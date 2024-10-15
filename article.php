<?php

require_once "includes/headerfunction.php";

function get_data($article_id) {
    try {
        $conn = new PDO('mysql:host=localhost;dbname=blog','root','');
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = 'SELECT * FROM articles where id = ?';
        $stmt= $conn->prepare($sql);
        $stmt->execute([ $article_id ]);

        return $stmt->fetch();
    } catch(PDOException $e) {
        echo "SQL error: ".$e->getMessage();
    }
    return false;
}

require_once "includes/header.php";

$article_row = get_data($_GET['id']);

?>
<main>

    <article class="page">
        <h1><?= htmlentities($article_row['title']) ?>
            <form method="post" action="article.php">
                <input type="submit" value="remove_article" name="remove_article">
            </form>

            <img src="<?= $article_row['image_url'] ?>">
            <div class="text">
                <?= htmlentities($article_row['content']) ?>
            </div>
    </article>
</main>

<?php

require_once  'includes/footer.php';
