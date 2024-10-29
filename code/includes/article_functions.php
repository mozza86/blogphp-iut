<?php
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

?>
