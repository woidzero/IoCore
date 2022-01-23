<?php
include "../../include/lib/connection.php";
require_once "../../include/lib/mainLib.php";
$gs = new mainLib();
session_start();
function invalid() {
    exit("-1");
}
function success() {
    echo '<meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        * {
            font-family: Geneva, Arial, Helvetica, sans-serif;
            margin: 0;
        }
        .main {
            margin-top: 100px;
        }
        form {
            margin: 5px;
        }
    </style>
    <div class="main" align="center">
        <h1>Аккаунт активирован!</h1>
        <p>Вы можете входить!</p>
    </div>';
    exit;
}
$captcha = $_SESSION["captcha"];
$tk = htmlspecialchars($_GET["token"]);
if($tk != "") {
    $_SESSION["tk"] = $tk;
    echo "<script>location.href='activate.php'</script>";
    exit;
} else {
    if($_SESSION["tk"]) {
        $query = $db->prepare("SELECT * FROM register WHERE token = :tk");
        $query->execute([':tk' => $_SESSION["tk"]]);
        if ($query->rowCount() == 0) {
            invalid();
        }
    } else {
        invalid();
    }
}
$cap = htmlspecialchars($_POST["cap"]);

if($cap == "") {
} else {
    if($cap == $captcha) {
        $tk = $_SESSION["tk"];
        $query = $db->prepare("SELECT userName FROM register WHERE token = :tk");
        $query->execute([':tk' => $tk]);
        $userName = $query->fetchColumn();
        $query = $db->prepare("SELECT password FROM register WHERE token = :tk");
        $query->execute([':tk' => $tk]);
        $password = $query->fetchColumn();
        $query = $db->prepare("SELECT email FROM register WHERE token = :tk");
        $query->execute([':tk' => $tk]);
        $email = $query->fetchColumn();
        $query = $db->prepare("SELECT registerDate FROM register WHERE token = :tk");
        $query->execute([':tk' => $tk]);
        $registerDate = $query->fetchColumn();
        $query = $db->prepare("INSERT INTO accounts (userName, password, registerDate, email) VALUES (:userName, :password, :registerDate, :email)");
        $query->execute([':userName' => $userName, ':password' => $password, ':email' => $email, ':registerDate' => $registerDate]);
        $query = $db->prepare("DELETE FROM register WHERE token = :tk");
        $query->execute([':tk' => $tk]);
        success();
    } else {
        $err = "Неправильно.";
    }
}
?>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
    * {
        font-family: Geneva, Arial, Helvetica, sans-serif;
        margin: 0;
    }
    .main {
        margin-top: 100px;
    }
    form {
        margin: 5px;
    }
</style>
<div class="main" align="center">
    <h1>Активация аккаунта Paradox Dash!</h1>
    <p style="padding-bottom: 10px">
    <?php
    if (!$err) {
        echo "Введите текст с картинки в поле ввода.<br>";
    } else {
        echo $err;  
    }
    ?></p>
    <img style="border: 1px solid black" src="cap.php" method="post">
    <form action="activate.php?token=<?=$tk?>" method="post">
        <input style="padding-bottom: 5px" name="cap" placeholder="..." /><br />
        <input type="submit" value="verify" />
    </form>
</div>
