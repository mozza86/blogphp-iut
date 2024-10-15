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
        <input type="file" name="avatar" id="avatar"> <br/>

        <label> Description</label>
        <input type="text" name="description" id="description"> <br/>
        <label> Year : </label>
        <input type="number" name="year" id="year"> <br/>
        <label> Place </label>
        <input type="text" name="place" id="place"> <br/>
    </form>
</div>


<?php
require_once "includes/footer.php";