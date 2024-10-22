<?php
require_once "../includes/headerfunction.php";
require_once "../includes/header.php";

var_dump($_POST);
function get_categories(){
    $values = [];
    try {
        $conn = new PDO('mysql:host=localhost;dbname=blog','root','');
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql =  'SELECT * FROM categories';
        $stmt = $conn->prepare($sql);
        $stmt->execute([]);
        $values = $stmt->fetchAll();
        $conn = null;
    } catch(PDOException $e) {
        echo "SQL error: ".$e->getMessage();
    }
    return $values;
}

// supprimer une catégorie a la BD
if (!empty($_POST["categorie_a_suppr"])) {
    try {
        $conn = new PDO('mysql:host=localhost;dbname=blog','root','');
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $conn->prepare("DELETE FROM categories where id = ?");
        $stmt->execute([$_POST["categorie_a_suppr"]]);
    } catch (PDOException $e) {
        die('Erreur pdo' . $e->getMessage());
    } catch (Exception $e) {
        die('Erreur général' . $e->getMessage());
    }
}
// modifier une categorie
if (!empty($_POST["ancien_nom"]) && !empty($_POST["nouveau_nom"]) ) {
    try{
            $conn = new PDO('mysql:host=localhost;dbname=blog', 'root', '');
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $conn->prepare("UPDATE categories SET name = ? WHERE id = ?");
            $stmt->execute([$_POST["nouveau_nom"], $_POST["ancien_nom"]]);
        } catch (PDOException $e) {
            echo('Erreur pdo' . $e->getMessage());
        } catch (Exception $e) {
            echo('Erreur général' . $e->getMessage());
        }
    }

//rajoute une catégorie a la BD
if (!empty($_POST["nom"])) {
    try {
        $conn = new PDO('mysql:host=localhost;dbname=blog', 'root', '');
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $conn->prepare("INSERT INTO categories (name) VALUES(?)");
        $stmt->execute([$_POST['nom']]);

    } catch (PDOException $e) {
        die ('Erreur pdo' . $e->getMessage());
    } catch (Exception $e) {
        die('Erreur général' . $e->getMessage());
    }
}
?>
<fieldset>
    <legend>Supprimer une catégorie</legend>
    <form action="index.php" method="post" class="formclass">
        <select name="categorie_a_suppr" id="filtrerparcategorie">
            <?php
            foreach  (get_categories() as $row) {?>
                <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
            <?php } ?>
        </select>
        <input type="submit" value="Supprimerr">
    </form>
</fieldset>
<fieldset>
    <legend>Mise a jour d'une catégorie</legend>
    <form action="index.php" method="post" class="formclass">
        <select name="ancien_nom" >
            <?php
            foreach  (get_categories() as $row) {?>
                <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
            <?php } ?>
        </select>
        <input type="text" name="nouveau_nom" placeholder="Nouveau nom de la catégorie">
        <input type="submit" value="Mettre a jour">
    </form>
</fieldset>
<fieldset>
    <legend>Créer</legend>
    <form method ='POST' action = 'index.php'>
        <input type="text" name="nom" placeholder="Nouveau nom de la catégorie">
        <button type="submit">Créer</button>

    </form>
</fieldset>
<fieldset>
    <legend>Liste des categories</legend>
    <ul>
    <?php

    foreach  (get_categories() as $row) {?>
        <li><?= $row['name'] ?></li>
    <?php } ?>
    </ul>
</fieldset>


