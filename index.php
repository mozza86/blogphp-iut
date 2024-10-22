<?php
require_once "includes/functions.php";

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Blog</title>
    <link rel="stylesheet" href="res/css/style2.css">
</head>
<body class="home">
    <header>
        <h1>Le blog</h1>
    </header>
    <nav>
        <div class="left">
            <a href="./" class="button">Home</a>
            <a href="create_article.php" class="button">New</a>
        </div>
        <div class="right">
            <a href="login.php" class="button">Login</a>
        </div>
    </nav>
    <main>
        <!--
        <form action="index.php" method="post" class="formclass">
        <label>Filtre pour les titres :</label>
        <input type="checkbox" id="filtrerpartitre" name="filtrerpartitre"> <br/>
        <label>Filtre pour les contenus :</label>
        <input type="checkbox" id="filtrerparcontenu" name="filtrerparcontenu"> <br/>
        <label>Filtre par l'auteur :</label>
        <input type="checkbox" id="filtrerparauteur" name="filtrerparauteur"> <br/>
        <input type="text" id="valfiltre" name="valfiltre" placeholder="Entrez la valeur"> <br/>

        <select name="filtrerparcategorie" id="filtrerparcategorie">
            <option value="Aucun">Aucun</option>
            <option value="informatique">Informatique</option>
            <option value="actualite">Actualité</option>
            <option value="game">Game</option>
        </select> <br/>
        <button type="submit">Filtrer</button>
    </form>
        -->
        <form class="filters">
            <h2>Filtres</h2>
            <div class="input_block">
                <label class="input_name" for="auteur">Auteur</label>
                <input type="text" name="auteur" id="auteur" placeholder="Auteur">
            </div>
            <div class="input_block">
                <label class="input_name" for="categorie">Catégorie</label>
                <select name="categorie" id="categorie">
                    <option value="1">Informatique</option>
                </select>
            </div>
            <div class="input_block">
                <label class="input_name" for="titre">Titre</label>
                <input type="text" name="titre" id="titre" placeholder="Titre">
            </div>
            <div class="input_block">
                <label class="input_name" for="contenu">Contenu</label>
                <input type="text" name="contenu" id="contenu" placeholder="Contenu">
            </div>
            <input class="button" type="submit" value="Filtrer">
        </form>
        <div class="articles">
            <?php
            try {
                $conn = new PDO('mysql:host=localhost;dbname=blog', 'root', '');
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $sql = 'SELECT a.*, u.username FROM articles a JOIN users u ON a.author_id = u.id WHERE 1=1';  // Condition de base pour ajouter des filtres
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
                    $sql .= ' AND u.username = :auteur';
                    $params[':auteur'] = $_POST['valfiltre'];
                }

                $sql .= ' ORDER BY created_at DESC LIMIT 25';
                $stmt = $conn->prepare($sql);
                $stmt->execute($params);
                $values = $stmt->fetchAll();

            } catch (PDOException $e) {
                echo "SQL error: " . $e->getMessage();
            }

            foreach ($values as $row) { ?>
                <div class="article">
                    <img src="<?= $row['image_url'] ?>">
                    <div class="preview">
                        <h4><?= htmlentities($row['title']) ?></h4>
                        <p><?= htmlentities($row['content']) ?></p>
                        <a href="article.php?id=<?= $row['id'] ?>">Lire Plus</a>
                    </div>
                </div>
            <?php } ?>
        </div>
    </main>
</body>
</html>
