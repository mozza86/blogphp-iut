<?php
require_once 'includes/functions.php';
require_once 'includes/article_functions.php';

function is_admin() {
    return $_SESSION['user']['admin'] ?? 0;
}
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
    if (!empty($_POST['action']) && $_POST['action'] == "delete" && is_connected() && (is_admin() || is_article_admin($article_row))) {
        delete_article_database($_GET['id']);
    }
    // supprime le commentaire quand le bouton est cliqué
    if (!empty($_POST['action']) && $_POST['action'] == "delete_comment" && !empty($_POST['comment_id']) && is_connected()) {
        $comment_id = $_POST['comment_id'];

        try {
            $conn = new PDO('mysql:host=localhost;dbname=blog','root','');
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = 'SELECT * FROM comments where id = ?';
            $stmt= $conn->prepare($sql);
            $stmt->execute([ $comment_id ]);

            $comment_row = $stmt->fetch();
        } catch(PDOException $e) {
            die("SQL error: ".$e->getMessage());
        }

        if (!empty($comment_row['author_id']) && $_SESSION["user"]["id"] == $comment_row['author_id'] || is_admin()){
            delete_message($_POST['comment_id']);
        }
    }
    // ajout d'un commentaire
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
                <img src="<?= $article_row['avatar_url'] ?>" alt="Image de l'article">
                <div class="text">
                    <span class="author"><?= $article_row['username'] ?></span>
                    <span class="bio">"<?= $article_row['description'] ?>"</span>
                </div>
            </div>
        </div>
    </div>

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
                <?php if (is_connected()) { ?>
                    <form action="article.php?id=<?= $_GET['id'] ?>" method="post">
                        <input type="hidden" name="action" value="new_comment">
                        <label>
                            Commentaire:
                            <input type="text" name="comment" placeholder="Commentaire">
                        </label>
                        <input type="submit" value="Envoyer">
                    </form>
                <?php
                }

                try {
                    $conn = new PDO('mysql:host=localhost;dbname=blog','root','');
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $sql =  'SELECT c.id, c.content as content, u.username as username, u.avatar_url as avatar_url FROM comments c join users u on c.author_id = u.id where c.article_id = ? order by created_at desc limit 25';
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
                            <img src="<?= $row['avatar_url'] ?>" alt="Image de profil de <?= htmlentities($row['username']) ?>">
                            <span class="username"><?= htmlentities($row['username']) ?></span>
                            <form action="article.php?id=<?= $_GET['id'] ?>" method="post">
                                <input type="hidden" name="action" value="delete_comment">
                                <input type="hidden" name="comment_id" value="<?= $row['id'] ?>">
                                <input type="submit" value="Supprimer">
                            </form>
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

    ?>
    <main class="error">
        <div class="container">
            L'article n'existe pas
        </div>
    </main>
    <?php
}
?>

