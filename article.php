<?php
require_once "includes/headerfunction.php";
require_once 'includes/article_functions.php';
require_once "includes/header.php";

var_dump($_REQUEST);

$article_row = null;
if (!empty($_GET['id'])) {
    $article_id = $_GET['id'];
    try {
        $conn = new PDO('mysql:host=localhost;dbname=blog','root','');
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = 'SELECT * FROM articles a join users u on a.author_id = u.id where a.id = ?';
        $stmt= $conn->prepare($sql);
        $stmt->execute([ $article_id ]);

        $article_row = $stmt->fetch();
    } catch(PDOException $e) {
        die("SQL error: ".$e->getMessage());
    }
}

if ($article_row) {
    // supprime le post quand le bouton est cliqué
    if (!empty($_POST['action']) && $_POST['action'] == "delete" && is_connected() && is_admin() || is_article_admin($article_row)) {
        delete_article_database($_GET['id']);
    }
    if (!empty($_POST['action']) && $_POST['action'] == "new_comment" && !empty($_POST['comment']) && is_connected()) {
        $comment = $_POST['comment'];
        $author_id = $_SESSION['user']['id'];
        $article_id = $_GET['id'];

        try {
            $conn = new PDO('mysql:host=localhost;dbname=blog', 'root', '');
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $conn->prepare("INSERT INTO comments (author_id, content, article_id) VALUES(?, ?, ?)");
            $stmt->execute([$author_id, $comment, $article_id]);
        } catch (PDOException $e) {
            die ('Erreur pdo' . $e->getMessage());
        } catch (Exception $e) {
            die ('Erreur général' . $e->getMessage());
        }
    }
    ?>

    <div class="container">
        <div class="article_details">
            <h1><?= htmlentities($article_row['title']) ?></h1>
            <p>Published the <?= $article_row['created_at'] ?></p>
            <div class="author_card">
                <img src="<?= $article_row['avatar_url'] ?>">
                <div class="text">
                    <span class="author"><?= $article_row['username'] ?></span>
                    <span class="bio">"<?= $article_row['description'] ?>"</span>
                </div>
            </div>
        </div>
    </div>

    <?php require_once "includes/nav.php"; ?>

    <main class="article">
        <article>
            <div class="container">
                <img alt="" src="<?= $article_row['image_url'] ?>">
                <!-- bouston de supression affiché que si l'utilisateur est admin ou créateur du post -->
                <?php if (is_admin() || is_article_admin($article_row)) { ?>
                    <form action="article.php?id=<?= $_GET['id'] ?>" method="post">
                        <input type="hidden" name="action" value="delete">
                        <input type="submit" value="Supprimer">
                    </form>
                <?php } ?>
                <p>
                    <?= htmlentities($article_row['content']) ?>
                </p>
            </div>
        </article>
        <div class="container">
            <fieldset>
                <legend>Commentaire</legend>
                <form action="article.php?id=<?= $_GET['id'] ?>" method="post">
                    <input type="hidden" name="action" value="new_comment">
                    <input type="text" name="comment" placeholder="Commentaire">
                    <input type="submit" value="Envoyer">
                </form>
                <?php

                try {
                    $conn = new PDO('mysql:host=localhost;dbname=blog','root','');
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $sql =  'SELECT c.content as content, u.username as username, u.avatar_url as avatar_url FROM comments c join users u on c.author_id = u.id where c.article_id = ? order by created_at desc limit 25';
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([$_GET['id']]);
                    $values = $stmt->fetchAll();
                    $conn = null;
                } catch(PDOException $e) {
                    echo "SQL error: ".$e->getMessage();
                }

                foreach  ($values as $row) {
                    ?>
                    <div class="comment">
                        <div class="author_card">
                            <img src="<?= $row['avatar_url'] ?>">
                            <span class="username"><?= htmlentities($row['username']) ?></span>
                        </div>
                        <div class="text"><?= htmlentities($row['content']) ?></div>
                    </div>
                    <?php
                }
                ?>
            </fieldset>
        </div>
    </main>

<?php

} else {

    require_once "includes/realheader.php";
    require_once "includes/nav.php";
    ?>
    <main class="error">
        <div class="container">
            L'article n'existe pas
        </div>
    </main>
    <?php
}
require_once 'includes/footer.php';
?>