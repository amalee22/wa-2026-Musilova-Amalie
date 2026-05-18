<?php 

class BookController {
    
    protected function requireAuth() {
        if (!isset($_SESSION['user_id'])) {
            $this->addErrorMessage('Pro tuto akci se musíte přihlásit.');
            header('Location: ' . BASE_URL . '/index.php?url=auth/login');
            exit;
        }
    }

    public function index() {
        require_once '../app/models/Database.php';
        require_once '../app/models/Book.php';
        $db = (new Database())->getConnection();
        $bookModel = new Book($db);
        $books = $bookModel->getAll(); 
        require_once '../app/views/books/books_list.php';
    }

    public function create() {
        $this->requireAuth();
        require_once '../app/models/Database.php';
        require_once '../app/models/Category.php';
        require_once '../app/models/Subcategory.php'; 
        $db = (new Database())->getConnection();
        
        $categories = (new Category($db))->getAllCategories();
        $subcategories = (new Subcategory($db))->getAllSubcategories();

        require_once '../app/views/books/book_create.php';
    }

    public function show($id = null) {
        if (!$id) {
            $this->addErrorMessage('Nebylo zadáno ID knihy.');
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }
        require_once '../app/models/Database.php';
        require_once '../app/models/Book.php';
        require_once '../app/models/Comment.php'; // ZMĚNA: Přidán model Comment
        
        $db = (new Database())->getConnection();
        $book = (new Book($db))->getById($id);
        
        if (!$book) {
            $this->addErrorMessage('Hledaná kniha v databázi neexistuje.');
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }
        
        // ZMĚNA: Načtení komentářů k dané knize
        $commentModel = new Comment($db);
        $comments = $commentModel->getByBookId($id);

        require_once '../app/views/books/book_show.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->requireAuth();
            $userId = $_SESSION['user_id'];

            $title = htmlspecialchars($_POST['title'] ?? '');
            $author = htmlspecialchars($_POST['author'] ?? '');
            $isbn = htmlspecialchars($_POST['isbn'] ?? '');
            $category = (int)($_POST['category'] ?? 0);
            $subcategory = (int)($_POST['subcategory'] ?? 0);
            $year = (int)($_POST['year'] ?? 0);
            $price = (float)($_POST['price'] ?? 0);
            $link = htmlspecialchars($_POST['link'] ?? '');
            $description = htmlspecialchars($_POST['description'] ?? '');
            $uploadedImages = $this->processImageUploads();

            require_once '../app/models/Database.php';
            require_once '../app/models/Book.php';
            $db = (new Database())->getConnection();
            $bookModel = new Book($db);
            
            $isSaved = $bookModel->create($title, $author, $category, $subcategory, $year, $price, $isbn, $description, $link, $uploadedImages, $userId);

            if ($isSaved) {
                $this->addSuccessMessage('Kniha byla úspěšně uložena.');
            } else {
                $this->addErrorMessage('Nastala chyba při ukládání do databáze.');
            }
            header('Location: ' . BASE_URL . '/index.php'); exit;
        }
    }

    public function edit($id = null) {
        $this->requireAuth();
        if (!$id) { header('Location: ' . BASE_URL . '/index.php'); exit; }

        require_once '../app/models/Database.php';
        require_once '../app/models/Book.php';
        require_once '../app/models/Category.php';
        require_once '../app/models/Subcategory.php';

        $db = (new Database())->getConnection();
        $bookModel = new Book($db);
        $book = $bookModel->getById($id);

        $isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;

        if (!$book || ($book['created_by'] != $_SESSION['user_id'] && !$isAdmin)) {
            $this->addErrorMessage('Nemáte oprávnění upravovat cizí záznam.');
            header('Location: ' . BASE_URL . '/index.php'); exit;
        }

        $categories = (new Category($db))->getAllCategories();
        $subcategories = (new Subcategory($db))->getAllSubcategories();

        require_once '../app/views/books/book_edit.php';
    }

    public function update($id = null) {
        $this->requireAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = htmlspecialchars($_POST['title'] ?? '');
            $author = htmlspecialchars($_POST['author'] ?? '');
            $isbn = htmlspecialchars($_POST['isbn'] ?? '');
            $category = (int)($_POST['category'] ?? 0);
            $subcategory = (int)($_POST['subcategory'] ?? 0);
            $year = (int)($_POST['year'] ?? 0);
            $price = (float)($_POST['price'] ?? 0);
            $link = htmlspecialchars($_POST['link'] ?? '');
            $description = htmlspecialchars($_POST['description'] ?? '');

            require_once '../app/models/Database.php';
            require_once '../app/models/Book.php';
            $db = (new Database())->getConnection();
            $bookModel = new Book($db);
            
            $existingBook = $bookModel->getById($id);
            $isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;

            if (!$existingBook || ($existingBook['created_by'] != $_SESSION['user_id'] && !$isAdmin)) {
                $this->addErrorMessage('Nemáte oprávnění upravovat cizí záznam.');
                header('Location: ' . BASE_URL . '/index.php'); exit;
            }

            $uploadedImages = $this->processImageUploads();
            if (empty($uploadedImages)) {
                $uploadedImages = json_decode($existingBook['images'] ?? '[]', true) ?: [];
            } else {
                $oldImages = json_decode($existingBook['images'] ?? '[]', true);
                if (!empty($oldImages)) {
                    $uploadDir = __DIR__ . '/../../public/uploads/';
                    foreach ($oldImages as $img) {
                        $filePath = $uploadDir . $img;
                        if (file_exists($filePath) && is_file($filePath)) { unlink($filePath); }
                    }
                }
            }

            $isUpdated = $bookModel->update($id, $title, $author, $category, $subcategory, $year, $price, $isbn, $description, $link, $uploadedImages, $_SESSION['user_id']);

            if ($isUpdated) { $this->addSuccessMessage('Kniha byla úspěšně upravena.'); } 
            else { $this->addErrorMessage('Nastala chyba při ukládání.'); }
            header('Location: ' . BASE_URL . '/index.php'); exit;
        }
    }

    public function delete($id = null) {
        $this->requireAuth();
        if (!$id) { header('Location: ' . BASE_URL . '/index.php'); exit; }
        
        require_once '../app/models/Database.php';
        require_once '../app/models/Book.php';
        $db = (new Database())->getConnection();
        $bookModel = new Book($db);
        $book = $bookModel->getById($id);
        
        $isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;

        if (!$book || ($book['created_by'] != $_SESSION['user_id'] && !$isAdmin)) {
            $this->addErrorMessage('Knihu se nepodařilo smazat. Nemáte k tomu oprávnění.');
            header('Location: ' . BASE_URL . '/index.php'); exit;
        }

        $images = json_decode($book['images'] ?? '[]', true);
        if (!empty($images)) {
            $uploadDir = __DIR__ . '/../../public/uploads/';
            foreach ($images as $img) {
                $filePath = $uploadDir . $img;
                if (file_exists($filePath) && is_file($filePath)) { unlink($filePath); }
            }
        }

        if ($bookModel->delete($id)) { $this->addSuccessMessage('Kniha byla trvale smazána.'); } 
        else { $this->addErrorMessage('Nastala chyba. Knihu se nepodařilo smazat.'); }
        header('Location: ' . BASE_URL . '/index.php'); exit;
    }

    // ZMĚNA: Přidána metoda pro uložení komentáře
    public function addComment() {
        $this->requireAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $bookId = (int)($_POST['book_id'] ?? 0);
            $text = htmlspecialchars(trim($_POST['text'] ?? ''));

            if ($bookId > 0 && !empty($text)) {
                require_once '../app/models/Database.php';
                require_once '../app/models/Comment.php';
                $db = (new Database())->getConnection();
                $commentModel = new Comment($db);
                if ($commentModel->create($bookId, $_SESSION['user_id'], $text)) { 
                    $this->addSuccessMessage('Komentář byl úspěšně přidán.'); 
                } else { 
                    $this->addErrorMessage('Nastala chyba při ukládání komentáře.'); 
                }
            }
            header('Location: ' . BASE_URL . '/index.php?url=book/show/' . $bookId); exit;
        }
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
                    if (!in_array($fileExtension, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) continue;
                    $newName = 'book_' . uniqid() . '_' . substr(md5(mt_rand()), 0, 4) . '.' . $fileExtension;
                    if (move_uploaded_file($tmpName, $uploadDir . $newName)) { $uploadedFiles[] = $newName; }
                }
            }
        }
        return $uploadedFiles;
    }

    protected function addSuccessMessage($message) { $_SESSION['messages']['success'][] = $message; }
    protected function addNoticeMessage($message) { $_SESSION['messages']['notice'][] = $message; }
    protected function addErrorMessage($message) { $_SESSION['messages']['error'][] = $message; }
}