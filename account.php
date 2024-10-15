<?php
require_once "includes/header.php";

if (empty($_SESSION['user'])) {
    header('Location: login.php');
    die;
}

if(isset($_FILES['image'])){

}
?>

<div>
    <form name="account" method="post" action="">
        <label> Username :</label>
        <input type="text" name="username" id="username"> <br/>
        <label> Avatar :</label>
        <input type="file" name="avatar" id="avatar" accept="image/*"> <br/>

        <label> Description</label>
        <input type="text" name="description" id="description"> <br/>

        <input type="submit">
    </form>
</div>


<?php
require_once "includes/footer.php";