<?php
global $ARTICLE_IMG_DIR;

require_once "includes/functions.php";
require_once 'includes/User.php';
require_once 'includes/Category.php';
require_once 'includes/Exceptions.php';
require_once 'includes/Article.php';

if (!is_connected()) {
    header('Location: login.php?err=NotConnected&return=CreateArticle');
    die;
}

try {
    $user = User::findById($_SESSION['user_id'] ?? null);
    $username = htmlspecialchars($user->getUsername());
    $avatar_url = $user->getAvatarUrl();
} catch (UserNotFoundException|DatabaseException $e) {
    var_dump($e->getMessage());
}

try {
    $categories = Category::getAll();
} catch (DatabaseException $e) {
    var_dump($e->getMessage());
}

if (!empty($_POST["title"]) && !empty($_POST["category"]) && !empty($_POST["content"])) {
    $title = $_POST["title"];
    $category_id = $_POST["category"];
    $content = $_POST['content'];

    $image_url = '';
    if (!empty($_FILES['image'])) {
        $image_url = $ARTICLE_IMG_DIR . uniqid() . '.png';
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $image_url)) {
            $error_msg = 'Error in file upload';
            $image_url = '';
        }
    }

    try {
        $article = Article::create($title, $content, User::findById($_SESSION['user_id']), $image_url, Category::findById($category_id));
        header('Location: show_article.php?id=' . $article->getId());
    } catch (UserNotFoundException|DatabaseException|CategoryNotFoundException $e) {
        $error_msg = $e->getMessage();
    }
}


?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Blog - Nouveau Article</title>
    <link rel="stylesheet" href="res/css/style.css">

    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.js"></script>

</head>
<body>
<?php require_once "includes/header.php"; ?>
<main class="create_article">
    <form action="create_article.php" method="post" onsubmit="submitQuillContent()">
        <label>
            <span>Titre</span>
            <input type="text" name="title" placeholder="Titre" required>
        </label>
        <label>
            <span>Categorie</span>
            <select name="category" required>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= $category->getId() ?>"><?= htmlspecialchars($category->getName()) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <textarea id="summernote" name="content" required></textarea>

        <input type="submit" value="Publier">
        <p class="error_msg"><?= $error_msg ?? '' ?></p>
    </form>
    <script>
        $(document).ready(function() {
            $('#summernote').summernote({
                placeholder: 'Contenu de votre article',
                tabsize: 2,
                height: 300,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['codeview']]
                ]
            });
        });
    </script>
</main>
</body>
</html>