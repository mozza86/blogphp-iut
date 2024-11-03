<?php

$ARTICLE_IMG_DIR = 'articleImgs/';
$AVATAR_IMG_DIR = 'avatars/';

require_once 'User.php';
require_once 'Exceptions.php';

/**
 * Verify if the user is login
 * @return bool
 */
function is_connected(): bool {
    if (!empty($_SESSION['user_id'])) {
        try {
            User::findById($_SESSION['user_id']);
            return true;
        } catch (UserNotFoundException|DatabaseException $e) {}
    }
    return false;
}

/**
 * Refresh the page
 * @return void
 */
function refresh_page(): void {
    $URI = $_SERVER['REQUEST_URI'];
    header("Location: $URI");
}

session_start();
