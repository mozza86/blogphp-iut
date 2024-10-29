<?php
require_once "includes/functions.php";

if (is_connected()) {
    try {
        $user = User::findById($_SESSION['user_id'] ?? null);
        $username = $user->getUsername();
        $avatar_url = $user->getAvatarUrl();
    } catch (Exception $e) {
        $error_msg = $e->getMessage();
    }
}

try {
    $conn = get_bdd_connection();

    $Select_categorie = 'SELECT * FROM categories';
    $stmtCategory = $conn->prepare($Select_categorie);
    $stmtCategory->execute();
    $ListeCategorie = $stmtCategory->fetchAll();

    $sql = 'SELECT *, a.id as aid FROM articles a 
            JOIN users u ON a.author_id = u.id 
            JOIN article_categories ac ON a.id = ac.article_id 
            JOIN categories c ON ac.category_id = c.id 
            WHERE 1=1';
    $params = [];

    // Filtrer par auteur
    if (!empty($_POST['auteur'])) {
        $sql .= ' AND u.username LIKE :auteur';
        $params[':auteur'] = '%' . $_POST['auteur'] . '%';
    }

    // Filtrer par catégorie
    if (!empty($_POST['categorie'])) {
        $sql .= ' AND c.id = :categorie';
        $params[':categorie'] = $_POST['categorie'];
    }

    // Filtrer par titre
    if (!empty($_POST['titre'])) {
        $sql .= ' AND a.title LIKE :titre';
        $params[':titre'] = '%' . $_POST['titre'] . '%';
    }

    // Filtrer par contenu
    if (!empty($_POST['contenu'])) {
        $sql .= ' AND a.content LIKE :contenu';
        $params[':contenu'] = '%' . $_POST['contenu'] . '%';
    }

    $sql .= ' ORDER BY a.created_at DESC LIMIT 25';
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $values = $stmt->fetchAll();


} catch (PDOException $e) {
    echo "Erreur SQL : " . $e->getMessage();
}
?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
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
        <form method="POST" class="filters">
            <h2>Filtres</h2>
            <div class="input_block">
                <label class="input_name" for="auteur">Auteur</label>
                <input type="text" name="auteur" id="auteur" placeholder="Auteur">
            </div>
            <div class="input_block">
                <label class="input_name" for="categorie">Catégorie</label>
                <select name="categorie" id="categorie">
                    <option value="">Aucun</option>
                    <?php foreach ($ListeCategorie as $categorie): ?>
                        <option value="<?= $categorie['id'] ?>"><?= htmlentities($categorie['name']) ?></option>
                    <?php endforeach; ?>
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
            <?php foreach ($values as $row): ?>
                <div class="article">
                    <img src="<?= htmlentities($row['image_url']) ?>" alt="Image de l'article">
                    <div class="preview">
                        <h4><?= htmlentities($row['title']) ?></h4>
                        <p><?= htmlentities($row['content']) ?></p>
                        <a href="article.php?id=<?= $row['aid'] ?>">Lire Plus</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
</body>
</html>
