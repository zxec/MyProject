<?php

require_once('helpers.php');
require_once('linkDatabase.php');
require_once('init.php');
require_once('session.php');
require_once('queriesDatabase.php');

$errors = [];

if (isset($_POST['addTask'])) {
    $taskName = checkInput($_POST['name']);
    $projectId = checkInput($_POST['project'] ?? '');
    $date = checkInput($_POST['date']);

    $file = NULL;
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $file = $uploadFileDir . checkInput($_FILES['file']['name']);
        move_uploaded_file($_FILES['file']['tmp_name'], $file);
    }

    if (empty($taskName)) {
        $errors['name'] = 'Заполните поле';
    }
    if (array_search($projectId, array_column($projects, 'id')) === false) {
        $errors['project'] = 'Такого проекта не существует';
    }
    if (!empty($date) && (!is_date_valid($date) || strtotime($date) < strtotime(date('Y/m/d')))) {
        $errors['date'] = 'Некорректно введена дата';
    }
    if (empty($errors)) {
        dbGetResult($mysqli, 'INSERT INTO `tasks`(`user_id`, `project_id`, `task_name`, `date`, `file`) VALUES (?, ?, ?, ?, ?)', array($id, $projectId, $taskName, $date, $file));
        header('Location: index.php?id=' . $projectId);
    }
}

$mysqli->close();

$content = include_template('add.php',  array(
    'projects' => preparingDataOutput($projects),
    'errors' => $errors
));

print(include_template('layout.php', array(
    'projects' => preparingDataOutput($projects),
    'projectsId' => preparingDataOutput($projectsId),
    'userName' => preparingDataOutput($name),
    'userData' => preparingDataOutput(array([$id])),
    'content' => $content,
    'title' => $title
)));
