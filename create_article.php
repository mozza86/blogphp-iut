<?php
require_once "includes/headerfunction.php";
require_once "includes/article_functions.php";

if (!is_connected()) {
    header('Location: login.php');
    die;
}
$conn = new PDO('mysql:host=localhost;dbname=blog', 'root', '');
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$Select_categorie = 'SELECT * FROM categories';
$stmtCategory = $conn->prepare($Select_categorie);
$stmtCategory->execute();
$ListeCategorie = $stmtCategory->fetchAll();

if (!empty($_FILES['img_article']) && !empty($_POST["title"]) && !empty($_POST["content"])) {
    $title = $_POST["title"];
    $description = $_POST["content"];
    $category_name = $_POST["categorie"];
    $article_id = add_article_database($title, $description);

    if ($article_id == -1) {
        echo "Erreur lors de la création de l'article!";
    } else {
        $upload = 'imgArticle/';
        $uploadFile = $upload . $article_id . '.png';
        if (move_uploaded_file($_FILES['img_article']['tmp_name'], $uploadFile)) {
            if (update_image_url($article_id, $uploadFile)) {
                echo "<img src=\"$uploadFile\" alt=\"Image chargée\" style=\"max-width: 100%; height: auto;\">";
            }
        }

        // Récupérer l'ID de la catégorie
        $category_id = get_category_id($category_name);
        if ($category_id != null && $category_id > 0) {
            add_article_category($article_id, $category_id);
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

    <label>Content </label>
    <input name="content" id="content" type="text" /><br>

    <label>Catégorie :</label>
    <select name="categorie" id="categorie">
        <option value="">Aucun</option>
        <?php foreach ($ListeCategorie as $categorie): ?>
            <option value="<?= $categorie['id'] ?>"><?= htmlentities($categorie['name']) ?></option>
        <?php endforeach; ?>
    </select> <br/>

    <label>Image :</label>
    <input type="file" name="img_article" id="img_article" accept="image/*"> <br/>
    <input type="submit">
</form>

<?php
require_once "includes/footer.php";
?>
