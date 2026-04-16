<?php

class BookController {

    // --- POMOCNÁ METODA PRO OVĚŘENÍ PŘIHLÁŠENÍ ---
    protected function requireAuth() {
        if (!isset($_SESSION['user_id'])) {
            $this->addErrorMessage('Pro tuto akci se musíte přihlásit.');
            header('Location: ' . BASE_URL . '/index.php?url=auth/login');
            exit;
        }
    }

    // 0. Výchozí metoda pro zobrazení úvodní stránky
    public function index() {
        require_once '../app/models/Database.php';
        require_once '../app/models/Book.php';

        $database = new Database();
        $db = $database->getConnection();

        $bookModel = new Book($db);
        $books = $bookModel->getAll(); 
        
        require_once '../app/views/books/books_list.php';
    }

    // Zobrazení formuláře pro přidání
    public function create() {
        // 1. ZABEZPEČENÍ: Musí být přihlášen!
        $this->requireAuth();
        
        require_once '../app/views/books/book_create.php';
    }

    // Zobrazení detailu jedné knihy
    public function show($id = null) {
        if (!$id) {
            $this->addErrorMessage('Nebylo zadáno ID knihy.');
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        require_once '../app/models/Database.php';
        require_once '../app/models/Book.php';

        $database = new Database();
        $db = $database->getConnection();

        $bookModel = new Book($db);
        $book = $bookModel->getById($id);

        if (!$book) {
            $this->addErrorMessage('Hledaná kniha v databázi neexistuje.');
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        require_once '../app/views/books/book_show.php';
    }

    // 2. Zpracování dat odeslaných z formuláře (PŘIDÁNÍ)
    public function store() {
        // 1. ZABEZPEČENÍ: Musí být přihlášen!
        $this->requireAuth();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $title = htmlspecialchars($_POST['title'] ?? '');
            $author = htmlspecialchars($_POST['author'] ?? '');
            $isbn = htmlspecialchars($_POST['isbn'] ?? '');
            $category = htmlspecialchars($_POST['category'] ?? '');
            $subcategory = htmlspecialchars($_POST['subcategory'] ?? '');
            
            $year = (int)($_POST['year'] ?? 0);
            $price = (float)($_POST['price'] ?? 0);
            
            $link = htmlspecialchars($_POST['link'] ?? '');
            $description = htmlspecialchars($_POST['description'] ?? '');

            $uploadedImages = $this->processImageUploads(); 

            require_once '../app/models/Database.php';
            require_once '../app/models/Book.php';

            $database = new Database();
            $db = $database->getConnection();

            // PŘIDÁNO: Uložení ID přihlášeného uživatele do pole dat
            $bookData = [
                'title' => $title,
                'author' => $author,
                'category' => $category,
                'subcategory' => $subcategory,
                'year' => $year,
                'price' => $price,
                'isbn' => $isbn,
                'description' => $description,
                'link' => $link,
                'images' => $uploadedImages,
                'created_by' => $_SESSION['user_id'] // ID aktuálně přihlášeného uživatele
            ];

            $bookModel = new Book($db);
            $isSaved = $bookModel->create($bookData);

            if ($isSaved) {
                $this->addSuccessMessage('Kniha byla úspěšně uložena do databáze.');
                header('Location: ' . BASE_URL . '/index.php');
                exit;
            } else {
                $this->addErrorMessage('Nastala chyba. Nepodařilo se uložit knihu do databáze.');
            }
            
        } else {
            $this->addNoticeMessage('Pro přidání knihy je nutné odeslat formulář.');
        }
    }

    // 3. Smazání existující knihy
    public function delete($id = null) {
        // 1. ZABEZPEČENÍ: Musí být přihlášen!
        $this->requireAuth();

        if (!$id) {
            $this->addErrorMessage('Nebylo zadáno ID knihy ke smazání.');
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        require_once '../app/models/Database.php';
        require_once '../app/models/Book.php';

        $database = new Database();
        $db = $database->getConnection();
        $bookModel = new Book($db);

        // Nejprve musíme knihu najít, abychom zkontrolovali, komu patří
        $book = $bookModel->getById($id);

        // AUTORIZACE: Zkontrolujeme, zda přihlášený uživatel je autorem záznamu
        if (!$book || $book['created_by'] != $_SESSION['user_id']) {
            $this->addErrorMessage('Knihu se nepodařilo smazat. Nemáte k tomu oprávnění (nejste autorem).');
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        $isDeleted = $bookModel->delete($id);

        if ($isDeleted) {
            $this->addSuccessMessage('Kniha byla trvale smazána z databáze.');
        } else {
            $this->addErrorMessage('Nastala chyba. Knihu se nepodařilo smazat.');
        }

        header('Location: ' . BASE_URL . '/index.php');
        exit;
    }

    // 4. Zobrazení formuláře pro úpravu existující knihy
    public function edit($id = null) {
        // 1. ZABEZPEČENÍ: Musí být přihlášen!
        $this->requireAuth();

        if (!$id) {
            $this->addErrorMessage('Nebylo zadáno ID knihy k úpravě.');
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        require_once '../app/models/Database.php';
        require_once '../app/models/Book.php';

        $database = new Database();
        $db = $database->getConnection();

        $bookModel = new Book($db);
        $book = $bookModel->getById($id);

        if (!$book) {
            $this->addErrorMessage('Požadovaná kniha nebyla v databázi nalezena.');
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        // AUTORIZACE: Zkontrolujeme, zda přihlášený uživatel je autorem záznamu
        if ($book['created_by'] != $_SESSION['user_id']) {
            $this->addErrorMessage('Nemáte oprávnění upravovat cizí záznam.');
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        require_once '../app/views/books/book_edit.php';
    }

    // 5. Zpracování dat odeslaných z editačního formuláře (ÚPRAVA)
    public function update($id = null) {
        // 1. ZABEZPEČENÍ: Musí být přihlášen!
        $this->requireAuth();

        if (!$id) {
            $this->addErrorMessage('Nebylo zadáno ID knihy k aktualizaci.');
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $title = htmlspecialchars($_POST['title'] ?? '');
            $author = htmlspecialchars($_POST['author'] ?? '');
            $isbn = htmlspecialchars($_POST['isbn'] ?? '');
            $category = htmlspecialchars($_POST['category'] ?? '');
            $subcategory = htmlspecialchars($_POST['subcategory'] ?? '');
            
            $year = (int)($_POST['year'] ?? 0);
            $price = (float)($_POST['price'] ?? 0);
            
            $link = htmlspecialchars($_POST['link'] ?? '');
            $description = htmlspecialchars($_POST['description'] ?? '');

            require_once '../app/models/Database.php';
            require_once '../app/models/Book.php';

            $database = new Database();
            $db = $database->getConnection();
            $bookModel = new Book($db);

            // Zjistíme si stávající záznam z databáze
            $existingBook = $bookModel->getById($id);

            // AUTORIZACE: Zkontrolujeme, zda přihlášený uživatel je autorem záznamu
            if (!$existingBook || $existingBook['created_by'] != $_SESSION['user_id']) {
                $this->addErrorMessage('Nemáte oprávnění upravovat cizí záznam.');
                header('Location: ' . BASE_URL . '/index.php');
                exit;
            }

            // Stávající obrázky
            $oldImages = json_decode($existingBook['images'] ?? '[]', true) ?: [];

            // Pokusíme se nahrát nové obrázky
            $uploadedImages = $this->processImageUploads();

            // Pokud uživatel nenahrál nic nového, ponecháme staré obrázky
            if (empty($uploadedImages)) {
                $uploadedImages = $oldImages;
            }

            $isUpdated = $bookModel->update(
                $id, $title, $author, $category, $subcategory, 
                $year, $price, $isbn, $description, $link, $uploadedImages
            );

            if ($isUpdated) {
                $this->addSuccessMessage('Kniha byla úspěšně upravena.');
            } else {
                $this->addErrorMessage('Nastala chyba. Změny se nepodařilo uložit.');
            }
            
            header('Location: ' . BASE_URL . '/index.php');
            exit;
            
        } else {
            $this->addNoticeMessage('Pro úpravu knihy je nutné odeslat formulář.');
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        // ... předchozí kód (nahrávání obrázků atd.) ...

            $isUpdated = $bookModel->update(
                $id, $title, $author, $category, $subcategory, 
                $year, $price, $isbn, $description, $link, $uploadedImages,
                $_SESSION['user_id'] // PŘIDÁNO: Předáme ID uživatele, který knihu upravil
            );

            // ... zbytek kódu ...
    }

    // --- Pomocná metoda pro zpracování nahrávání obrázků ---
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
                    $originalName = basename($_FILES['images']['name'][$i]);
                    $fileExtension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

                    $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
                    if (!in_array($fileExtension, $allowedExtensions)) {
                        continue; 
                    }

                    $newName = 'book_' . uniqid() . '_' . substr(md5(mt_rand()), 0, 4) . '.' . $fileExtension;
                    $targetFilePath = $uploadDir . $newName;

                    if (move_uploaded_file($tmpName, $targetFilePath)) {
                        $uploadedFiles[] = $newName; 
                    }
                }
            }
        }
        return $uploadedFiles;
    }

    // --- Pomocné metody pro systém notifikací ---
    protected function addSuccessMessage($message) {
        $_SESSION['messages']['success'][] = $message;
    }

    protected function addNoticeMessage($message) {
        $_SESSION['messages']['notice'][] = $message;
    }

    protected function addErrorMessage($message) {
        $_SESSION['messages']['error'][] = $message;
    }
}