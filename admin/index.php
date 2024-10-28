<?php
require_once '../includes/Category.php';

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
    } catch (Exception $e) {
        die($e->getMessage());
    }
}

try {
    $categories = Category::getAll();
} catch (Exception $e) {
    die($e->getMessage());
}

?>

<fieldset>
    <legend>Créer</legend>
    <form action="./" method="post">
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
    <form action="./" method="post">
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
    <form action="./" method="post">
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
