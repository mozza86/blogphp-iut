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
        die ('Erreur pdo' . $e->getMessage());
    } catch (Exception $e) {
        die('Erreur général' . $e->getMessage());
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
        $stmt = $conn->prepare("DELETE FROM articles where id = ?");
        $stmt->execute([$article_id]);
    } catch (PDOException $e) {
        die('Erreur pdo' . $e->getMessage());
    } catch (Exception $e) {
        die('Erreur général' . $e->getMessage());
    }
}

function is_article_admin($article_row){
    return (is_connected() && $article_row['author_id'] == $_SESSION["user"]["id"]);
}

?>