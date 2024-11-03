<?php
global $e;
if (empty($__PAGE_PREFIX)) {
    $__PAGE_PREFIX = './';
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Blog - Erreur</title>
    <link rel="stylesheet" href="<?= $__PAGE_PREFIX ?>res/css/style.css">
</head>
<body>
<?php require_once "$__PAGE_PREFIX/includes/header.php"; ?>
<main class="error_page">
    <p><?= $error_msg??'Erreur inconnue' ?></p>
    <?php
    if (!empty($_GET['debug']) && $_GET['debug'] == 'true') {
        var_dump($e??null);
    }
    ?>
</main>
</body>
</html>

<?php
die;