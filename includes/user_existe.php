<?php
require_once "includes/header.php";
/*
 * if it exists:
 * - return user
 * if it does not exists:
 * - add a user
 *  * - return false
 *
 */
function user_existe($email){
    try {
        $conn = new PDO('mysql:host=localhost;dbname=blog','root','');
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = 'SELECT * FROM users where email = ?';
        $stmt= $conn->prepare($sql);
        $stmt->execute([ $email ]);

        $user = $stmt->fetch();
        return false;
    } catch(PDOException $e) {
        echo "SQL error: ".$e->getMessage();
    }
    return !$user;

}
/*
 * return true if user is admin
 */
function is_admin($mail, $mdp){
    if ($mail ="admin@localhost.fr" && $mdp ="admin69IUT"){
        return true;
    } else {
        return false;
    }
}

function login($email, $password){
    try {
        $conn = new PDO('mysql:host=localhost;dbname=blog','root','');
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = 'SELECT * FROM users where email = ?';
        $stmt= $conn->prepare($sql);
        $stmt->execute([ $email ]);

        $user = $stmt->fetch();
        var_dump($user, $password, password_verify($password, $user['password']));
        if ($user) {
            if (password_verify($password, $user['password'])) {
                return $user;
            } else {
                return false;
            }
        }
        return false;
    } catch(PDOException $e) {
        echo "SQL error: ".$e->getMessage();
    }
}

function creer_utilisateur($email, $password){
    try {
        $conn =  new PDO('mysql:host=localhost;dbname=blog','root','');
        $conn -> setAttribute(PDO::ATTR_ERRMODE , PDO::ERRMODE_EXCEPTION);
        $stmt = $conn->prepare("INSERT INTO users(email, password) VALUES(?,?)");
        $stmt->execute([$email, password_hash($password, PASSWORD_DEFAULT) ]);
    } catch (PDOException $e){
        die ('Erreur pdo'.$e->getMessage());
    } catch (Exception $e){
        die('Erreur général'.$e->getMessage());
    }
    return login($email, $password);
}

?>