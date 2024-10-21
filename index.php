<?php
require_once "includes/headerfunction.php";
require_once "includes/header.php";
?>
<form action="index.php" method="post" id="filter">
    <label>Filtrer par titre</label>
    <input type="checkbox" name="filtrepartitre" id="filtrepartitre"> <br/>

    <label>Filtrer par contenu</label>
    <input type="checkbox" name="filtreparcontenu" id="filtreparcontenu"> <br/>
    <input type="text" id="filtre" name="filtre" placeholder="Filtre...."> <br/>
    <label>Filtrer par categorie</label>
    <select name="filtreparcategorie" class="filtreparcategorie">
        <option value="Aucun">Aucun</option>
        <option value="Actu">Actu</option>
        <option value="game">game</option>
        <option value="informatique">informatique</option>
    </select>
    <br/>

    <button type="submit">Filtrer</button>
</form>

<div class="articles">
    <?php
    try {
        $conn = new PDO('mysql:host=localhost;dbname=blog', 'root', '');
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = '';
        $params = [];

        if (isset($_POST['filtre']) && !empty($_POST['filtre'])) {
            $filtre = $_POST['filtre'];

            if (isset($_POST['filtrepartitre'])) {
                $sql = 'SELECT DISTINCT * FROM articles WHERE title LIKE :filtre ORDER BY created_at DESC LIMIT 25';
                $params['filtre'] = "%" . $filtre . "%";
            } elseif (isset($_POST['filtreparcontenu'])) {
                $sql = 'SELECT DISTINCT * FROM articles WHERE content LIKE :filtre ORDER BY created_at DESC LIMIT 25';
                $params['filtre'] = "%" . $filtre . "%";
            }
        }

        // Filtrage par catégorie
        if (isset($_POST['filtreparcategorie']) && !empty($_POST['filtreparcategorie'] && $_POST['filtreparcategorie'] !== 'Aucun')) {
            $categorie = $_POST['filtreparcategorie'];
            if (!empty($sql) && $categorie != 'Aucun') {
                $sql .= ' AND categorie = :categorie';
            } else {
                $sql = 'SELECT DISTINCT * FROM articles WHERE categorie = :categorie ORDER BY created_at DESC LIMIT 25';
            }
            $params['categorie'] = $categorie;
        }

        // Si aucune requête n'a été définie, charger tous les articles
        if (empty($sql)) {
            $sql = 'SELECT DISTINCT * FROM articles ORDER BY created_at DESC LIMIT 25';
            $stmt = $conn->query($sql);
        } else {
            $stmt = $conn->prepare($sql);
            $stmt->execute($params);
        }

        // Vérification si il y a des articles dans la base de données
        if ($stmt->rowCount() > 0) {
            foreach ($stmt as $row) {
                $article_row = $row;
                include "includes/cartearticle.php";
            }
        } else {
            echo "<p>Aucun article trouvé</p>";
        }
    } catch (PDOException $e) {
        echo "SQL error: " . $e->getMessage();
    }
    ?>
</div>

<?php
require_once "includes/footer.php";
?>