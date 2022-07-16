<?php
error_reporting(0);
include "../../include/lib/connection.php";
require_once "../../include/lib/mainLib.php";
$gs = new mainLib();
session_start();


function success() {
    echo '<meta name="viewport" content="width=device-width, initial-scale=1">
    <div class="main" align="center">
        <h1>Аккаунт активирован!</h1>br>
        <p>Вы можете входить!</p>
    </div>';
    exit;
}

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
        
    }
}

$cap = htmlspecialchars($_POST["cap"]);
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
?>
