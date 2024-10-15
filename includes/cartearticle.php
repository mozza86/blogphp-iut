<?php

if (!empty($article)) {

?>

<article class="has-container">
    <div class="container">
        <img src="res/img/thumbnails/2022-03-01_20-36.png" alt="">
        <div class="text">
            <h1>Un article <?= $article['number'] ?></h1>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Distinctio excepturi, magnam commodi atque provident soluta corrupti ad fugit minus explicabo aperiam quis saepe dolore ullam culpa repellendus delectus odio expedita!</p>
            <a href="" class="more">Lire plus</a>
        </div>
    </div>
</article>
<?php
}
