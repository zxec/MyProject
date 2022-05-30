<?php

require_once('helpers.php');
require_once('linkDatabase.php');
require_once('init.php');
require_once('queriesDatabase.php');

$errors = [];

if (isset($_POST['auth'])) {
    $email = checkInput($_POST['email']);
    $password = checkInput($_POST['password']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'E-mail введён некорректно';
    } else {
        $errors['password'] = 'Неверный пароль';
        $findEmail = array_search($email, array_column($users, 'user_email'));
        if ($findEmail !== false && password_verify($password, $users[$findEmail]['user_password'])) {
            unset($error);

            session_start();
            $_SESSION['user_id'] = $users[$findEmail]['id'];

            header('Location: index.php');
        }
    }
}

$mysqli->close();

$content = include_template('auth.php', array('errors' => $errors));

print(include_template('layout.php', array(
    'content' => $content,
    'title' => $title
)));
