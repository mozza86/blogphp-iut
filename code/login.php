<?php
require_once "includes/functions.php";
if (is_connected()) {
    try {
        $user = User::findById($_SESSION['user_id'] ?? null);
        $username = $user->getUsername();
        $avatar_url = $user->getAvatarUrl();
    } catch (ObjectNotFoundException|ObjectDeletedException|SQLException $e) {
        die($e->getMessage());
    }
}

if (!empty($_POST["email"]) && !empty($_POST["password"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];

    try {
        $user = User::loginOrCreate($email, $password);
        $_SESSION["user_id"] = $user->getId();
        header('Location: account.php');
        die;
    } catch (ObjectDeletedException|InvalidEmailException|SQLException $e) {
        $error_msg = $e->getMessage();
    }
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Blog - Connexion</title>
    <link rel="stylesheet" href="res/css/style2.css">
</head>
<body class="login">
    <nav>
        <div class="left">
            <a href=".." class="button">Accueil</a>
            <a href="create_article.php" class="button">Nouveau</a>
        </div>
        <div class="right">
            <a href="account.php" class="button">
                <?= $username ?? 'Connexion' ?>
                <img src="<?= $avatar_url ?? 'res/img/login.png' ?>" alt="<?= $username ?? 'Default' ?>'s avatar">
            </a>
        </div>
    </nav>
    <main>
        <form action="login.php" method="post">
            <div class="input_block">
                <label for="email">Email</label>
                <input type="text" name="email" id="email" placeholder="Email" value="<?= $_POST["email"] ?? '' ?>">
            </div>
            <div class="input_block">
                <label for="password">Mot de passe</label>
                <input type="password" name="password" id="password" placeholder="Mot de passe" value="<?= $_POST["password"] ?? '' ?>">
            </div>
            <input type="submit" value="Valider">
            <?php if (isset($user)): ?> <p><a href="account.php">You are already connected as <?= $username ?></a></p>  <?php endif; ?>
            <p class="error_msg"><?= $error_msg ?? '' ?></p>
        </form>
    </main>
</body>
</html>