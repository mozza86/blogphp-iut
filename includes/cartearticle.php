<?php

if (!empty($article_row)) {

?>

<article class="has-container">
    <div class="container">
        <img src="<?= $article_row['image_url'] ?>" alt="">
        <div class="text">
            <h1><?= htmlentities($article_row['title']) ?></h1>
            </button>
            <p><?= htmlentities($article_row['content']) ?></p>
            <a href="article.php?id=<?= $article_row['id'] ?>" class="more">Read More</a>
        </div>
    </div>
</article>
<?php
}
