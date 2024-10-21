<?php

require_once "includes/user_existe.php";
function add_article_database($title, $description){
    try {
        $conn = new PDO('mysql:host=localhost;dbname=blog','root','');
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $conn->prepare("INSERT INTO articles(title, content, author_id) VALUES(?,?,?)");
        $stmt->execute([$title, $description, $_SESSION["user"]['id']]);

        return $conn->lastInsertId();
    } catch (PDOException $e){
        echo ('Erreur pdo'.$e->getMessage());
    } catch (Exception $e){
        echo ('Erreur général'.$e->getMessage());
    }
    return -1;
}

function update_image_url($id, $url) {
        update_bdd($url, $id, "UPDATE articles SET image_url = ? WHERE id = ?");
}

function delete_article_database($title){
    {
        $bdd = new PDO('mysql:host=localhost;dbname=rhtab;charset=utf8', 'root', '');
        $sql='DELETE FROM articles WHERE title = '.$title.'';
        $req = $bdd ->prepare($sql);
        $r = $req->execute();
        //header("location:Toutabsences.php");
    }

}

?>