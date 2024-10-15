<?php
require_once "includes/header.php";
?>
    <div class="articles">
        <?php

        for ($i = 1; $i <= 10; $i++) {
            $article = array(
                    'number' => $i
            );
            include "includes/cartearticle.php";
        }
        ?>
    </div>
<?php
require_once "includes/footer.php";
?>
