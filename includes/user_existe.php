<?php
require_once "includes/headerfunction.php";
/*
 * if it exists:
 * - return user
 * if it does not exists:
 * - add a user
 * - return false
 */
function user_existe($email)
{
    try {
        $conn = new PDO('mysql:host=localhost;dbname=blog', 'root', '');
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = 'SELECT * FROM users where email = ?';
        $stmt = $conn->prepare($sql);
        $stmt->execute([$email]);

        $user = $stmt->fetch();
        return !!$user;

    } catch (PDOException $e) {
        echo "SQL error: " . $e->getMessage();
    }
    return false;
}

/*
 * return true if user is admin
 */
function is_admin($mail, $mdp)
{
    if ($mail = "admin@localhost.fr" && $mdp = "admin69IUT") {
        return true;
    } else {
        return false;
    }
}

/*
 * vérify that password corresponds to the user password in data base
 * return true if password corresponds
 * else return false
 */
function login($email, $password)
{
    try {
        $conn = new PDO('mysql:host=localhost;dbname=blog', 'root', '');
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = 'SELECT * FROM users where email = ?';
        $stmt = $conn->prepare($sql);
        $stmt->execute([$email]);

        $user = $stmt->fetch();
        if ($user) {
            if (password_verify($password, $user['password'])) {
                return $user;
            } else {
                return false;
            }
        }
        return false;
    } catch (PDOException $e) {
        echo "SQL error: " . $e->getMessage();
    }
}

/*
 * add user in database
 * return user login and password
 */
function creer_utilisateur($email, $password)
{

    if ((filter_var($email, FILTER_VALIDATE_EMAIL))) {
        try {
            $conn = new PDO('mysql:host=localhost;dbname=blog', 'root', '');
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $conn->prepare("INSERT INTO users(email, password) VALUES(?,?)");
            $stmt->execute([$email, password_hash($password, PASSWORD_DEFAULT)]);
        } catch (PDOException $e) {
            die ('Erreur pdo' . $e->getMessage());
        } catch (Exception $e) {
            die('Erreur général' . $e->getMessage());
        }
        return login($email, $password);
    } else {
        echo "<script>window.alert('Hello world!');</script>);";
    }
}
function update_avatar_url($user_id, $avatar_url)
{
    return update_bdd($avatar_url, $user_id, "UPDATE users SET avatar_url = ? WHERE id = ?");
}


function update_username($user_id, $username)
{
    return update_bdd($username, $user_id, "UPDATE users SET username = ? WHERE id = ?");
}

function update_description($user_id, $description)
{
    return update_bdd($description, $user_id, "UPDATE users SET description = ? WHERE id = ?");
}

function update_bdd($value1, $value2, $sql)
{
    try {
        $conn = new PDO('mysql:host=localhost;dbname=blog', 'root', '');
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $conn->prepare($sql);
        $stmt->execute([$value1, $value2]);
        return true;
    } catch (PDOException $e) {
        echo('Erreur pdo' . $e->getMessage());
    } catch (Exception $e) {
        echo('Erreur général' . $e->getMessage());
    }
    return false;
}

?>