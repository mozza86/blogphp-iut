<?php
require_once '../includes/Category.php';
require_once '../includes/User.php';
require_once '../includes/functions.php';

if (!is_connected()) {
    header('Location: ../login.php?err=NotConnected');
    die;
}
try {
    $user = User::findById($_SESSION['user_id'] ?? null);
    if (!$user->isAdmin()) {
        header('Location: ../login.php?err=NotAdmin');
        die;
    }
    if ($user->isDeleted()) {
        header('Location: ../login.php?err=UserDeleted');
        die;
    }
} catch (ObjectDeletedException|ObjectNotFoundException|SQLException $e) {
    die($e->getMessage());
}

if (!empty($_POST['action'])) {
    try {
        switch ($_POST['action']) {
            case 'create':
                Category::create($_POST['name']);
                break;
            case 'update':
                Category::findById($_POST['id'])->update($_POST['name']);
                break;
            case 'delete':
                Category::findById($_POST['id'])->delete();
                break;
            default:
                throw new Exception('Unexpected action value');
        }
    } catch (ObjectNotFoundException|ObjectDeletedException|SQLException $e) {
        die($e->getMessage());
    }
}

try {
    $categories = Category::getAll();
} catch (SQLException $e) {
    die($e->getMessage());
}

?>

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
    <legend>Liste des categories</legend>
    <ul>
        <?php foreach ($categories as $category): ?>
            <li><?= $category->getName() ?></li>
        <?php endforeach; ?>
    </ul>
</fieldset>

<fieldset>
    <legend>Mise a jour d'une catégorie</legend>
    <form action="" method="post">
        <input type="hidden" name="action" value="update">
        <label>
            Catégorie:
            <select name="id">
                <?php foreach ($categories as $category): ?>
                    <option value="<?= $category->getId() ?>"><?= $category->getName() ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>
            Nom:
            <input type="text" name="name" placeholder="Nouveau nom de la catégorie">
        </label>
        <input type="submit" value="Mettre a jour">
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
                    <option value="<?= $category->getId() ?>"><?= $category->getName() ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <input type="submit" value="Supprimer">
    </form>
</fieldset>
