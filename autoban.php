<?php
include "../../include/lib/connection.php";

$stars = 210;
$coins = 63;
$pc = 0;
$demons = 3;

ob_flush();
flush();
$query = $db->prepare("SELECT COUNT(starDemon) FROM levels WHERE starDemon = 1");
$query->execute();
$demons = $demons + $query->fetchColumn();

$query = $db->prepare("SELECT * FROM dailyfeatures");
$query->execute();
$result = $query->fetchAll();
foreach($result as $a){
	$querys = $db->prepare("SELECT starStars FROM levels WHERE levelID = ".$a["levelID"]);
	$querys->execute();
	$stars = $stars + $querys->fetchColumn();
}

$query = $db->prepare("SELECT SUM(stars) FROM mappacks");
$query->execute();
$stars = $stars + $query->fetchColumn();

$query = $db->prepare("SELECT SUM(starStars) FROM levels");
$query->execute();
$stars = $stars + $query->fetchColumn();
$query = $db->prepare("UPDATE users SET isBanned = 1 WHERE stars > $stars");
$query->execute();

$query = $db->prepare("SELECT SUM(coins) FROM mappacks");
$query->execute();
$coins = $coins + $query->fetchColumn();

$query = $db->prepare("SELECT SUM(coins) FROM levels");
$query->execute();
$pc = $query->fetchColumn();


$query = $db->prepare("SELECT * FROM gauntlets");
$query->execute();
$result = $query->fetchAll();
foreach($result as $a){
	$querys = $db->prepare("SELECT starStars FROM levels WHERE levelID = ".$a["level1"]);
	$querys->execute();
	$stars = $stars + $querys->fetchColumn();
}


$query = $db->prepare("SELECT * FROM gauntlets");
$query->execute();
$result = $query->fetchAll();
foreach($result as $a){
	$querys = $db->prepare("SELECT starStars FROM levels WHERE levelID = ".$a["level5"]);
	$querys->execute();
	$stars = $stars + $querys->fetchColumn();
}

$query = $db->prepare("SELECT * FROM gauntlets");
$query->execute();
$result = $query->fetchAll();
foreach($result as $a){
	$querys = $db->prepare("SELECT starStars FROM levels WHERE levelID = ".$a["level4"]);
	$querys->execute();
	$stars = $stars + $querys->fetchColumn();
}

$query = $db->prepare("SELECT * FROM gauntlets");
$query->execute();
$result = $query->fetchAll();
foreach($result as $a){
	$querys = $db->prepare("SELECT starStars FROM levels WHERE levelID = ".$a["level3"]);
	$querys->execute();
	$stars = $stars + $querys->fetchColumn();
}

$query = $db->prepare("SELECT * FROM gauntlets");
$query->execute();
$result = $query->fetchAll();
foreach($result as $a){
	$querys = $db->prepare("SELECT starStars FROM levels WHERE levelID = ".$a["level2"]);
	$querys->execute();
	$stars = $stars + $querys->fetchColumn();
}

$query = $db->prepare("UPDATE users SET isBanned = 1 WHERE stars > :star OR coins > :coin OR userCoins > :pc");
$query->execute(array(":star" => $stars, ":coin" => $coins, ":pc" => $pc));
$query = $db->prepare("UPDATE users SET isBanned = 1 WHERE stars < 0 OR coins < 0 OR userCoins < 0");
$query->execute();
echo "1";
ob_flush();
flush();
?>

