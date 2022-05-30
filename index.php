<?php

require_once('helpers.php');
require_once('linkDatabase.php');
require_once('init.php');
require_once('session.php');
require_once('queriesDatabase.php');

$error = '';
$query = '';
$tasks = [];
$data[] = $id;

if (isset($_GET['id'])) {
    $projectId = checkInput($_GET['id']);

    if (array_search($projectId, array_column($projects, 'id')) === false && isset($id)) {
        $error = '404 Такого проекта не существует!';
        http_response_code(404);
    } else {
        $query .= ' AND `project_id` = (?)';
        $data[] = $projectId;
    }
}

if (isset($_GET['menu'])) {
    $menu = checkInput($_GET['menu']);

    if ($menu === 'agenda') {
        $query .= ' AND `date` = CURRENT_DATE()';
    }
    if ($menu === 'tomorrow') {
        $query .= ' AND `date` = CURRENT_DATE() + INTERVAL 1 DAY';
    }
    if ($menu === 'overdue') {
        $query .= ' AND `date` < CURRENT_DATE()';
    }
}

if (isset($_GET['searched'])) {
    $searched = checkInput($_GET['searched']);

    if (!empty($searched)) {
        $query .= ' AND MATCH(`task_name`) AGAINST ((?))';
        $data[] = $searched;
    }
}

if (empty($error)) {
    $tasks = dbGetResult($mysqli, 'SELECT `id`, `task_name`, `date`, `completed`, `project_id`, `file` FROM `tasks` WHERE `user_id` = (?)' . $query, $data);
}
if (isset($_GET['searched']) && empty($tasks) && empty($error)) {
    $error = 'Ничего не найдено по вашему запросу';
}

if (isset($_POST['task_completed'])) {
    $completed = checkInput($_POST['task_completed']);

    dbGetResult($mysqli, 'UPDATE `tasks` SET `completed` = NOT completed WHERE `user_id` = (?) AND `id` = (?)', array($id, $completed));
}

$mysqli->close();

$content = include_template('main.php', array(
    'tasks' => preparingDataOutput($tasks),
    'error' => $error
));

print(include_template('layout.php', array(
    'projects' => preparingDataOutput($projects),
    'projectsId' => preparingDataOutput($projectsId),
    'userName' => preparingDataOutput($name),
    'userData' => preparingDataOutput(array([$id])),
    'content' => $content,
    'title' => $title
)));
