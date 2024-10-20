<?php
require_once "includes/headerfunction.php";

require_once "includes/user_functions.php";

if (empty($_SESSION['user'])) {
    header('Location: login.php');
    die;
}
if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: login.php');
}
if(!empty($_FILES['avatar'])){
    $upload = 'uploads/';
    $uploadFile = $upload . $_SESSION['user']['id'] . '.png';

    if (move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadFile)) {
        if(update_avatar_url($_SESSION['user']['id'], $uploadFile)) {
            $_SESSION['user']['avatar_url'] = $uploadFile;
        }
    }
}

if (!empty($_POST['username'])) {
    $username = htmlentities($_POST['username']);
    if (update_username($_SESSION['user']['id'], $username) ) {
        $_SESSION['user']['username'] = $username;
    } else {
        $_SESSION['user']['username'] = $_SESSION['user']['email'];
    }
}
if (!empty($_POST['description'])) {
    $description = htmlentities($_POST['description']);
    if (update_description($_SESSION['user']['id'], $description)) {
        $_SESSION['user']['description'] = $description;
    }
}

require_once "includes/header.php";
?>

<div class="container">
    <div class="home">
        <img src="<?= $_SESSION['user']['avatar_url'] ?>">
        <h1><?= $_SESSION['user']['username'] ?></h1>
        <p><?= $_SESSION['user']['description'] ?></p>
    </div>
</div>

<?php require_once "includes/nav.php"; ?>

<main>
    <div class="container">
        <form method="post" action="account.php" enctype="multipart/form-data">
            <label> Username :</label>
            <input type="text" name="username" id="username"> <br/>

            <label> Avatar :</label>
            <input type="file" name="avatar" id="avatar" accept="image/*"> <br/>

            <label> Description</label>
            <input type="text" name="description" id="description"> <br/>

            <input type="submit">
        </form>
        <form method="post" action="">
            <input type="submit" value="logout" name="logout">
        </form>

    </div>
</main>
<?php require_once "includes/footer.php";
