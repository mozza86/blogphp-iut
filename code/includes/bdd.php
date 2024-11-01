<?php
function get_bdd_connection(): PDO {
    $DB_HOST = $_ENV['DB_HOST'] ?? 'localhost';
    $DB_USER = $_ENV['DB_USER'] ?? 'root';
    $DB_PASS = $_ENV['DB_PASS'] ?? '';
    $DB_NAME = $_ENV['DB_NAME'] ?? 'blog';

    try {
        $conn = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME", $DB_USER, $DB_PASS);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch (PDOException $e) {
       die("Connection failed: " . $e->getMessage());
    }
}