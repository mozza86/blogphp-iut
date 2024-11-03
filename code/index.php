<?php
require_once "includes/functions.php";
require_once "includes/Category.php";
require_once "includes/Article.php";
require_once 'includes/Exceptions.php';

$articles = [];
try {
    $articles = Article::filter($_POST, 1);
} catch (DatabaseException|UserNotFoundException $e) {
    $error_msg = $e->getMessage();
    require_once 'includes/error_page.php';
    die;
}

$categories = [];
try {
    $categories = Category::getAll();
} catch (DatabaseException $e) {
    echo "Erreur a la recuperation des categories : " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
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
        <?php foreach ($articles as $article): ?>
            <article>
                <div class="preview">
                    <h2><?= htmlspecialchars($article->getTitle()) ?></h2>
                    <p><?= substr(strip_tags($article->getContent()), 0, 350) ?>...</p>

                    <a href="show_article.php?id=<?= $article->getId() ?>">Lire Plus</a>
                    <p></p>

                    <span><?= count($article->getComments()) ?> Commentaire<?= count($article->getComments()) != 1 ? 's' : '' ?></span>
                    <span>Catégories: <?php foreach ($article->getCategories() as $category) { echo $category->getName().' ';} ?></span>
                    <span>Publié par <?= htmlspecialchars($article->getAuthor()->getUsername()) ?> le <?= $article->getUpdatedAt() ?></span>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</main>
</body>
</html>
