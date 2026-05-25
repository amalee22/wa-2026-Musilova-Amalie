<?php 

class RecipeController {
    
    protected function requireAuth() {
        if (!isset($_SESSION['user_id'])) {
            $this->addErrorMessage('Pro tuto akci se musíte přihlásit.');
            header('Location: ' . BASE_URL . '/index.php?url=auth/login');
            exit;
        }
    }

    public function index() {
        $query = $_GET['q'] ?? '';
        $sort = $_GET['sort'] ?? 'latest'; 
        
        // Výpočet stránkování
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) $page = 1;
        $limit = 8; // Kolik receptů na jednu stranu chceme
        $offset = ($page - 1) * $limit;
        
        require_once '../app/models/Database.php'; require_once '../app/models/Recipe.php';
        $db = (new Database())->getConnection(); $recipeModel = new Recipe($db);
        
        if ($query) {
            $recipes = $recipeModel->search($query, $sort); 
            $totalPages = 1; // Při vyhledávání stránkování schováme
        } else {
            $totalRecipes = $recipeModel->getTotalCount();
            $totalPages = ceil($totalRecipes / $limit);
            $recipes = $recipeModel->getAll($sort, $limit, $offset);
        }
        
        require_once '../app/views/recipes/recipes_list.php';
    }

    public function create() {
        $this->requireAuth();
        require_once '../app/models/Database.php'; require_once '../app/models/Category.php';
        $db = (new Database())->getConnection();
        $categories = (new Category($db))->getAllCategories();
        require_once '../app/views/recipes/recipe_create.php';
    }

    public function show($id = null) {
        if (!$id) { $this->addErrorMessage('Nebylo zadáno ID receptu.'); header('Location: ' . BASE_URL . '/index.php'); exit; }
        require_once '../app/models/Database.php'; require_once '../app/models/Recipe.php'; require_once '../app/models/Comment.php'; require_once '../app/models/Like.php'; require_once '../app/models/Favorite.php';

        $db = (new Database())->getConnection();
        $recipeModel = new Recipe($db);
        $recipe = $recipeModel->getById($id);
        
        if (!$recipe) { $this->addErrorMessage('Hledaný recept v databázi neexistuje.'); header('Location: ' . BASE_URL . '/index.php'); exit; }

        // Načtení podobných receptů
        $similarRecipes = $recipeModel->getSimilar((int)$recipe['category_id'], $id);

        $commentModel = new Comment($db);
        $comments = $commentModel->getByRecipeId($id);

        $likeModel = new Like($db); $favModel = new Favorite($db);
        $likesCount = $likeModel->getCount($id);
        $isLiked = false; $isFavorited = false;

        if (isset($_SESSION['user_id'])) {
            $isLiked = $likeModel->hasLiked($_SESSION['user_id'], $id);
            $isFavorited = $favModel->hasFavorited($_SESSION['user_id'], $id);
        }
        require_once '../app/views/recipes/recipe_show.php';
    }

    public function edit($id = null) {
        $this->requireAuth();
        if (!$id) { header('Location: ' . BASE_URL . '/index.php'); exit; }
        
        require_once '../app/models/Database.php'; require_once '../app/models/Recipe.php'; require_once '../app/models/Category.php';

        $db = (new Database())->getConnection();
        $recipeModel = new Recipe($db);
        $recipe = $recipeModel->getById($id);

        $isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;

        if (!$recipe || ($recipe['created_by'] != $_SESSION['user_id'] && !$isAdmin)) {
            $this->addErrorMessage('Nemáte oprávnění upravovat cizí recept.');
            header('Location: ' . BASE_URL . '/index.php'); exit;
        }

        $categories = (new Category($db))->getAllCategories();
        require_once '../app/views/recipes/recipe_edit.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                $this->addErrorMessage('Neplatný požadavek (CSRF selhalo). Zkuste to znovu.');
                header('Location: ' . BASE_URL . '/index.php?url=recipe/create'); exit;
            }

            $this->requireAuth();
            $userId = $_SESSION['user_id'];

            $title = trim($_POST['title'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $ingredients = trim($_POST['ingredients'] ?? '');
            $instructions = trim($_POST['instructions'] ?? '');
            $category = (int)($_POST['category'] ?? 0);
            $prep_time = (int)($_POST['prep_time'] ?? 0);
            
            $uploadedImages = $this->processImageUploads();

            require_once '../app/models/Database.php'; require_once '../app/models/Recipe.php';
            $db = (new Database())->getConnection(); $recipeModel = new Recipe($db);
            
            if ($recipeModel->create($title, $description, $ingredients, $instructions, $category, $prep_time, $uploadedImages, $userId)) {
                $this->addSuccessMessage('Recept byl úspěšně uložen.');
            } else {
                $this->addErrorMessage('Nastala chyba při ukládání do databáze.');
            }
            header('Location: ' . BASE_URL . '/index.php'); exit;
        }
    }

    public function update($id = null) {
        $this->requireAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                $this->addErrorMessage('Neplatný požadavek (CSRF selhalo). Zkuste to znovu.');
                header('Location: ' . BASE_URL . '/index.php?url=recipe/edit/' . $id); exit;
            }

            $title = trim($_POST['title'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $ingredients = trim($_POST['ingredients'] ?? '');
            $instructions = trim($_POST['instructions'] ?? '');
            $category = (int)($_POST['category'] ?? 0);
            $prep_time = (int)($_POST['prep_time'] ?? 0);

            require_once '../app/models/Database.php'; require_once '../app/models/Recipe.php';
            $db = (new Database())->getConnection(); $recipeModel = new Recipe($db);
            
            $existingRecipe = $recipeModel->getById($id);
            $isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;

            if (!$existingRecipe || ($existingRecipe['created_by'] != $_SESSION['user_id'] && !$isAdmin)) {
                $this->addErrorMessage('Nemáte oprávnění upravovat cizí recept.');
                header('Location: ' . BASE_URL . '/index.php'); exit;
            }

            $uploadedImages = $this->processImageUploads();
            if (empty($uploadedImages)) {
                $uploadedImages = json_decode($existingRecipe['images'] ?? '[]', true) ?: [];
            } else {
                $oldImages = json_decode($existingRecipe['images'] ?? '[]', true);
                if (!empty($oldImages)) {
                    $uploadDir = __DIR__ . '/../../public/uploads/';
                    foreach ($oldImages as $img) {
                        $filePath = $uploadDir . $img;
                        if (file_exists($filePath) && is_file($filePath)) { unlink($filePath); }
                    }
                }
            }

            if ($recipeModel->update($id, $title, $description, $ingredients, $instructions, $category, $prep_time, $uploadedImages)) { 
                $this->addSuccessMessage('Recept byl úspěšně upraven.'); 
            } else { 
                $this->addErrorMessage('Nastala chyba při ukládání změn.'); 
            }
            header('Location: ' . BASE_URL . '/index.php?url=recipe/show/' . $id); exit;
        }
    }

    public function delete($id = null) {
        $this->requireAuth();
        if (!$id) { header('Location: ' . BASE_URL . '/index.php'); exit; }
        
        require_once '../app/models/Database.php'; require_once '../app/models/Recipe.php';
        $db = (new Database())->getConnection(); $recipeModel = new Recipe($db);
        $recipe = $recipeModel->getById($id);
        
        $isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;

        if (!$recipe || ($recipe['created_by'] != $_SESSION['user_id'] && !$isAdmin)) {
            $this->addErrorMessage('Nemáte oprávnění k tomuto úkonu.');
            header('Location: ' . BASE_URL . '/index.php'); exit;
        }
        
        $images = json_decode($recipe['images'] ?? '[]', true);
        if (!empty($images)) {
            $uploadDir = __DIR__ . '/../../public/uploads/';
            foreach ($images as $img) {
                $filePath = $uploadDir . $img;
                if (file_exists($filePath) && is_file($filePath)) { unlink($filePath); }
            }
        }
        
        if ($recipeModel->delete($id)) { $this->addSuccessMessage('Recept byl smazán.'); } 
        else { $this->addErrorMessage('Nastala chyba.'); }
        header('Location: ' . BASE_URL . '/index.php'); exit;
    }

    public function addComment() {
        $this->requireAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $recipeId = (int)($_POST['recipe_id'] ?? 0);
            $text = htmlspecialchars(trim($_POST['text'] ?? ''));
            if ($recipeId > 0 && !empty($text)) {
                require_once '../app/models/Database.php'; require_once '../app/models/Comment.php';
                $db = (new Database())->getConnection(); $commentModel = new Comment($db);
                if ($commentModel->create($recipeId, $_SESSION['user_id'], $text)) { $this->addSuccessMessage('Komentář byl přidán.'); } 
                else { $this->addErrorMessage('Nastala chyba.'); }
            }
            header('Location: ' . BASE_URL . '/index.php?url=recipe/show/' . $recipeId); exit;
        }
    }

    public function editComment($id = null) {
        $this->requireAuth();
        if (!$id) { header('Location: ' . BASE_URL . '/index.php'); exit; }
        
        require_once '../app/models/Database.php'; require_once '../app/models/Comment.php';
        $db = (new Database())->getConnection(); $commentModel = new Comment($db);
        $comment = $commentModel->getById($id);
        
        $isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;
        if (!$comment || ($comment['user_id'] != $_SESSION['user_id'] && !$isAdmin)) {
            $this->addErrorMessage('Nemáte oprávnění upravovat tento komentář.');
            header('Location: ' . BASE_URL . '/index.php'); exit;
        }
        
        require_once '../app/views/recipes/comment_edit.php';
    }

    public function updateComment($id = null) {
        $this->requireAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
            $text = htmlspecialchars(trim($_POST['text'] ?? ''));
            require_once '../app/models/Database.php'; require_once '../app/models/Comment.php';
            $db = (new Database())->getConnection(); $commentModel = new Comment($db);
            $comment = $commentModel->getById($id);
            
            $isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;
            if (!$comment || ($comment['user_id'] != $_SESSION['user_id'] && !$isAdmin)) {
                $this->addErrorMessage('Nemáte oprávnění.');
                header('Location: ' . BASE_URL . '/index.php'); exit;
            }
            
            if (!empty($text)) {
                $commentModel->update($id, $text);
                $this->addSuccessMessage('Komentář byl úspěšně upraven.');
            }
            header('Location: ' . BASE_URL . '/index.php?url=recipe/show/' . $comment['recipe_id']); exit;
        }
    }

    public function deleteComment($id = null) {
        $this->requireAuth();
        if (!$id) { header('Location: ' . BASE_URL . '/index.php'); exit; }
        
        require_once '../app/models/Database.php'; require_once '../app/models/Comment.php';
        $db = (new Database())->getConnection(); $commentModel = new Comment($db);
        $comment = $commentModel->getById($id);
        
        $isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;
        if (!$comment || ($comment['user_id'] != $_SESSION['user_id'] && !$isAdmin)) {
            $this->addErrorMessage('Nemáte oprávnění.');
            header('Location: ' . BASE_URL . '/index.php'); exit;
        }
        
        $recipeId = $comment['recipe_id'];
        $commentModel->delete($id);
        $this->addSuccessMessage('Komentář byl smazán.');
        header('Location: ' . BASE_URL . '/index.php?url=recipe/show/' . $recipeId); exit;
    }

    public function toggleLike() {
        header('Content-Type: application/json');
        if (!isset($_SESSION['user_id'])) { echo json_encode(['error' => 'Not logged in']); exit; }
        $data = json_decode(file_get_contents("php://input"), true); $recipeId = (int)($data['recipe_id'] ?? 0);
        require_once '../app/models/Database.php'; require_once '../app/models/Like.php';
        $db = (new Database())->getConnection(); $likeModel = new Like($db);
        $result = $likeModel->toggleLike($_SESSION['user_id'], $recipeId);
        $result['count'] = $likeModel->getCount($recipeId);
        echo json_encode($result); exit;
    }

    public function toggleFavorite() {
        header('Content-Type: application/json');
        if (!isset($_SESSION['user_id'])) { echo json_encode(['error' => 'Not logged in']); exit; }
        $data = json_decode(file_get_contents("php://input"), true); $recipeId = (int)($data['recipe_id'] ?? 0);
        require_once '../app/models/Database.php'; require_once '../app/models/Favorite.php';
        $db = (new Database())->getConnection(); $favModel = new Favorite($db);
        echo json_encode($favModel->toggleFavorite($_SESSION['user_id'], $recipeId)); exit;
    }

   protected function processImageUploads() {
        $uploadedFiles = []; 
        $uploadDir = __DIR__ . '/../../public/uploads/';
        if (!is_dir($uploadDir)) { mkdir($uploadDir, 0777, true); }
        
        if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
            $fileCount = count($_FILES['images']['name']);
            $allowedMimes = ['image/jpeg', 'image/png', 'image/webp']; 

            for ($i = 0; $i < $fileCount; $i++) {
                if ($_FILES['images']['error'][$i] === UPLOAD_ERR_OK) {
                    $tmpName = $_FILES['images']['tmp_name'][$i];
                    
                    $finfo = finfo_open(FILEINFO_MIME_TYPE);
                    $mimeType = finfo_file($finfo, $tmpName);
                    finfo_close($finfo);

                    if (!in_array($mimeType, $allowedMimes)) { continue; }

                    $fileExtension = strtolower(pathinfo($_FILES['images']['name'][$i], PATHINFO_EXTENSION));
                    if (!in_array($fileExtension, ['jpg', 'jpeg', 'png', 'webp'])) continue;
                    
                    $newName = 'recipe_' . uniqid() . '_' . substr(md5(mt_rand()), 0, 4) . '.' . $fileExtension;
                    if (move_uploaded_file($tmpName, $uploadDir . $newName)) { 
                        $uploadedFiles[] = $newName; 
                    }
                }
            }
        }
        return $uploadedFiles;
    }

    protected function addSuccessMessage($message) { $_SESSION['messages']['success'][] = $message; }
    protected function addErrorMessage($message) { $_SESSION['messages']['error'][] = $message; }
}