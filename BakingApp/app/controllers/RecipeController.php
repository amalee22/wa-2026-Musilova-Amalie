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
        
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) $page = 1;
        $limit = 8;
        $offset = ($page - 1) * $limit;
        
        require_once '../app/models/Database.php'; 
        require_once '../app/models/Recipe.php';
        $db = (new Database())->getConnection(); 
        $recipeModel = new Recipe($db);
        
        if ($query) {
            $recipes = $recipeModel->search($query, $sort); 
            $totalPages = 1;
        } else {
            $totalRecipes = $recipeModel->getTotalCount();
            $totalPages = ceil($totalRecipes / $limit);
            $recipes = $recipeModel->getAll($sort, $limit, $offset);
        }
        
        require_once '../app/models/Tip.php';
        $tipModel = new Tip($db);
        $tips = $tipModel->getAll();

        require_once '../app/views/recipes/recipes_list.php';
    }

    public function create() {
        $this->requireAuth();
        require_once '../app/models/Database.php'; 
        require_once '../app/models/Category.php';
        $db = (new Database())->getConnection();
        $categories = (new Category($db))->getAllCategories();
        require_once '../app/views/recipes/recipe_create.php';
    }

    public function show($id = null) {
        if (!$id) { 
            $this->addErrorMessage('Nebylo zadáno ID receptu.'); 
            header('Location: ' . BASE_URL . '/index.php'); 
            exit; 
        }
        require_once '../app/models/Database.php'; 
        require_once '../app/models/Recipe.php'; 
        require_once '../app/models/Comment.php'; 
        require_once '../app/models/Like.php'; 
        require_once '../app/models/Favorite.php';

        $db = (new Database())->getConnection();
        $recipeModel = new Recipe($db);
        $recipe = $recipeModel->getById((int)$id);
        
        if (!$recipe) { 
            $this->addErrorMessage('Recept neexistuje.'); 
            header('Location: ' . BASE_URL . '/index.php'); 
            exit; 
        }

        $similarRecipes = $recipeModel->getSimilar((int)$recipe['category_id'], (int)$id);
        $commentModel = new Comment($db);
        $comments = $commentModel->getByRecipeId((int)$id);

        $likeModel = new Like($db); 
        $favModel = new Favorite($db);
        $likesCount = $likeModel->getCount((int)$id);
        $isLiked = isset($_SESSION['user_id']) ? $likeModel->hasLiked((int)$_SESSION['user_id'], (int)$id) : false;
        $isFavorited = isset($_SESSION['user_id']) ? $favModel->hasFavorited((int)$_SESSION['user_id'], (int)$id) : false;

        require_once '../app/views/recipes/recipe_show.php';
    }

    public function edit($id = null) {
        $this->requireAuth();
        if (!$id) { header('Location: ' . BASE_URL . '/index.php'); exit; }
        
        require_once '../app/models/Database.php'; 
        require_once '../app/models/Recipe.php'; 
        require_once '../app/models/Category.php';

        $db = (new Database())->getConnection();
        $recipeModel = new Recipe($db);
        $recipe = $recipeModel->getById((int)$id);

        $isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;

        if (!$recipe || ($recipe['created_by'] != $_SESSION['user_id'] && !$isAdmin)) {
            $this->addErrorMessage('Nemáte oprávnění k úpravě tohoto receptu.');
            header('Location: ' . BASE_URL . '/index.php'); exit;
        }

        $categories = (new Category($db))->getAllCategories();
        require_once '../app/views/recipes/recipe_edit.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                $this->addErrorMessage('Neplatný požadavek (CSRF).');
                header('Location: ' . BASE_URL . '/index.php?url=recipe/create'); exit;
            }

            $this->requireAuth();
            $title = trim($_POST['title'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $ingredients = trim($_POST['ingredients'] ?? '');
            $instructions = trim($_POST['instructions'] ?? '');
            $category = (int)($_POST['category'] ?? 0);
            $prep_time = (int)($_POST['prep_time'] ?? 0);
            
            $uploadedImages = $this->processImageUploads();

            require_once '../app/models/Database.php'; 
            require_once '../app/models/Recipe.php';
            $db = (new Database())->getConnection(); 
            $recipeModel = new Recipe($db);
            
            if ($recipeModel->create($title, $description, $ingredients, $instructions, $category, $prep_time, $uploadedImages, (int)$_SESSION['user_id'])) {
                $this->addSuccessMessage('Recept byl úspěšně uložen.');
            } else {
                $this->addErrorMessage('Nastala chyba při ukládání do databáze.');
            }
            header('Location: ' . BASE_URL . '/index.php'); exit;
        }
    }

    public function update($id = null) {
        $this->requireAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                $this->addErrorMessage('Neplatný požadavek (CSRF).');
                header('Location: ' . BASE_URL . '/index.php?url=recipe/edit/' . (int)$id); exit;
            }
            $title = trim($_POST['title'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $ingredients = trim($_POST['ingredients'] ?? '');
            $instructions = trim($_POST['instructions'] ?? '');
            $category = (int)($_POST['category'] ?? 0);
            $prep_time = (int)($_POST['prep_time'] ?? 0);

            require_once '../app/models/Database.php'; 
            require_once '../app/models/Recipe.php';
            $db = (new Database())->getConnection(); 
            $recipeModel = new Recipe($db);
            
            $existingRecipe = $recipeModel->getById((int)$id);
            $isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;

            if (!$existingRecipe || ($existingRecipe['created_by'] != $_SESSION['user_id'] && !$isAdmin)) {
                $this->addErrorMessage('Nemáte oprávnění.');
                header('Location: ' . BASE_URL . '/index.php'); exit;
            }

            $uploadedImages = $this->processImageUploads();
            if (empty($uploadedImages)) {
                $uploadedImages = json_decode($existingRecipe['images'] ?? '[]', true) ?: [];
            } else {
                $oldImages = json_decode($existingRecipe['images'] ?? '[]', true) ?: [];
                $uploadedImages = array_merge($oldImages, $uploadedImages);
            }

            if ($recipeModel->update((int)$id, $title, $description, $ingredients, $instructions, $category, $prep_time, $uploadedImages)) { 
                $this->addSuccessMessage('Recept byl úspěšně upraven.'); 
            } else { 
                $this->addErrorMessage('Nastala chyba při ukládání.'); 
            }
            header('Location: ' . BASE_URL . '/index.php?url=recipe/show/' . (int)$id); exit;
        }
    }

    public function delete($id = null) {
        $this->requireAuth();
        if (!$id) { header('Location: ' . BASE_URL . '/index.php'); exit; }
        
        require_once '../app/models/Database.php'; 
        require_once '../app/models/Recipe.php';
        $db = (new Database())->getConnection(); 
        $recipeModel = new Recipe($db);
        $recipe = $recipeModel->getById((int)$id);
        
        $isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;

        if (!$recipe || ($recipe['created_by'] != $_SESSION['user_id'] && !$isAdmin)) {
            $this->addErrorMessage('Nemáte oprávnění.');
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
        
        if ($recipeModel->delete((int)$id)) { $this->addSuccessMessage('Recept byl trvale smazán.'); } 
        header('Location: ' . BASE_URL . '/index.php'); exit;
    }

    public function addComment() {
        $this->requireAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $recipeId = (int)($_POST['recipe_id'] ?? 0);
            $parentId = !empty($_POST['parent_id']) ? (int)$_POST['parent_id'] : null;
            $text = htmlspecialchars(trim($_POST['text'] ?? ''));
            
            $commentImage = null;
            if (isset($_FILES['comment_image']) && $_FILES['comment_image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../../public/uploads/';
                $ext = strtolower(pathinfo($_FILES['comment_image']['name'], PATHINFO_EXTENSION));
                if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                    $newName = 'user_photo_' . uniqid() . '.' . $ext;
                    if (move_uploaded_file($_FILES['comment_image']['tmp_name'], $uploadDir . $newName)) {
                        $commentImage = $newName;
                    }
                }
            }
            
            if ($recipeId > 0 && !empty($text)) {
                require_once '../app/models/Database.php'; 
                require_once '../app/models/Comment.php';
                $db = (new Database())->getConnection(); 
                $commentModel = new Comment($db);
                
                $commentModel->create((int)$recipeId, (int)$_SESSION['user_id'], $text, $parentId, $commentImage);
                $this->addSuccessMessage('Hodnocení bylo přidáno.');
            }
            header('Location: ' . BASE_URL . '/index.php?url=recipe/show/' . (int)$recipeId); exit;
        }
    }

    public function editComment($id = null) {
        $this->requireAuth();
        if (!$id) { header('Location: ' . BASE_URL . '/index.php'); exit; }
        require_once '../app/models/Database.php'; 
        require_once '../app/models/Comment.php';
        $db = (new Database())->getConnection(); 
        $commentModel = new Comment($db);
        $comment = $commentModel->getById((int)$id);
        
        $isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;
        if (!$comment || ($comment['user_id'] != $_SESSION['user_id'] && !$isAdmin)) {
            $this->addErrorMessage('Nemáte oprávnění.');
            header('Location: ' . BASE_URL . '/index.php'); exit;
        }
        require_once '../app/views/recipes/comment_edit.php';
    }

    public function updateComment($id = null) {
        $this->requireAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
            $text = htmlspecialchars(trim($_POST['text'] ?? ''));
            require_once '../app/models/Database.php'; 
            require_once '../app/models/Comment.php';
            $db = (new Database())->getConnection(); 
            $commentModel = new Comment($db);
            $comment = $commentModel->getById((int)$id);
            
            $isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;
            if (!$comment || ($comment['user_id'] != $_SESSION['user_id'] && !$isAdmin)) {
                $this->addErrorMessage('Nemáte oprávnění.');
                header('Location: ' . BASE_URL . '/index.php'); exit;
            }
            if (!empty($text)) { $commentModel->update((int)$id, $text); }
            header('Location: ' . BASE_URL . '/index.php?url=recipe/show/' . (int)$comment['recipe_id']); exit;
        }
    }

    public function deleteComment($id = null) {
        $this->requireAuth();
        if (!$id) { header('Location: ' . BASE_URL . '/index.php'); exit; }
        require_once '../app/models/Database.php'; 
        require_once '../app/models/Comment.php';
        $db = (new Database())->getConnection(); 
        $commentModel = new Comment($db);
        $comment = $commentModel->getById((int)$id);
        
        $isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;
        if (!$comment || ($comment['user_id'] != $_SESSION['user_id'] && !$isAdmin)) {
            $this->addErrorMessage('Nemáte oprávnění.');
            header('Location: ' . BASE_URL . '/index.php'); exit;
        }
        
        $recipeId = $comment['recipe_id'];
        $commentModel->delete((int)$id);
        $this->addSuccessMessage('Komentář smazán.');
        header('Location: ' . BASE_URL . '/index.php?url=recipe/show/' . (int)$recipeId); exit;
    }

    public function createTip() {
        $this->requireAuth();
        require_once '../app/views/recipes/tip_create.php';
    }

    public function storeTip() {
        $this->requireAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = htmlspecialchars($_POST['title'] ?? '');
            $content = htmlspecialchars($_POST['content'] ?? '');
            $icon = htmlspecialchars($_POST['icon'] ?? 'fas fa-lightbulb');
            
            require_once '../app/models/Database.php';
            require_once '../app/models/Tip.php';
            $db = (new Database())->getConnection();
            
            if ((new Tip($db))->create($title, $content, $icon, $_SESSION['user_id'])) {
                $this->addSuccessMessage('Super! Váš tip byl přidán.');
            } else {
                $this->addErrorMessage('Nastala chyba při ukládání tipu.');
            }
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }
    }

    public function deleteTip($id = null) {
        $this->requireAuth();
        if (!$id) { header('Location: ' . BASE_URL . '/index.php'); exit; }

        require_once '../app/models/Database.php';
        require_once '../app/models/Tip.php';
        $db = (new Database())->getConnection();
        $tipModel = new Tip($db);
        $tip = $tipModel->getById((int)$id);

        $isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;

        if (!$tip || ($tip['created_by'] != $_SESSION['user_id'] && !$isAdmin)) {
            $this->addErrorMessage('Nemáte oprávnění smazat tento tip.');
            header('Location: ' . BASE_URL . '/index.php'); exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                $this->addErrorMessage('Neplatný požadavek (CSRF).');
            } else {
                if ($tipModel->delete((int)$id)) {
                    $this->addSuccessMessage('Tip byl úspěšně smazán.');
                } else {
                    $this->addErrorMessage('Nastala chyba při mazání tipu.');
                }
            }
        }
        header('Location: ' . BASE_URL . '/index.php'); exit;
    }

    public function toggleLike() {
        header('Content-Type: application/json');
        if (!isset($_SESSION['user_id'])) { echo json_encode(['error' => 'Not logged in']); exit; }
        $data = json_decode(file_get_contents("php://input"), true); 
        $recipeId = (int)($data['recipe_id'] ?? 0);
        require_once '../app/models/Database.php'; 
        require_once '../app/models/Like.php';
        $db = (new Database())->getConnection(); 
        $likeModel = new Like($db);
        $result = $likeModel->toggleLike((int)$_SESSION['user_id'], $recipeId);
        $result['count'] = $likeModel->getCount($recipeId);
        echo json_encode($result); exit;
    }

    public function toggleFavorite() {
        header('Content-Type: application/json');
        if (!isset($_SESSION['user_id'])) { echo json_encode(['error' => 'Not logged in']); exit; }
        $data = json_decode(file_get_contents("php://input"), true); 
        $recipeId = (int)($data['recipe_id'] ?? 0);
        require_once '../app/models/Database.php'; 
        require_once '../app/models/Favorite.php';
        $db = (new Database())->getConnection(); 
        $favModel = new Favorite($db);
        echo json_encode($favModel->toggleFavorite((int)$_SESSION['user_id'], $recipeId)); exit;
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

    public function deleteImage($recipeId = null, $fileName = null) {
        $this->requireAuth();
        if (!$recipeId || !$fileName) { header('Location: ' . BASE_URL . '/index.php'); exit; }

        require_once '../app/models/Database.php';
        require_once '../app/models/Recipe.php';
        $db = (new Database())->getConnection();
        $recipeModel = new Recipe($db);
        $recipe = $recipeModel->getById((int)$recipeId);

        $isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;
        if (!$recipe || ($recipe['created_by'] != $_SESSION['user_id'] && !$isAdmin)) {
            $this->addErrorMessage('Nemáte oprávnění.');
            header('Location: ' . BASE_URL . '/index.php'); exit;
        }

        $images = json_decode($recipe['images'] ?? '[]', true);
        if (($key = array_search($fileName, $images)) !== false) {
            unset($images[$key]);
            $images = array_values($images);
            
            $filePath = __DIR__ . '/../../public/uploads/' . $fileName;
            if (file_exists($filePath)) { unlink($filePath); }

            $recipeModel->update((int)$recipeId, $recipe['title'], $recipe['description'], $recipe['ingredients'], $recipe['instructions'], $recipe['category_id'], $recipe['prep_time'], $images);
            $this->addSuccessMessage('Obrázek byl smazán.');
        }

        header('Location: ' . BASE_URL . '/index.php?url=recipe/edit/' . (int)$recipeId); exit;
    }

    protected function addSuccessMessage($message) { $_SESSION['messages']['success'][] = $message; }
    protected function addErrorMessage($message) { $_SESSION['messages']['error'][] = $message; }
}