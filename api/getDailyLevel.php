<?php
include "../include/lib/connection.php";

$getleveldata = $db->prepare(
    "SELECT * FROM levels 
    WHERE levelID IN
        (SELECT levelID FROM dailyfeatures GROUP BY levelID) 
    ORDER BY levelID"
);

$getdailydata = $db->prepare("SELECT * FROM dailyfeatures");

$getleveldata->execute(["levels"=>$levels]);
$getdailydata->execute(["dailyfeatures"=>$dailyfeatures]);

$row = $getleveldata->fetchAll(PDO::FETCH_ASSOC)[0];
$rowx = $getdailydata->fetchAll(PDO::FETCH_ASSOC)[0];

$feaid = $rowx["feaID"];
$author = $row["userName"];
$levelid = $row["levelID"];
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
$type = $rowx["type"];
$desc_encoded = base64_decode($desc);

$data = array(
    "feaid"=>$feaid,
    "author"=>$author,
    "levelid"=>$levelid,
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
    "type"=>$type,
    "desc_encoded"=>$desc_encoded
);

echo json_encode($data);
?>