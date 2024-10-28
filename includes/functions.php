<?php
require_once 'User.php';
require_once 'bdd.php';

function is_connected(): bool {
    return !empty($_SESSION['user_id']);
}

session_start();
