<?php

class UserController {

    public function index() {
        $query = $_GET['q'] ?? '';
        require_once '../app/models/Database.php';
        require_once '../app/models/User.php';
        $db = (new Database())->getConnection();
        $userModel = new User($db);
        
        $users = $query ? $userModel->searchUsers($query) : $userModel->getAllUsers();
        require_once '../app/views/user/user_list.php';
    }

   public function show($id = null) {
    if (!$id) {
        header('Location: ' . BASE_URL . '/index.php?url=user/index');
        exit;
    }

    require_once '../app/models/Database.php';
    require_once '../app/models/User.php';
    require_once '../app/models/Recipe.php'; // Přidáno načtení modelu Recipe

    $db = (new Database())->getConnection();
    $userModel = new User($db);
    $recipeModel = new Recipe($db); // Inicializace modelu
    
    $user = $userModel->findById($id);

    if (!$user) {
        $_SESSION['messages']['error'][] = 'Hledaný pekař neexistuje.';
        header('Location: ' . BASE_URL . '/index.php?url=user/index');
        exit;
    }

    // NOVÉ: Načtení receptů konkrétního uživatele
    // Předpokládám, že máš v Recipe modelu metodu jako getByUserId nebo podobnou
    $userRecipes = $recipeModel->getByUserId($id); 

    // Vykreslení pohledu (view) a předání dat
    require_once '../app/views/user/user_show.php';
}

    // OPRAVENO: Mazání uživatelů nyní vyžaduje POST a CSRF token
    public function delete($id = null) {
        if (!isset($_SESSION['user_id'])) { 
            header('Location: ' . BASE_URL . '/index.php?url=auth/login'); exit; 
        }
        
        $isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;
        if (!$isAdmin) { 
            $this->addErrorMessage('Nemáte oprávnění administrátora k této akci.');
            header('Location: ' . BASE_URL . '/index.php'); exit; 
        }

        // Zabezpečení proti CSRF a vynucení POST požadavku
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $this->addErrorMessage('Neplatný požadavek (CSRF ochrana).');
            header('Location: ' . BASE_URL . '/index.php?url=user/index'); exit;
        }

        if (!$id) { header('Location: ' . BASE_URL . '/index.php?url=user/index'); exit; }
        
        require_once '../app/models/Database.php'; 
        require_once '../app/models/User.php';
        
        $db = (new Database())->getConnection(); 
        $userModel = new User($db);
        
        if ($id == $_SESSION['user_id']) {
            $this->addErrorMessage('Nemůžete smazat svůj vlastní administrátorský účet.');
        } else {
            if ($userModel->delete((int)$id)) {
                $this->addSuccessMessage('Uživatel a všechny jeho recepty byly trvale smazány.');
            } else {
                $this->addErrorMessage('Nastala chyba při mazání uživatele.');
            }
        }
        header('Location: ' . BASE_URL . '/index.php?url=user/index'); exit;
    }

    protected function addSuccessMessage($message) { $_SESSION['messages']['success'][] = $message; }
    protected function addErrorMessage($message) { $_SESSION['messages']['error'][] = $message; }
}