<?php

require_once('helpers.php');
require_once('linkDatabase.php');
require_once('init.php');
require_once('session.php');
require_once('queriesDatabase.php');

$errors = [];

if (isset($_POST['add_project'])) {
    $projectName = checkInput($_POST['name']);

    $searchArray = array_map('mb_strtolower', array_column($projects, 'project_name'));

    $errors['name'] = 'Такой проект уже существует';
    if (empty($projectName)) {
        $errors['name'] = 'Заполните поле';
    } elseif (!in_array(mb_strtolower($projectName), $searchArray)) {
        dbGetResult($mysqli, 'INSERT INTO `projects`(`user_id`, `project_name`) VALUES (?, ?)', array($id, $projectName));
        $projectId = preparingDataOutput(dbGetResult($mysqli, 'SELECT `id` FROM `projects` WHERE `id` = LAST_INSERT_ID()'));

        unset($errors);

        header('Location: index.php?id=' . $projectId[0]['id']);
    }
}

$mysqli->close();

$content = include_template('add_project.php',  array('errors' => $errors));

print(include_template('layout.php', array(
    'projects' => preparingDataOutput($projects),
    'projectsId' => preparingDataOutput($projectsId),
    'userName' => preparingDataOutput($name),
    'userData' => preparingDataOutput(array([$id])),
    'content' => $content,
    'title' => $title
)));
