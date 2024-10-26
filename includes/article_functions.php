<?php
require_once "includes/user_functions.php";

function add_article_database($title, $description){
    try {
        $conn = new PDO('mysql:host=localhost;dbname=blog','root','');
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $conn->prepare("INSERT INTO articles(title, content, author_id) VALUES(?,?,?)");
        $stmt->execute([$title, $description, $_SESSION["user"]['id']]);

        return $conn->lastInsertId();
    } catch (PDOException $e) {
        die ('Erreur PDO: ' . $e->getMessage());
    } catch (Exception $e) {
        die('Erreur générale: ' . $e->getMessage());
    }
    return -1;
}

function update_image_url($id, $url) {
    update_bdd($url, $id, "UPDATE articles SET image_url = ? WHERE id = ?");
}

function delete_article_database($article_id){
    try {
        $conn = new PDO('mysql:host=localhost;dbname=blog','root','');
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $conn->prepare("DELETE FROM articles WHERE id = ?");
        $stmt->execute([$article_id]);
    } catch (PDOException $e) {
        die('Erreur PDO: ' . $e->getMessage());
    } catch (Exception $e) {
        die('Erreur générale: ' . $e->getMessage());
    }
}

function is_article_admin($article_row){
    return (is_connected() && $article_row['author_id'] == $_SESSION["user"]["id"]);
}

function delete_message($comment_id){
    try {
        $conn = new PDO('mysql:host=localhost;dbname=blog','root','');
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $conn->prepare("DELETE FROM comments WHERE id = ?");
        $stmt->execute([$comment_id]);
    } catch (PDOException $e) {
        die('Erreur PDO: ' . $e->getMessage());
    } catch (Exception $e) {
        die('Erreur générale: ' . $e->getMessage());
    }
}

function add_article_category($article_id, $category_id) {
    echo 't1';
    try {
        echo 't2';
        $conn = new PDO('mysql:host=localhost;dbname=blog','root','');
        echo 't3';
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo 't3';
        $stmt = $conn->prepare("INSERT INTO article_categories(article_id, category_id) VALUES(?,?)");
        echo 't4';
        $stmt->execute([$article_id, $category_id]);
    } catch (PDOException $e) {
        die ('Erreur PDO: ' . $e->getMessage());
    } catch (Exception $e) {
        die('Erreur générale: ' . $e->getMessage());
    }
}

function get_category_id($category_name) {
    try {
        $conn = new PDO('mysql:host=localhost;dbname=blog','root','');
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $conn->prepare("SELECT id FROM categories WHERE lower(name = ?) LIMIT 1");
        $stmt->execute([$category_name]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ? $result['id'] : null;
    } catch (PDOException $e) {
        die ('Erreur PDO: ' . $e->getMessage());
    } catch (Exception $e) {
        die('Erreur générale: ' . $e->getMessage());
    }
}
?>
