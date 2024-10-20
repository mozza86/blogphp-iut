<?php

if (!empty($article_row)) {

?>

<article>
    <div class="container">
        <img src="<?= $article_row['image_url'] ?>">
        <div class="text">
            <h1><?= htmlentities($article_row['title']) ?></h1>
            <p><?= htmlentities($article_row['content']) ?></p>
            <a class="more" href="article.php?id=<?= $article_row['id'] ?>">Read More</a>
        </div>
    </div>
</article>

<?php
}
