<?php

session_start();

function get_user_data($user_id) {
    try {
        $conn = new PDO('mysql:host=localhost;dbname=blog','root','');
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = 'SELECT * FROM users where id = ?';
        $stmt= $conn->prepare($sql);
        $stmt->execute([ $user_id ]);

        $user = $stmt->fetch();
        return $user;
    } catch(PDOException $e) {
        echo "SQL error: ".$e->getMessage();
    }
    return false;
}

if (!empty($_SESSION['user']['id'])) {
    $_SESSION['user'] = get_user_data($_SESSION['user']['id']);
}

?>