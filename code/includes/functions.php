<?php
require_once 'User.php';
require_once 'Exceptions.php';

function is_connected(): bool {
    if (!empty($_SESSION['user_id'])) {
        try {
            $user = User::findById($_SESSION['user_id']);
            if (!$user->isDeleted()) {
                return true;
            }
        } catch (ObjectNotFoundException|SQLException $e) {}
    }
    return false;
}

session_start();
