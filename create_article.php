<?php
require_once "includes/headerfunction.php";

require_once "includes/article_functions.php";

if (!is_connected()) {
    header('Location: login.php');
    die;
}

if(!empty($_FILES['img_article']) && !empty($_POST["title"]) && !empty($_POST["content"])) {
    $title = $_POST["title"];
    $description = $_POST["content"];
    $id = add_article_database($title, $description);

    if ($id == -1) {
        echo "erreur!";
    } else {
        $upload = 'imgArticle/';
        $uploadFile = $upload . $id . '.png';
        if (move_uploaded_file($_FILES['img_article']['tmp_name'], $uploadFile)) {
            if(update_image_url($id, $uploadFile)) {
                echo "<img src=\"$uploadFile\" alt=\"Image chargée\" style=\"max-width: 100%; height: auto;\">";
            }
        }
    }
}
require_once "includes/header.php";
require_once "includes/realheader.php";
require_once "includes/nav.php";
?>
<form action="create_article.php" method="post" enctype="multipart/form-data">
    <label>Title </label>
    <input name="title" id="title" type="text" placeholder="title"/><br>

    <label>content </label>
    <input name="content" id="content" type="text" /><br>
    <label>Image :</label>
    <input type="file" name="img_article" id="img_article" accept="image/*"> <br/>
    <input type="submit">
</form>
<?php

require_once "includes/footer.php";

?>