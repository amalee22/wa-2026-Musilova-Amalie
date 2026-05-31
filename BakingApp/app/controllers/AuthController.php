<?php

class AuthController {

    public function index() {
        header('Location: ' . BASE_URL . '/index.php?url=auth/login');
        exit;
    }

    public function register() { require_once '../app/views/auth/register.php'; }

    public function storeUser() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $passwordConfirm = $_POST['password_confirm'] ?? '';
            
            $firstName = trim($_POST['first_name'] ?? '');
            $lastName = trim($_POST['last_name'] ?? '');
            $nickname = trim($_POST['nickname'] ?? '');
            
            if (empty($username) || empty($email) || empty($password)) { 
                $this->addErrorMessage('Vyplňte povinná pole.'); 
                header('Location: ' . BASE_URL . '/index.php?url=auth/register'); 
                exit; 
            }
            if ($password !== $passwordConfirm) { 
                $this->addErrorMessage('Hesla se neshodují.'); 
                header('Location: ' . BASE_URL . '/index.php?url=auth/register'); 
                exit; 
            }
            
            if (strlen($password) < 8 || !preg_match("/[0-9]/", $password) || !preg_match("/[A-Z]/", $password)) {
                $this->addErrorMessage('Heslo musí mít alespoň 8 znaků, obsahovat číslo a velké písmeno.');
                header('Location: ' . BASE_URL . '/index.php?url=auth/register');
                exit;
            }

            require_once '../app/models/Database.php'; require_once '../app/models/User.php';
            $db = (new Database())->getConnection(); $userModel = new User($db);
            
            if ($userModel->register($username, $email, $password, $firstName, $lastName, $nickname)) {
                $this->addSuccessMessage('Registrace úspěšná.'); header('Location: ' . BASE_URL . '/index.php?url=auth/login'); exit;
            } else { 
                $this->addErrorMessage('Email je již obsazen.'); header('Location: ' . BASE_URL . '/index.php?url=auth/register'); exit; 
            }
        }
    }

    public function updateProfile() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
            // OPRAVENO: Validace CSRF tokenu při úpravě profilu
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                $this->addErrorMessage('Neplatný požadavek (CSRF ochrana).');
                header('Location: ' . BASE_URL . '/index.php?url=auth/profile'); exit;
            }

            require_once '../app/models/Database.php'; require_once '../app/models/User.php';
            $db = (new Database())->getConnection();
            
            $data = [
                'nickname' => trim($_POST['nickname'] ?? ''),
                'first_name' => trim($_POST['first_name'] ?? ''),
                'last_name' => trim($_POST['last_name'] ?? ''),
                'bio' => trim($_POST['bio'] ?? '')
            ];
            
            if ((new User($db))->updateProfile($_SESSION['user_id'], $data)) {
                $_SESSION['user_name'] = !empty($data['nickname']) ? $data['nickname'] : $_SESSION['user_name'];
                $this->addSuccessMessage('Profil byl aktualizován.');
            } else { 
                $this->addErrorMessage('Chyba při ukládání.'); 
            }
            header('Location: ' . BASE_URL . '/index.php?url=auth/profile'); exit;
        }
    }

    public function login() { require_once '../app/views/auth/login.php'; }

    public function authenticate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once '../app/models/Database.php'; require_once '../app/models/User.php';
            $db = (new Database())->getConnection(); $userModel = new User($db);
            $user = $userModel->findByEmail($_POST['email']);
            
           if ($user && password_verify($_POST['password'], $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = !empty($user['nickname']) ? $user['nickname'] : $user['username'];
    
    // OPRAVA: Kontrolujeme sloupec 'role'
    $_SESSION['is_admin'] = (isset($user['role']) && $user['role'] === 'admin') ? 1 : 0;

                $this->addSuccessMessage('Vítejte zpět!'); header('Location: ' . BASE_URL . '/index.php'); exit;
            } else { 
                $this->addErrorMessage('Chybné údaje.'); header('Location: ' . BASE_URL . '/index.php?url=auth/login'); exit; 
            }
        }
    }

    public function profile() {
        if (!isset($_SESSION['user_id'])) { header('Location: ' . BASE_URL . '/index.php?url=auth/login'); exit; }
        require_once '../app/models/Database.php'; require_once '../app/models/User.php'; require_once '../app/models/Recipe.php';
        $db = (new Database())->getConnection();
        $user = (new User($db))->findById($_SESSION['user_id']);
        $recipeModel = new Recipe($db);
        $myRecipes = $recipeModel->getByUserId($_SESSION['user_id']);
        $likedRecipes = $recipeModel->getLikedByUserId($_SESSION['user_id']);
        require_once '../app/views/auth/profile.php';
    }

    public function logout() { 
        unset($_SESSION['user_id']); 
        unset($_SESSION['user_name']); 
        unset($_SESSION['is_admin']); 
        header('Location: ' . BASE_URL . '/index.php'); 
        exit; 
    }
    
    protected function addSuccessMessage($message) { $_SESSION['messages']['success'][] = $message; }
    protected function addErrorMessage($message) { $_SESSION['messages']['error'][] = $message; }
}