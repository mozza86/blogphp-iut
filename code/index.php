<?php
require_once "includes/functions.php";
require_once "includes/Category.php";

if (is_connected()) {
    try {
        $user = User::findById($_SESSION['user_id'] ?? null);
        $username = htmlspecialchars($user->getUsername());
        $avatar_url = $user->getAvatarUrl();
    } catch (UserNotFoundException|SQLException $e) {
        $error_msg = $e->getMessage();
    }
}

try {
    $sql = 'SELECT *, a.id as article_id FROM articles a 
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

    $conn = get_bdd_connection();
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $values = $stmt->fetchAll();

} catch (PDOException $e) {
    echo "Erreur SQL : " . $e->getMessage();
}

try {
    $categories = Category::getAll();
} catch (SQLException $e) {
    echo "Erreur Category::getAll() : " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Blog</title>
    <link rel="stylesheet" href="res/css/style.css">
</head>
<body>
    <header>
        <h1>Le blog</h1>
    </header>
    <?php require_once "includes/header.php"; ?>
    <main class="home">
        <form method="POST" class="filters">
            <h2><label>Filtres<input type="checkbox" class="toggle_filters"></label></h2>

            <label>
                <span>Auteur</span>
                <input type="text" name="auteur" placeholder="Auteur">
            </label>
            <label>
                <span>Catégorie</span>
                <select name="categorie">
                    <option value="">Toutes</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category->getId() ?>"><?= htmlspecialchars($category->getName()) ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <label>
                <span>Titre</span>
                <input type="text" name="titre" placeholder="Titre">
            </label>
            <label>
                <span>Contenu</span>
                <input type="text" name="contenu" placeholder="Contenu">
            </label>

            <input type="submit" value="Filtrer">
        </form>
        <div class="articles">
            <?php foreach ($values as $row): ?>
                <article>
                    <img src="<?= $row['image_url'] ?>" alt="Image de l'article">
                    <div class="preview">
                        <h4><?= htmlspecialchars($row['title']) ?></h4>
                        <p><?= htmlspecialchars($row['content']) ?></p>
                        <a href="article.php?id=<?= $row['article_id'] ?>">Lire Plus</a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </main>
</body>
</html>
