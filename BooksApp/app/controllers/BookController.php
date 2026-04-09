<?php

class BookController {

        // 0. Výchozí metoda pro zobrazení úvodní stránky včetně seznamu knih
    public function index() {
        // Načtení potřebných tříd
        require_once '../app/models/Database.php';
        require_once '../app/models/Book.php';

        // Vytvoření připojení k databázi
        $database = new Database();
        $db = $database->getConnection();

        // Inicializace modelu a získání dat
        $bookModel = new Book($db);
        $books = $bookModel->getAll(); // Proměnná $books nyní obsahuje pole všech knih
        
        // Načte se (vloží) připravený soubor s HTML strukturou
        require_once '../app/views/books/books_list.php';
    }

             public function create() {
        // Vráceno zpět na knihy
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

        // Načtení šablony pro zobrazení detailu
        require_once '../app/views/books/book_show.php';
    }






    // 2. Zpracování dat odeslaných z formuláře
    public function store() {
        // Kontrola, zda byl formulář odeslán metodou POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            // 1. Získání a očištění textových dat (ochrana proti XSS)
            $title = htmlspecialchars($_POST['title'] ?? '');
            $author = htmlspecialchars($_POST['author'] ?? '');
            $isbn = htmlspecialchars($_POST['isbn'] ?? '');
            $category = htmlspecialchars($_POST['category'] ?? '');
            $subcategory = htmlspecialchars($_POST['subcategory'] ?? '');
            
            // U číselných hodnot se provádí explicitní přetypování
            $year = (int)($_POST['year'] ?? 0);
            $price = (float)($_POST['price'] ?? 0);
            
            $link = htmlspecialchars($_POST['link'] ?? '');
            $description = htmlspecialchars($_POST['description'] ?? '');

            // Prozatímní zástupce pro obrázky (bude řešeno v budoucnu)
            $uploadedImages = []; 

            // 2. Komunikace s databází a modelem
            require_once '../app/models/Database.php';
            require_once '../app/models/Book.php';

            // Vytvoření připojení k DB
            $database = new Database();
            $db = $database->getConnection();

            // Vytvoření objektu knihy a volání metody pro uložení
            $bookModel = new Book($db);
            // Zabalíme data do jednoho pole, jak to očekává model
            $bookData = [
                'title' => $title,
                'author' => $author,
                'category' => $category,
                'subcategory' => $subcategory,
                'year' => $year,
                'price' => $price,
                'isbn' => $isbn,
                'description' => $description,
                'link' => $link
            ];

            // Vytvoření objektu knihy a volání metody pro uložení
            $bookModel = new Book($db);
            $isSaved = $bookModel->create($bookData);

            // 3. Vyhodnocení výsledku a přesměrování
            if ($isSaved) {
                // Vyvolání zelené notifikace pro úspěšnou akci
                $this->addSuccessMessage('Kniha byla úspěšně uložena do databáze.');
                
                // Přesměrování zpět na hlavní stránku s využitím dynamické BASE_URL
                header('Location: ' . BASE_URL . '/index.php');
                exit;
            } else {
                // Vyvolání červené notifikace pro kritické selhání
                $this->addErrorMessage('Nastala chyba. Nepodařilo se uložit knihu do databáze.');
            }
            
        } else {
            // Pokud je stránka navštívena napřímo bez odeslání dat, zobrazí se žlutá informativní zpráva
            $this->addNoticeMessage('Pro přidání knihy je nutné odeslat formulář.');
        }
    }


// 3. Smazání existující knihy
    public function delete($id = null) {
        // Kontrola, zda bylo v URL předáno ID
        if (!$id) {
            $this->addErrorMessage('Nebylo zadáno ID knihy ke smazání.');
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        // Načtení potřebných tříd a spojení s databází
        require_once '../app/models/Database.php';
        require_once '../app/models/Book.php';

        $database = new Database();
        $db = $database->getConnection();

        // Inicializace modelu a zavolání metody pro smazání
        $bookModel = new Book($db);
        $isDeleted = $bookModel->delete($id);

        // Vyhodnocení výsledku
        if ($isDeleted) {
            $this->addSuccessMessage('Kniha byla trvale smazána z databáze.');
        } else {
            $this->addErrorMessage('Nastala chyba. Knihu se nepodařilo smazat.');
        }

        // Přesměrování zpět na seznam knih
        header('Location: ' . BASE_URL . '/index.php');
        exit;
    }


// 4. Zobrazení formuláře pro úpravu existující knihy
    public function edit($id = null) {
        // Kontrola, zda bylo v URL předáno ID
        if (!$id) {
            $this->addErrorMessage('Nebylo zadáno ID knihy k úpravě.');
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        // Načtení potřebných tříd a spojení s databází
        require_once '../app/models/Database.php';
        require_once '../app/models/Book.php';

        $database = new Database();
        $db = $database->getConnection();

        // Získání dat o konkrétní knize
        $bookModel = new Book($db);
        $book = $bookModel->getById($id);

        // Pokud kniha neexistuje (někdo si vymyslel ID v URL)
        if (!$book) {
            $this->addErrorMessage('Požadovaná kniha nebyla v databázi nalezena.');
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        // Načtení HTML formuláře pro úpravy
        require_once '../app/views/books/book_edit.php';
    }




    // 5. Zpracování dat odeslaných z editačního formuláře
    public function update($id = null) {
        // Kontrola, jestli máme ID knihy, kterou chceme upravit
        if (!$id) {
            $this->addErrorMessage('Nebylo zadáno ID knihy k aktualizaci.');
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            // 1. Očištění dat z formuláře
            $title = htmlspecialchars($_POST['title'] ?? '');
            $author = htmlspecialchars($_POST['author'] ?? '');
            $isbn = htmlspecialchars($_POST['isbn'] ?? '');
            $category = htmlspecialchars($_POST['category'] ?? '');
            $subcategory = htmlspecialchars($_POST['subcategory'] ?? '');
            
            $year = (int)($_POST['year'] ?? 0);
            $price = (float)($_POST['price'] ?? 0);
            
            $link = htmlspecialchars($_POST['link'] ?? '');
            $description = htmlspecialchars($_POST['description'] ?? '');

            $uploadedImages = []; // Prozatím ignorujeme obrázky

            // 2. Připojení k databázi
            require_once '../app/models/Database.php';
            require_once '../app/models/Book.php';

            $database = new Database();
            $db = $database->getConnection();

            // 3. Zavolání modelu pro přepsání dat (UPDATE)
            $bookModel = new Book($db);
            $isUpdated = $bookModel->update(
                $id, $title, $author, $category, $subcategory, 
                $year, $price, $isbn, $description, $link, $uploadedImages
            );

            // 4. Přesměrování podle úspěchu
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
    }


// --- Pomocné metody pro systém notifikací ---

    protected function addSuccessMessage($message) {
        // Zelená zpráva o úspěchu
        $_SESSION['messages']['success'][] = $message;
    }

    protected function addNoticeMessage($message) {
        // Žlutá informativní zpráva
        $_SESSION['messages']['notice'][] = $message;
    }

    protected function addErrorMessage($message) {
        // Červená chybová zpráva
        $_SESSION['messages']['error'][] = $message;
    }
}

