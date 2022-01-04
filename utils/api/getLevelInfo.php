<?php
$levelID = $_GET['levelid'];
include "../database/incl/lib/connection.php";

$ifexists = $db->prepare("SELECT count(*) FROM levels WHERE levelID=:levelID");
$ifexists->execute(["levelID"=>$levelID]);
if($ifexists->fetchColumn()==0){exit("<center><h1>Запрещено</h1><hr><h4>пошел нахуй</h4></center>");}

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
$dwls = $row["downloads"];
$likes = $row["likes"];
$ratedate = $row["rateDate"];
$difficulty = $row["starDifficulty"];
$demon = $row["starDemon"];
$date = $row["uploadDate"];
$length = $row["levelLength"];
$unlisted = $row["unlisted"];

$descd = base64_decode($desc);

$main_array = array(
"author"=>$author,
"id"=>$id,
"name"=>$name,
"desc"=>$desc,
"version"=>$version,
"song"=>$song,
"objects"=>$objects,
"dwls"=>$dwls,
"likes"=>$likes,
"ratedate"=>$ratedate,
"difficulty"=>$difficulty,
"demon"=>$demon,
"coins"=>$coins,
"date"=>$date,
"length"=>$length,
"descd"=>$descd,
"unlisted"=>$unlisted);

echo json_encode($main_array);
?>