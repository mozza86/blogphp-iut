<?php
require_once "includes/headerfunction.php";
require_once "includes/user_functions.php";
?>
<nav>
    <div class="container">
        <div class="left">
            <a href="./">Home</a>
            <?php if (is_connected()) { ?>
            <a href="./create_article.php">New</a>
            <?php } ?>
        </div>
        <div class="right">
            <a href="account.php" class="account"><?= $_SESSION['user']['username'] ?? 'Login' ?><img src="<?= $_SESSION['user']['avatar_url'] ?? 'res/img/login.png' ?> " alt="avatar"></a>
        </div>
    </div>
</nav>
</header>

