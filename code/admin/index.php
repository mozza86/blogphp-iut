<?php
require_once '../includes/functions.php';
require_once '../includes/Category.php';
require_once '../includes/User.php';
require_once '../includes/Exceptions.php';

$__PAGE_PREFIX = '../';

if (!is_connected()) {
    header('Location: ../login.php?err=NotConnected&return=Admin');
    die;
}
try {
    $user = User::findById($_SESSION['user_id'] ?? null);
    if (!$user->isAdmin()) {
        header('Location: ../login.php?err=NotAdmin&return=Admin');
        die;
    }
} catch (UserNotFoundException|DatabaseException $e) {
    $error_msg = $e->getMessage();
    require_once '../includes/error_page.php';
    die;
}

if (!empty($_POST['action'])) {
    try {
        switch ($_POST['action']) {
            case 'create':
                if (!empty($_POST['name'])) {
                    Category::create($_POST['name']);
                    refresh_page();
                }
                break;
            case 'update':
                if (!empty($_POST['name']) && !empty($_POST['id'])) {
                    Category::findById($_POST['id'])->update($_POST['name']);
                    refresh_page();
                }
                break;
            case 'delete':
                if (!empty($_POST['id'])) {
                    Category::findById($_POST['id'])->delete();
                    refresh_page();
                }
                break;
        }
    } catch (CategoryNotFoundException|DatabaseException $e) {
        $error_msg = $e->getMessage();
        require_once '../includes/error_page.php';
        die;
    }
}

try {
    $categories = Category::getAll();
} catch (DatabaseException $e) {
    $error_msg = $e->getMessage();
    require_once '../includes/error_page.php';
    die;
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Blog - Espace Admin</title>
    <link rel="stylesheet" href="../res/css/style.css">
</head>
<body>
<header>
    <h1>Espace Admin</h1>
</header>
<?php require_once "../includes/header.php"; ?>
<main class="admin">
    <div>
        <fieldset>
            <legend>Créer</legend>
            <form action="" method="post">
                <input type="hidden" name="action" value="create">
                <label>
                    Nom:
                    <input type="text" name="name" placeholder="Nouveau nom de la catégorie">
                </label>
                <input type="submit" value="Créer">
            </form>
        </fieldset>

        <fieldset>
            <legend>Liste des catégories</legend>
            <ul>
                <?php foreach ($categories as $category): ?>
                    <li><?= htmlspecialchars($category->getName()) ?></li>
                <?php endforeach; ?>
            </ul>
        </fieldset>

        <fieldset>
            <legend>Mise à jour d'une catégorie</legend>
            <form action="" method="post">
                <input type="hidden" name="action" value="update">
                <label>
                    Catégorie:
                    <select name="id">
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category->getId() ?>"><?= htmlspecialchars($category->getName()) ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <label>
                    Nom:
                    <input type="text" name="name" placeholder="Nouveau nom de la catégorie">
                </label>
                <input type="submit" value="Mettre à jour">
            </form>
        </fieldset>

        <fieldset>
            <legend>Supprimer une catégorie</legend>
            <form action="" method="post">
                <input type="hidden" name="action" value="delete">
                <label>
                    Catégorie:
                    <select name="id">
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category->getId() ?>"><?= htmlspecialchars($category->getName()) ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <input type="submit" value="Supprimer">
            </form>
        </fieldset>
    </div>
</main>
</body>
</html>
