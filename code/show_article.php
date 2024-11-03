<?php
require_once "includes/functions.php";
require_once 'includes/User.php';
require_once 'includes/Article.php';
require_once 'includes/Category.php';
require_once 'includes/Exceptions.php';

if (empty($_GET['id'])) {
    die('Aucun article');
}

$article_id = $_GET['id'];
try {
    $article = Article::findById($article_id);
    $article_title = htmlspecialchars($article->getTitle());

    require_once 'includes/htmlpurifier-4.15.0-standalone/HTMLPurifier.standalone.php';
    $purifier = new HTMLPurifier();
    $article_content = $purifier->purify($article->getContent());

    $author_avatar = $article->getAuthor()->getAvatarUrl();
    $author_username = htmlspecialchars($article->getAuthor()->getUsername());
    $article_updated_at = $article->getUpdatedAt();
    $article_created_at = $article->getCreatedAt();
} catch (DatabaseException|ArticleNotFoundException|UserNotFoundException $e) {
    die($e->getMessage());
}

$user = null;
if (is_connected()) {
    try {
        $user = User::findById($_SESSION['user_id'] ?? null);
        $username = htmlspecialchars($user->getUsername());
        $avatar_url = $user->getAvatarUrl();
    } catch (DatabaseException $e) {
        $error_msg = $e->getMessage();
    } catch (UserNotFoundException $e) {
        $user = null;
    }

    if ($user && !empty($_POST['action'])) {
        $action = $_POST['action'];
        if ($action == "new_comment" && !empty($_POST['comment'])) {
            $content = $_POST['comment'];

            try {
                Comment::create($content, $user, $article);
                refresh_page();
            } catch (DatabaseException $e) {
                $error_msg = $e->getMessage();
            }
        }

        if ($action == "delete_article" && $article->isAllowedToDelete($user)) {
            try {
                $article->deleteArticle();
                die("L'article à été supprimé");
            } catch (DatabaseException $e) {
                $error_msg = $e->getMessage();
            }
        }

        if ($action == "delete_comment") {
            $comment_id = $_POST['comment_id'];
            try {
                $comment = Comment::findById($comment_id);
                if ($comment->isAllowedToDelete($user)) {
                    $comment->delete();
                    refresh_page();
                }
            } catch (ArticleNotFoundException|CommentNotFoundException|DatabaseException|UserNotFoundException $e) {
                $error_msg = $e->getMessage();
            }
        }
    }
}

$comments = $article->getComments();

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Blog - Article</title>
    <link rel="stylesheet" href="res/css/style.css">
</head>
<body>
<?php require_once "includes/header.php"; ?>
<main class="article">
    <span class="error_msg"><?= $error_msg ?? '' ?></span>
    <div class="article_content">
        <h1><?= $article_title ?></h1>
        <?php if ($user && $article->isAllowedToDelete($user)): ?>
            <form action="show_article.php?id=<?= $article_id ?>" method="post">
                <input type="hidden" name="action" value="delete_article">
                <input type="submit" value="Supprimer l'article">
            </form>
        <?php endif; ?>
        <div>
            <?= $article_content ?>
        </div>
    </div>
    <div class="author">
        <img src="<?= $author_avatar ?>" alt="l'image de l'auteur">
        <div class="infos">
            <span class="username"><?= $author_username ?></span>
            <span class="date">Publié le <?= $article_created_at ?><?= (($article_updated_at != $article_created_at ? ", mis à jour le $article_updated_at" : '')) ?></span>
        </div>
    </div>
    <div class="comments">
        <h2><?= count($comments) ?> Commentaire<?= count($comments) != 1 ? 's' : '' ?></h2>
        <?php if ($user): ?>
            <form class="new_comment" action="show_article.php?id=<?= $article_id ?>" method="post">
                <input type="hidden" name="action" value="new_comment">
                <div class="top">
                    <label for="new_comment">Nouveau commentaire</label>
                    <input type="submit" value="Envoyer">
                </div>
                <div class="bottom">
                    <img src="<?= $avatar_url ?>" alt="Avatar de <?= $username ?>">
                    <textarea name="comment" id="new_comment" placeholder="Nouveau commentaire"></textarea>
                </div>
            </form>
        <?php else: ?>
            <a href="login.php?return=Article&rid=<?= $article_id ?>">Connectez vous pour écrire un commentaire</a>
        <?php endif; ?>

        <?php foreach ($comments as $comment): ?>
            <div class="comment">
                <img src="<?= $comment->getAuthor()->getAvatarUrl() ?>"
                     alt="Avatar de <?= htmlspecialchars($comment->getAuthor()->getUsername()) ?>">
                <div class="content">
                    <div class="top">
                        <span class="username"><?= htmlspecialchars($comment->getAuthor()->getUsername()) ?></span>
                        <span class="date"><?= $comment->getCreatedAt() ?></span>
                        <?php if ($user && $comment->isAllowedToDelete($user)): ?>
                            <form action="show_article.php?id=<?= $article_id ?>" method="post">
                                <input type="hidden" name="action" value="delete_comment">
                                <input type="hidden" name="comment_id" value="<?= $comment->getId() ?>">
                                <input type="submit" value="Supprimer">
                            </form>
                        <?php endif; ?>
                    </div>
                    <div class="bottom">
                        <?= htmlspecialchars($comment->getContent()) ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</main>
</body>
</html>