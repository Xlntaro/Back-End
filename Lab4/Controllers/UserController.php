<?php
namespace Controllers;

use Models\UserModel;
use Views\UserView;

class UserController {
    private $model;
    private $view;

    public function __construct() {
        $this->model = new UserModel();
        $this->view = new UserView();
    }

    public function handleUserRegistration($name, $email) {
        // Логіка реєстрації користувача
        $result = $this->model->createUser($name, $email);
        
        if ($this->model->validateUser()) {
            return $this->view->renderSuccess($result);
        } else {
            return $this->view->renderError("Invalid user data");
        }
    }

    public function getUserProfile() {
        $userInfo = $this->model->getUserInfo();
        return $this->view->renderUserProfile($userInfo);
    }

    public function listUsers() {
        // Imitation of user listing
        $users = [
            ['name' => 'John Doe', 'email' => 'john@example.com'],
            ['name' => 'Jane Smith', 'email' => 'jane@example.com']
        ];
        
        return $this->view->renderUserList($users);
    }
}