<?php 
class BookController {

    

   public function index() {
        require_once '../app/models/Book.php';
        $bookModel = new Book();
        $books = $bookModel->getAll();
        
        require_once '../app/views/books/books_list.php';
    }

    // Metoda pro uložení dat
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            require_once '../app/models/Book.php';
            $bookModel = new Book();

            // Příprava dat z $_POST
            $data = [
                'title' => $_POST['title'],
                'author' => $_POST['author'],
                'isbn' => $_POST['isbn'],
                'category' => $_POST['category'] ?? null,
                'subcategory' => $_POST['subcategory'] ?? null,
                'year' => $_POST['year'],
                'price' => $_POST['price'] ?? 0,
                'link' => $_POST['link'] ?? null,
                'description' => $_POST['description'] ?? null
            ];

            if ($bookModel->create($data)) {
                // Po úspěšném uložení přesměrujeme na seznam
                header('Location: index.php?url=book/index');
            } else {
                echo "Chyba při ukládání knihy.";
            }
        }
    }
}