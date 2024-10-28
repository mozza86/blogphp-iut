<?php
require_once 'includes/Article.php';
require_once 'includes/Category.php';
require_once "includes/functions.php";

if (!is_connected()) {
    header('Location: login.php');
    die;
}

try {
    $user = User::findById($_SESSION['user_id'] ?? null);
    $username = $user->getUsername();
    $avatar_url = $user->getAvatarUrl();
} catch (Exception $e) {
    $error_msg = $e->getMessage();
}

if ($user->isDeleted()) {
    header('Location: login.php');
    die;
}

if (!empty($_POST["title"]) && !empty($_POST["category"]) && !empty($_FILES['image']) && !empty($_POST["content"])) {
    $title = $_POST["title"];
    $category_id = $_POST["category"];
    $content = $_POST["content"];

    $image_url = 'imgArticle/'.uniqid().'.png';
    if (!move_uploaded_file($_FILES['image']['tmp_name'], $image_url)) {
        $error_msg = 'Error in file upload';
    } else {
        echo "<img src=\"$image_url\" alt=\"Image chargée\" style=\"max-width: 100%; height: auto;\">";

        try {
            $article = Article::create($title, $content, User::findById($_SESSION['user_id']), $image_url, Category::findById($category_id));
            header('Location: article.php?id='.$article->getId());
        } catch (Exception $e) {
            $error_msg = $e->getMessage();
        }
    }
}

try {
    $categories = Category::getAll();
} catch (Exception $e) {
    die($e->getMessage());
}

?>


<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Blog - Creer Article</title>
    <link rel="stylesheet" href="res/css/style2.css">
</head>
<body class="account">
<nav>
    <div class="left">
        <a href="./" class="button">Accueil</a>
        <a href="create_article.php" class="button">Nouveau</a>
    </div>
    <div class="right">
        <a href="account.php" class="button">
            <?= $username ?? 'Connexion' ?>
            <img src="<?= $avatar_url ?? 'res/img/login.png' ?>" alt="<?= $username ?? 'Default' ?>'s avatar">
        </a>
    </div>
</nav>
<main class="create_article">
    <form action="create_article.php" method="post" enctype="multipart/form-data">
        <div class="input_block">
            <label for="title">Titre</label>
            <input type="text" name="title" id="title" placeholder="Titre">
        </div>
        <div class="input_block">
            <label for="category">Categorie</label>
            <select name="category" id="category">
                <?php foreach ($categories as $category): ?>
                    <option value="<?= $category->getId() ?>"><?= $category->getName() ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="input_block">
            <label for="image">Image</label>
            <input type="file" name="image" id="image" placeholder="Image" accept="image/*">
        </div>
        <div class="input_block">
            <label for="content">Contenu</label>
            <textarea name="content" id="content" cols="30" rows="10"></textarea>
        </div>

        <input type="submit" value="Publier">
        <p class="error_msg"><?= $error_msg ?? '' ?></p>
    </form>
</main>
</body>
</html>