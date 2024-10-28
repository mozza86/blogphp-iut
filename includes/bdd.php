<?php
function get_bdd_connection(): PDO {
    $conn = new PDO('mysql:host=localhost;dbname=blog', 'root', '');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $conn;
}