<?php

class UserController {

    public function index() {
        $query = $_GET['q'] ?? '';
        require_once '../app/models/Database.php';
        require_once '../app/models/User.php';
        $db = (new Database())->getConnection();
        $userModel = new User($db);
        
        $users = $query ? $userModel->searchUsers($query) : $userModel->getAllUsers();
        require_once '../app/views/users/user_list.php';
    }

    public function show($id = null) {
        if (!$id) { header('Location: ' . BASE_URL . '/index.php'); exit; }
        require_once '../app/models/Database.php';
        require_once '../app/models/User.php';
        require_once '../app/models/Recipe.php';
        
        $db = (new Database())->getConnection();
        $user = (new User($db))->findById($id);
        
        if (!$user) { header('Location: ' . BASE_URL . '/index.php'); exit; }
        
        $recipeModel = new Recipe($db);
        $userRecipes = $recipeModel->getByUserId($id);
        
        require_once '../app/views/users/user_show.php';
    }
}