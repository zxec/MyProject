<?php

require_once('linkDatabase.php');

$users = dbGetResult($mysqli, 'SELECT `id`, `user_email`, `user_password` FROM `users`');
$projects = [];
$projectsId = [];
$tasks = [];
$name = [];

if (isset($id)) {
    $projects = dbGetResult($mysqli, 'SELECT `id`, `project_name` FROM `projects` WHERE `user_id` = (?)', array($id));
    $projectsId = dbGetResult($mysqli, 'SELECT `project_id` FROM `tasks` WHERE `user_id` = (?)', array($id));
    $tasks = dbGetResult($mysqli, 'SELECT `id`, `task_name`, `date`, `completed`, `project_id`, `file` FROM `tasks` WHERE `user_id` = (?)', array($id));
    $name = dbGetResult($mysqli, 'SELECT `user_name` FROM `users` WHERE `id` = (?)', array($id));
}
