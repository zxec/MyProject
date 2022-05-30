<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: guest.php');
}

$id = $_SESSION['user_id'];
