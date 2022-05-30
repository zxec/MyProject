<?php

$mysqli = new mysqli('localhost', 'root', '', 'thingsarefine');

if ($mysqli->connect_error) {
    die('Error : (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

$mysqli->set_charset('utf8');
