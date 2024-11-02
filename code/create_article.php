<?php
global $ARTICLE_IMG_DIR;
require_once "includes/functions.php";
require_once 'includes/Article.php';
require_once 'includes/Category.php';

if (!is_connected()) {
    header('Location: login.php?err=NotConnected');
    die;
}

try {
    $user = User::findById($_SESSION['user_id'] ?? null);
    $username = htmlspecialchars($user->getUsername());
    $avatar_url = $user->getAvatarUrl();
} catch (SQLException|UserNotFoundException $e) {
    die($e->getMessage());
}


if (!empty($_POST["title"]) && !empty($_POST["category"]) && !empty($_FILES['image']) && !empty($_POST["content"])) {
    $title = $_POST["title"];
    $category_id = $_POST["category"];
    $content = $_POST["content"];

    $image_url = $ARTICLE_IMG_DIR.uniqid().'.png';
    if (!move_uploaded_file($_FILES['image']['tmp_name'], $image_url)) {
        $error_msg = 'Error in file upload';
    } else {
        try {
            $article = Article::create($title, $content, User::findById($_SESSION['user_id']), $image_url, Category::findById($category_id));
            header('Location: article.php?id='.$article->getId());
        } catch (SQLException|ObjectNotFoundException|UserNotFoundException $e) {
            $error_msg = $e->getMessage();
        }
    }
}

try {
    $categories = Category::getAll();
} catch (SQLException $e) {
    die($e->getMessage());
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Blog - Nouveau Article</title>
    <link rel="stylesheet" href="res/css/style.css">
</head>
<body>
<?php require_once "includes/header.php"; ?>
<main class="create_article">
    <form action="create_article.php" method="post" enctype="multipart/form-data">
        <label>
            <span>Titre</span>
            <input type="text" name="title" placeholder="Titre">
        </label>
        <label>
            <span>Categorie</span>
            <select name="category">
                <?php foreach ($categories as $category): ?>
                    <option value="<?= $category->getId() ?>"><?= htmlspecialchars($category->getName()) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>
            <span>Image</span>
            <input type="file" name="image" placeholder="Image" accept="image/*">
        </label>
        <label>
            <span>Contenu</span>
            <textarea name="content" cols="30" rows="10"></textarea>
        </label>

        <input type="submit" value="Publier">
        <p class="error_msg"><?= $error_msg ?? '' ?></p>
    </form>
</main>
</body>
</html>