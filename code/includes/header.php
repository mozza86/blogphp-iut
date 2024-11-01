<?php
require_once "functions.php";
require_once 'User.php';

if (is_connected()) {
    try {
        $user = User::findById($_SESSION['user_id'] ?? null);
        $username = $user->getUsername();
        $avatar_url = $user->getAvatarUrl();
    } catch (UserNotFoundException|SQLException $e) {
        $error_msg = $e->getMessage();
    }
}

if (empty($__PAGE_PREFIX)) {
    $__PAGE_PREFIX = './';
}
?>
<nav>
    <div class="left">
        <a class="button" href="<?= $__PAGE_PREFIX ?>">Accueil</a>
        <a class="button" href="<?= $__PAGE_PREFIX ?>create_article.php">Nouveau</a>
        <a class="button" href="<?= $__PAGE_PREFIX ?>admin/">Admin</a>
    </div>
    <div class="right">
        <a href="<?= $__PAGE_PREFIX ?>account.php" class="button">
            <?= $username ?? 'Se connecter' ?>
            <img src="<?= $__PAGE_PREFIX.($avatar_url ?? 'res/img/login.png') ?>" alt="<?= $username ?? 'Default' ?>'s avatar">
        </a>
    </div>
</nav>