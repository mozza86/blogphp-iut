<?php
require_once "includes/functions.php";
require_once 'includes/User.php';
require_once 'includes/Exceptions.php';

if (is_connected()) {
    try {
        $user = User::findById($_SESSION['user_id'] ?? null);
        $username = htmlspecialchars($user->getUsername());
    } catch (DatabaseException $e) {
        $error_msg = $e->getMessage();
        require_once 'includes/error_page.php';
        die;
    } catch (UserNotFoundException $e) {}
}

if (!empty($_GET['err'])) {
    $error_msg = match ($_GET['err']) {
        'NotConnected' => "Vous avez besoin d'etre connecté pour faire cette action",
        'NotAdmin' => "Vous avez besoin des permissions administrateur pour faire cette action",
        'LogOut' => "Vous avez été déconnecté",
        'UserDeleted' => "Votre compte vient d'etre supprimé",
    };
}
if (!empty($_POST["email"]) && !empty($_POST["password"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];
    try {
        $user = User::loginOrCreate($email, $password);
        $_SESSION["user_id"] = $user->getId();

        $return_article_id = $_GET['rid'] ?? '';

        $location = match ($_GET['return'] ?? 'Account') {
            'CreateArticle' => "create_article.php",
            'Article' => "show_article.php?id=$return_article_id",
            'Admin' => "admin/",
            'Account' => "account.php",
        };
        header("Location: $location");

        die;
    } catch (DatabaseException|IncorrectPasswordException|InvalidEmailException $e) {
        $error_msg = $e->getMessage();
    }
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Blog - Connexion</title>
    <link rel="stylesheet" href="res/css/style.css">
</head>
<body>
<?php require_once "includes/header.php"; ?>
<main class="login">
    <form action="<?= htmlspecialchars($_SERVER['REQUEST_URI']) ?>" method="post">
        <label>
            <span>Email</span>
            <input type="text" name="email" placeholder="Email" value="<?= $_POST["email"] ?? '' ?>">
        </label>
        <label>
            <span>Mot de passe</span>
            <input type="password" name="password" placeholder="Mot de passe" value="<?= $_POST["password"] ?? '' ?>">
        </label>
        <input type="submit" value="Valider">
        <?php if (isset($user)): ?> <p><a href="account.php">Vous êtes déjà connecté en tant que <?= $username ?></a>
        </p>  <?php endif; ?>
        <p class="error_msg"><?= $error_msg ?? '' ?></p>
    </form>
</main>
</body>
</html>