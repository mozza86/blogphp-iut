<?php
require_once "includes/headerfunction.php";
require_once "includes/header.php";
require_once "includes/realheader.php";
require_once "includes/nav.php";
?>
<form action="index.php" method="post">
    <label>Filtre pour les titres :</label>
    <input type="checkbox" id="filtrerpartitre" name="filtrerpartitre">
    <label>Filtre pour les contenus :</label>
    <input type="checkbox" id="filtrerparcontenu" name="filtrerparcontenu">
    <label>Filtre par l'auteur :</label>
    <input type="checkbox" id="filtrerparauteur" name="filtrerparauteur">
    <select name="filtrerparcategorie" id="filtrerparcategorie">
        <option value="Aucun">Aucun</option>
        <option value="informatique">Informatique</option>
        <option value="actualite">Actualité</option>
        <option value="game">Game</option>
    </select>
    <input type="text" id="valfiltre" name="valfiltre" placeholder="Entrez la valeur">
    <button type="submit">Filtrer</button>
</form>
<main class="home">
    <?php

      try {
        $conn = new PDO('mysql:host=localhost;dbname=blog', 'root', '');
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = 'SELECT * FROM articles WHERE 1=1'; // Condition de base pour ajouter des filtres
        $params = [];

        // Filtrer par titre
        if (isset($_POST['filtrerpartitre']) && !empty($_POST['valfiltre'])) {
            $sql .= ' AND title LIKE :filtervalue ';
            $params[':filtervalue'] = '%' . $_POST['valfiltre'] . '%';
        }

        // Filtrer par contenu
        if (isset($_POST['filtrerparcontenu']) && !empty($_POST['valfiltre'])) {
            $sql .= ' AND content LIKE :filtervalue ';
            $params[':filtervalue'] = '%' . $_POST['valfiltre'] . '%';
        }

        // Filtrer par catégorie
        if (!empty($_POST['filtrerparcategorie']) && $_POST['filtrerparcategorie'] != 'Aucun') {
            $sql .= ' AND categorie = :categorie ';
            $params[':categorie'] = $_POST['filtrerparcategorie'];
        }
        if (!empty($_POST['filtrerparauteur']) && !empty($_POST['valfiltre'])) {
            $sql = 'SELECT * FROM articles a JOIN users u ON a.user_id = u.id WHERE 1=1';
            $params[':auteur'] = $_POST['filtrerparauteur'];
        }
        $sql .= ' ORDER BY created_at DESC LIMIT 25';
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        $values = $stmt->fetchAll();

    } catch (PDOException $e) {
        echo "SQL error: " . $e->getMessage();
    }

    include "includes/filter.php";

    foreach ($values as $row) {
        $article_row = $row;
        include "includes/cartearticle.php";
    }
    ?>
</main>
<?php
require_once "includes/footer.php";
?>
