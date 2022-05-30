<?php

require_once('helpers.php');
require_once('init.php');

$content = include_template('guest.php');

print(include_template('layout.php', array(
    'content' => $content,
    'title' => $title
)));
