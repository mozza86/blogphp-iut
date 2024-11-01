<?php
require_once 'User.php';
require_once 'Exceptions.php';

function is_connected(): bool {
    if (!empty($_SESSION['user_id'])) {
        try {
            User::findById($_SESSION['user_id']);
            return true;
        } catch (UserNotFoundException|SQLException $e) {}
    }
    return false;
}

session_start();
