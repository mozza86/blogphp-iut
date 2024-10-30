<?php
require_once "includes/functions.php";

if (!is_connected()) {
    header('Location: login.php?err=NotConnected');
    die;
}

try {
    $user = User::findById($_SESSION['user_id'] ?? null);
    $username = htmlentities($user->getUsername());
    $email = htmlentities($user->getEmail());
    $avatar_url = htmlentities($user->getAvatarUrl());
} catch (ObjectNotFoundException|ObjectDeletedException|SQLException $e) {
    die($e->getMessage());
}

if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: login.php?err=LogOut');
}

try {
    if (!empty($_POST['email'])) {
        $user->setEmail($_POST['email']);
    }
    if (!empty($_POST['password'])) {
        $user->setPassword($_POST['password']);
    }
    if (!empty($_POST['username'])) {
        $user->setUsername($_POST['username'] ?? $user->getEmail());
    }
    if(!empty($_FILES['avatar']) && !empty($_FILES['avatar']['tmp_name'])) {
        $avatar_url = 'uploads/'.uniqid().'.png';

        if (move_uploaded_file($_FILES['avatar']['tmp_name'], $avatar_url)) {
            $user->setAvatarUrl($avatar_url);
        } else {
            echo('Error uploading avatar');
        }
    }
} catch (ObjectDeletedException|SQLException $e) {
    $error_msg = $e->getMessage();
}

?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Blog - Compte</title>
    <link rel="stylesheet" href="res/css/style2.css">
</head>
<body class="account">
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
        <div>
            <form method="post" action="account.php" enctype="multipart/form-data">
                <div class="input_block">
                    <label for="username">Nom d'utilisateur</label>
                    <input type="text" name="username" id="username" placeholder="Nom d'utilisateur" value="<?= $username ?>">
                </div>
                <div class="input_block">
                    <label for="avatar">Photo de profil</label>
                    <input type="file" name="avatar" id="avatar" placeholder="Photo de profil" accept="image/*">
                </div>
                <div class="input_block">
                    <label for="email">Email</label>
                    <input type="text" name="email" id="email" placeholder="Email" value="<?= $email ?>">
                </div>
                <div class="input_block">
                    <label for="password">Mot de passe</label>
                    <input type="password" name="password" id="password" placeholder="Mot de passe">
                </div>

                <input type="submit" value="Mettre a jour">
            </form>
            <form method="post" action="account.php">
                <input type="submit" name="logout" value="Se deconnecter" >
            </form>
            <p class="error_msg"><?= $error_msg ?? '' ?></p>
        </div>

    </main>
</body>
</html>