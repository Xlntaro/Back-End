<?php
namespace Views;

class UserView {
    public function renderSuccess($message) {
        return $this->createHtmlResponse(
            'Success', 
            "<div class='success'>$message</div>"
        );
    }

    public function renderError($message) {
        return $this->createHtmlResponse(
            'Error', 
            "<div class='error'>$message</div>"
        );
    }

    public function renderUserProfile($userInfo) {
        $name = htmlspecialchars($userInfo['name']);
        $email = htmlspecialchars($userInfo['email']);
        
        return $this->createHtmlResponse(
            'User Profile',
            "<div class='profile'>
                <h2>User Profile</h2>
                <p>Name: $name</p>
                <p>Email: $email</p>
            </div>"
        );
    }

    public function renderUserList($users) {
        $userList = '';
        foreach ($users as $user) {
            $name = htmlspecialchars($user['name']);
            $email = htmlspecialchars($user['email']);
            $userList .= "<li>$name ($email)</li>";
        }

        return $this->createHtmlResponse(
            'User List',
            "<div class='user-list'>
                <h2>Users</h2>
                <ul>$userList</ul>
            </div>"
        );
    }

    private function createHtmlResponse($title, $content) {
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <title>$title</title>
            <style>
                body { font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; }
                .success { color: green; border: 1px solid green; padding: 10px; }
                .error { color: red; border: 1px solid red; padding: 10px; }
                .profile, .user-list { border: 1px solid #ddd; padding: 15px; }
            </style>
        </head>
        <body>
            $content
        </body>
        </html>";
    }
}