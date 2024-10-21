<?php
require_once "includes/headerfunction.php";
require_once "includes/article_functions.php";

if (empty($_SESSION['user'])) {
    header('Location: login.php');
    die;
}

var_dump($_POST);

if (!empty($_FILES['img_article']) && !empty($_POST["title"]) && !empty($_POST["content"]) && !empty($_POST["categorie"])) {
    $title = $_POST["title"];
    $description = $_POST["content"];
    $category = $_POST["categorie"];

    $id = add_article_database($title, $description, $category);

    if ($id == -1) {
        echo "erreur!";
    } else {
        $upload = 'imgArticle/';
        $uploadFile = $upload . $id . '.png';
        if (move_uploaded_file($_FILES['img_article']['tmp_name'], $uploadFile)) {
            if (update_image_url($id, $uploadFile)) {
                echo "<img src=\"$uploadFile\" alt=\"Image chargée\" style=\"max-width: 100%; height: auto;\">";
            }
        }
    }
}
require_once "includes/header.php";
?>
<form action="create_article.php" method="post" enctype="multipart/form-data">
    <label>Title </label>
    <input name="title" id="title" type="text" placeholder="title"/><br>

    <label>Content </label>
    <input name="content" id="content" type="text"/><br>

    <label>Catégorie</label>
    <select name="categorie" id="categorie">
        <option value="Aucun">Aucun</option>
        <option value="Actu">Actu</option>
        <option value="game">game</option>
        <option value="informatique">informatique</option>
    </select><br>

    <label>Image :</label>
    <input type="file" name="img_article" id="img_article" accept="image/*"><br/>

    <input type="submit">
</form>
<?php
require_once "includes/footer.php";
?>
