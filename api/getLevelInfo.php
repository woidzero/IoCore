<?php
$levelID = $_GET['levelid'];
include "../include/lib/connection.php";

$ifexists = $db->prepare("SELECT count(*) FROM levels WHERE levelID=:levelID");
$ifexists->execute(["levelID"=>$levelID]);
if($ifexists->fetchColumn()==0){exit("-4");}

$getleveldata = $db->prepare("SELECT * FROM levels WHERE levelID=:levelID");
$getleveldata->execute(["levelID"=>$levelID]);

$row = $getleveldata->fetchAll(PDO::FETCH_ASSOC)[0];

$author = $row["userName"];
$id = $row["levelID"];
$name = $row["levelName"];
$desc = $row["levelDesc"];
$version = $row["levelVersion"];
$song = $row["songID"];
$objects = $row["objects"];
$coins = $row["coins"];
$downloads = $row["downloads"];
$likes = $row["likes"];
$ratedate = $row["rateDate"];
$difficulty = $row["starDifficulty"];
$demon = $row["starDemon"];
$date = $row["uploadDate"];
$length = $row["levelLength"];
$desc_encoded = base64_decode($desc);
$unlisted = $row["unlisted"];

$data = array(
"author"=>$author,
"id"=>$id,
"name"=>$name,
"desc"=>$desc,
"version"=>$version,
"song"=>$song,
"objects"=>$objects,
"downloads"=>$downloads,
"likes"=>$likes,
"ratedate"=>$ratedate,
"difficulty"=>$difficulty,
"demon"=>$demon,
"coins"=>$coins,
"date"=>$date,
"length"=>$length,
"desc_encoded"=>$desc_encoded,
"unlisted"=>$unlisted);

echo json_encode($data);
?>