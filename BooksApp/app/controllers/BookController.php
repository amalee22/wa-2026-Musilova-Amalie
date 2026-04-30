<?php 

class BookController {
    
    // Pomocná metoda pro kontrolu přihlášení
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

    // Zobrazení formuláře pro přidání (KROK 3)
    public function create() {
        $this->requireAuth();

        require_once '../app/models/Database.php';
        require_once '../app/models/Category.php';
        require_once '../app/models/Subcategory.php'; // Změna: nový model

        $db = (new Database())->getConnection();
        
        // Načtení seznamů pro <select> prvky ve View
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
        $db = (new Database())->getConnection();
        $book = (new Book($db))->getById($id);
        if (!$book) {
            $this->addErrorMessage('Hledaná kniha v databázi neexistuje.');
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }
        require_once '../app/views/books/book_show.php';
    }

    // Zpracování nového záznamu (KROK 5)
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->requireAuth();
            $userId = $_SESSION['user_id'];

            $title = htmlspecialchars($_POST['title'] ?? '');
            $author = htmlspecialchars($_POST['author'] ?? '');
            $isbn = htmlspecialchars($_POST['isbn'] ?? '');
            
            // ZMĚNA: Přetypování na (int), protože posíláme ID z <selectu>
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
            
            $isSaved = $bookModel->create(
                $title, $author, $category, $subcategory, 
                $year, $price, $isbn, $description, $link, $uploadedImages, $userId
            );

            if ($isSaved) {
                $this->addSuccessMessage('Kniha byla úspěšně uložena.');
            } else {
                $this->addErrorMessage('Nastala chyba při ukládání do databáze.');
            }
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }
    }

    // Zobrazení editačního formuláře
    public function edit($id = null) {
        $this->requireAuth();
        if (!$id) {
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        require_once '../app/models/Database.php';
        require_once '../app/models/Book.php';
        require_once '../app/models/Category.php';
        require_once '../app/models/Subcategory.php';

        $db = (new Database())->getConnection();
        $bookModel = new Book($db);
        $book = $bookModel->getById($id);

        if (!$book || $book['created_by'] != $_SESSION['user_id']) {
            $this->addErrorMessage('Nemáte oprávnění upravovat cizí záznam.');
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        // Načtení dat pro editační formulář
        $categories = (new Category($db))->getAllCategories();
        $subcategories = (new Subcategory($db))->getAllSubcategories();

        require_once '../app/views/books/book_edit.php';
    }

    // Aktualizace záznamu
    public function update($id = null) {
        $this->requireAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = htmlspecialchars($_POST['title'] ?? '');
            $author = htmlspecialchars($_POST['author'] ?? '');
            $isbn = htmlspecialchars($_POST['isbn'] ?? '');
            
            // ZMĚNA: Přetypování na (int)
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
            if (!$existingBook || $existingBook['created_by'] != $_SESSION['user_id']) {
                $this->addErrorMessage('Nemáte oprávnění upravovat cizí záznam.');
                header('Location: ' . BASE_URL . '/index.php');
                exit;
            }

            $uploadedImages = $this->processImageUploads();
            if (empty($uploadedImages)) {
                $uploadedImages = json_decode($existingBook['images'] ?? '[]', true) ?: [];
            }

            $isUpdated = $bookModel->update(
                $id, $title, $author, $category, $subcategory, 
                $year, $price, $isbn, $description, $link, 
                $uploadedImages, $_SESSION['user_id']
            );

            if ($isUpdated) {
                $this->addSuccessMessage('Kniha byla úspěšně upravena.');
            } else {
                $this->addErrorMessage('Nastala chyba při ukládání.');
            }
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }
    }

    public function delete($id = null) {
        $this->requireAuth();
        if (!$id) {
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }
        require_once '../app/models/Database.php';
        require_once '../app/models/Book.php';
        $db = (new Database())->getConnection();
        $bookModel = new Book($db);
        $book = $bookModel->getById($id);
        if (!$book || $book['created_by'] != $_SESSION['user_id']) {
            $this->addErrorMessage('Knihu se nepodařilo smazat. Nemáte k tomu oprávnění.');
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }
        if ($bookModel->delete($id)) {
            $this->addSuccessMessage('Kniha byla trvale smazána.');
        } else {
            $this->addErrorMessage('Nastala chyba. Knihu se nepodařilo smazat.');
        }
        header('Location: ' . BASE_URL . '/index.php');
        exit;
    }

    protected function processImageUploads() {
        $uploadedFiles = [];
        $uploadDir = __DIR__ . '/../../public/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
            $fileCount = count($_FILES['images']['name']);
            for ($i = 0; $i < $fileCount; $i++) {
                if ($_FILES['images']['error'][$i] === UPLOAD_ERR_OK) {
                    $tmpName = $_FILES['images']['tmp_name'][$i];
                    $fileExtension = strtolower(pathinfo($_FILES['images']['name'][$i], PATHINFO_EXTENSION));
                    if (!in_array($fileExtension, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) continue;
                    $newName = 'book_' . uniqid() . '_' . substr(md5(mt_rand()), 0, 4) . '.' . $fileExtension;
                    if (move_uploaded_file($tmpName, $uploadDir . $newName)) {
                        $uploadedFiles[] = $newName; 
                    }
                }
            }
        }
        return $uploadedFiles;
    }

    protected function addSuccessMessage($message) { $_SESSION['messages']['success'][] = $message; }
    protected function addNoticeMessage($message) { $_SESSION['messages']['notice'][] = $message; }
    protected function addErrorMessage($message) { $_SESSION['messages']['error'][] = $message; }
}