<?php
require_once "includes/headerfunction.php";
require_once "includes/user_existe.php";
require_once "includes/header.php";
?>
<link rel="stylesheet" href="res/css/styleform.css">
<form action="login.php" method="post">
    <div class=" formulary">
    <label>Email : </label>
    <input name="email" id="e_mail" type="email" placeholder="e-mail"/><br>

    <label>Mot de passe : </label>
    <input name="password" id="password" type="text" /><br>
    <button type="submit">Valider</button>
    </div>
</form>

<?php
if (!empty($_POST["email"]) && !empty($_POST["password"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];

    if (user_existe($email)) {
        $user = login($email, $password);
        if ($user) {
            echo "utilisateur connecté";
        } else {
            echo "mot de passe invalide!";
        }
    } else {
        $user = creer_utilisateur($email, $password);
        echo "utilisateur créé";
    }
    $_SESSION['user'] = $user;
    header('Location: account.php');
}

?>
