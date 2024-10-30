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
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Blog - Mon compte</title>
    <link rel="stylesheet" href="res/css/style.css">
</head>
<body>
<?php require_once "includes/header.php"; ?>
    <main class="account">
        <div>
            <form method="post" action="account.php" enctype="multipart/form-data">
                <label>
                    <span>Nom d'utilisateur</span>
                    <input type="text" name="username" placeholder="Nom d'utilisateur" value="<?= $username ?>">
                </label>
                <label>
                    <span>Photo de profil</span>
                    <input type="file" name="avatar" placeholder="Photo de profil" accept="image/*">
                </label>
                <label>
                    <span>Email</span>
                    <input type="text" name="email" placeholder="Email" value="<?= $email ?>">
                </label>
                <label>
                    <span>Mot de passe</span>
                    <input type="password" name="password" placeholder="Mot de passe">
                </label>

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