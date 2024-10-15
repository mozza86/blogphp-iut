<?php
    require_once "includes/header.php";
    require_once "includes/user_existe.php";
?>

<form action="login.php" method="post">
    <label>Email : </label>
    <input name="email" id="e_mail" type="email" placeholder="e-mail"/><br>

    <label>Mot de passe : </label>
    <input name="password" id="password" type="text" /><br>
    <button type="submit">Valider</button>
</form>

<?php
if (!empty($_POST["email"]) && !empty($_POST["password"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];

    if (!user_existe($email)) {
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
