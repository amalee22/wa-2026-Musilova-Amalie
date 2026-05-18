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
        
        require_once '../app/models/Database.php';
        require_once '../app/models/Recipe.php';
        
        $db = (new Database())->getConnection();
        $recipeModel = new Recipe($db);
        
        $recipes = $query ? $recipeModel->search($query) : $recipeModel->getAll(); 
        
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
        $recipe = (new Recipe($db))->getById($id);
        
        if (!$recipe) {
            $this->addErrorMessage('Hledaný recept v databázi neexistuje.');
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        $commentModel = new Comment($db);
        $comments = $commentModel->getByRecipeId($id);

        $likeModel = new Like($db);
        $favModel = new Favorite($db);
        
        $likesCount = $likeModel->getCount($id);
        $isLiked = false;
        $isFavorited = false;

        if (isset($_SESSION['user_id'])) {
            $isLiked = $likeModel->hasLiked($_SESSION['user_id'], $id);
            $isFavorited = $favModel->hasFavorited($_SESSION['user_id'], $id);
        }

        require_once '../app/views/recipes/recipe_show.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->requireAuth();
            $userId = $_SESSION['user_id'];

            $title = htmlspecialchars($_POST['title'] ?? '');
            $description = htmlspecialchars($_POST['description'] ?? '');
            $ingredients = htmlspecialchars($_POST['ingredients'] ?? '');
            $instructions = htmlspecialchars($_POST['instructions'] ?? '');
            $category = (int)($_POST['category'] ?? 0);
            $prep_time = (int)($_POST['prep_time'] ?? 0);
            
            $uploadedImages = $this->processImageUploads();

            require_once '../app/models/Database.php';
            require_once '../app/models/Recipe.php';
            $db = (new Database())->getConnection();
            $recipeModel = new Recipe($db);
            
            $isSaved = $recipeModel->create($title, $description, $ingredients, $instructions, $category, $prep_time, $uploadedImages, $userId);

            if ($isSaved) {
                $this->addSuccessMessage('Recept byl úspěšně uložen.');
            } else {
                $this->addErrorMessage('Nastala chyba při ukládání do databáze.');
            }
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }
    }

    public function edit($id = null) {
        $this->requireAuth();
        if (!$id) { header('Location: ' . BASE_URL . '/index.php'); exit; }
        
        require_once '../app/models/Database.php';
        require_once '../app/models/Recipe.php';
        require_once '../app/models/Category.php';

        $db = (new Database())->getConnection();
        $recipeModel = new Recipe($db);
        $recipe = $recipeModel->getById($id);

        if (!$recipe || $recipe['created_by'] != $_SESSION['user_id']) {
            $this->addErrorMessage('Nemáte oprávnění upravovat cizí recept.');
            header('Location: ' . BASE_URL . '/index.php'); exit;
        }

        $categories = (new Category($db))->getAllCategories();
        require_once '../app/views/recipes/recipe_edit.php';
    }

    public function update($id = null) {
        $this->requireAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = htmlspecialchars($_POST['title'] ?? '');
            $description = htmlspecialchars($_POST['description'] ?? '');
            $ingredients = htmlspecialchars($_POST['ingredients'] ?? '');
            $instructions = htmlspecialchars($_POST['instructions'] ?? '');
            $category = (int)($_POST['category'] ?? 0);
            $prep_time = (int)($_POST['prep_time'] ?? 0);

            require_once '../app/models/Database.php';
            require_once '../app/models/Recipe.php';
            $db = (new Database())->getConnection();
            $recipeModel = new Recipe($db);
            
            $existingRecipe = $recipeModel->getById($id);
            if (!$existingRecipe || $existingRecipe['created_by'] != $_SESSION['user_id']) {
                $this->addErrorMessage('Nemáte oprávnění upravovat cizí recept.');
                header('Location: ' . BASE_URL . '/index.php'); exit;
            }

            $uploadedImages = $this->processImageUploads();
            if (empty($uploadedImages)) {
                $uploadedImages = json_decode($existingRecipe['images'] ?? '[]', true) ?: [];
            } else {
                // OPRAVA: Pokud uživatel nahrál nové obrázky, smazat ty staré z disku
                $oldImages = json_decode($existingRecipe['images'] ?? '[]', true);
                if (!empty($oldImages)) {
                    $uploadDir = __DIR__ . '/../../public/uploads/';
                    foreach ($oldImages as $img) {
                        $filePath = $uploadDir . $img;
                        if (file_exists($filePath) && is_file($filePath)) {
                            unlink($filePath);
                        }
                    }
                }
            }

            $isUpdated = $recipeModel->update($id, $title, $description, $ingredients, $instructions, $category, $prep_time, $uploadedImages);

            if ($isUpdated) { 
                $this->addSuccessMessage('Recept byl úspěšně upraven.'); 
            } else { 
                $this->addErrorMessage('Nastala chyba při ukládání změn.'); 
            }
            
            header('Location: ' . BASE_URL . '/index.php'); exit;
        }
    }

    public function delete($id = null) {
        $this->requireAuth();
        if (!$id) { header('Location: ' . BASE_URL . '/index.php'); exit; }
        
        require_once '../app/models/Database.php';
        require_once '../app/models/Recipe.php';
        $db = (new Database())->getConnection();
        $recipeModel = new Recipe($db);
        $recipe = $recipeModel->getById($id);
        
        if (!$recipe || $recipe['created_by'] != $_SESSION['user_id']) {
            $this->addErrorMessage('Nemáte oprávnění k tomuto úkonu.');
            header('Location: ' . BASE_URL . '/index.php'); exit;
        }
        
        // OPRAVA: Smazání starých fotografií z disku při odstranění receptu
        $images = json_decode($recipe['images'] ?? '[]', true);
        if (!empty($images)) {
            $uploadDir = __DIR__ . '/../../public/uploads/';
            foreach ($images as $img) {
                $filePath = $uploadDir . $img;
                if (file_exists($filePath) && is_file($filePath)) {
                    unlink($filePath);
                }
            }
        }
        
        if ($recipeModel->delete($id)) { 
            $this->addSuccessMessage('Recept byl smazán.'); 
        } else { 
            $this->addErrorMessage('Nastala chyba.'); 
        }
        
        header('Location: ' . BASE_URL . '/index.php'); exit;
    }

    public function addComment() {
        $this->requireAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $recipeId = (int)($_POST['recipe_id'] ?? 0);
            $text = htmlspecialchars(trim($_POST['text'] ?? ''));
            $userId = $_SESSION['user_id'];

            if ($recipeId > 0 && !empty($text)) {
                require_once '../app/models/Database.php';
                require_once '../app/models/Comment.php';
                $db = (new Database())->getConnection();
                $commentModel = new Comment($db);
                if ($commentModel->create($recipeId, $userId, $text)) { 
                    $this->addSuccessMessage('Komentář byl úspěšně přidán.'); 
                } else { 
                    $this->addErrorMessage('Nastala chyba při ukládání komentáře.'); 
                }
            }
            header('Location: ' . BASE_URL . '/index.php?url=recipe/show/' . $recipeId); exit;
        }
    }

    public function toggleLike() {
        header('Content-Type: application/json');
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['error' => 'Not logged in']); exit;
        }
        
        $data = json_decode(file_get_contents("php://input"), true);
        $recipeId = (int)($data['recipe_id'] ?? 0);

        require_once '../app/models/Database.php';
        require_once '../app/models/Like.php';
        
        $db = (new Database())->getConnection();
        $likeModel = new Like($db);
        
        $result = $likeModel->toggleLike($_SESSION['user_id'], $recipeId);
        $result['count'] = $likeModel->getCount($recipeId);
        
        echo json_encode($result);
        exit;
    }

    public function toggleFavorite() {
        header('Content-Type: application/json');
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['error' => 'Not logged in']); exit;
        }
        
        $data = json_decode(file_get_contents("php://input"), true);
        $recipeId = (int)($data['recipe_id'] ?? 0);

        require_once '../app/models/Database.php';
        require_once '../app/models/Favorite.php';
        
        $db = (new Database())->getConnection();
        $favModel = new Favorite($db);
        
        $result = $favModel->toggleFavorite($_SESSION['user_id'], $recipeId);
        
        echo json_encode($result);
        exit;
    }

    protected function processImageUploads() {
        $uploadedFiles = [];
        $uploadDir = __DIR__ . '/../../public/uploads/';
        if (!is_dir($uploadDir)) { mkdir($uploadDir, 0777, true); }
        
        if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
            $fileCount = count($_FILES['images']['name']);
            for ($i = 0; $i < $fileCount; $i++) {
                if ($_FILES['images']['error'][$i] === UPLOAD_ERR_OK) {
                    $tmpName = $_FILES['images']['tmp_name'][$i];
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