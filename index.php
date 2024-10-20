<?php
require_once "includes/headerfunction.php";
require_once "includes/header.php";
require_once "includes/realheader.php";
require_once "includes/nav.php";
?>

<main class="home">
    <?php

    try {
        $conn = new PDO('mysql:host=localhost;dbname=blog','root','');
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql =  'SELECT * FROM articles order by created_at desc limit 25';
        $values = $conn->query($sql);
    } catch(PDOException $e) {
        echo "SQL error: ".$e->getMessage();
    }
    include "includes/filter.php";

    $conn = null;
    foreach  ($values as $row) {
        $article_row = $row;

        include "includes/cartearticle.php";
    }
    ?>
</main>
<?php
require_once "includes/footer.php";
?>