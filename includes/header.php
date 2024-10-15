<?php

session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "blog";
$dbport = 3306;

?>

<DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        <link rel="stylesheet" href="res/css/style.css">
    </head>
    <body>
    <header class="has-container">
        <div class="container">

            <div class="text">
                <h1>Le Blog</h1>
                <p>
                    Le Blog est un blog qui traite de plusieurs sujets, mais surtout de l'informatique.<br>
                    Nos articles sont généralement écrits en anglais, cependant certains sont exclusivement écrits ou traduits dans une autre langue.
                </p>
            </div>
        </div>
    </header>
    <nav class="has-container">
        <div class="container">
            <div class="left">
                <a href="">Create post</a>
            </div>
            <div class="right">
                <a href="account.php" class="account"><?= $_SESSION['user']['username'] ?? 'Login' ?><img src="<?= $_SESSION['user']['avatar_url'] ?? 'res/img/login.png' ?> " alt="avatar"></a>
            </div>
        </div>
    </nav>
