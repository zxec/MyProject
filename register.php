<?php

require_once('helpers.php');
require_once('linkDatabase.php');
require_once('init.php');
require_once('queriesDatabase.php');

$errors = [];
if (isset($_POST['register'])) {
    $registerName = checkInput($_POST['name']);
    $email = checkInput($_POST['email']);
    $password = checkInput($_POST['password']);

    if (empty($registerName)) {
        $errors['name'] = 'Заполните поле';
    }
    if (empty($password)) {
        $errors['password'] = 'Заполните поле';
    }
    if (empty($email) || filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        $errors['email'] = 'E-mail введён некорректно';
    } elseif (array_search($email, array_column($users, 'user_email')) !== false) {
        $errors['email'] = 'E-mail уже зарегистрирован';
    }
    if (empty($errors)) {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        dbGetResult($mysqli, 'INSERT INTO `users`(`user_name`, `user_password`, `user_email`) VALUES (?, ?, ?)', array($registerName, $passwordHash, $email));
        header('Location: auth.php');
    }
}

$mysqli->close();

$content = include_template('register.php',  array('errors' => $errors));

print(include_template('layout.php', array(
    'content' => $content,
    'title' => $title
)));
