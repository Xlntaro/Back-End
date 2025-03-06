<?php
namespace Models;

class UserModel {
    private $name;
    private $email;

    public function __construct($name = '', $email = '') {
        $this->name = $name;
        $this->email = $email;
    }

    public function getUserInfo() {
        return [
            'name' => $this->name,
            'email' => $this->email
        ];
    }

    public function createUser($name, $email) {
        $this->name = $name;
        $this->email = $email;
        return "User created: $name ($email)";
    }

    public function validateUser() {
        // Простая валидация
        return !empty($this->name) && 
               !empty($this->email) && 
               filter_var($this->email, FILTER_VALIDATE_EMAIL);
    }
}