<?php
$userName = $_GET['username'];
include "../include/lib/connection.php";

$ifexists = $db->prepare("SELECT count(*) FROM users WHERE userName=:userName");
$ifexists->execute(["userName"=>$userName]);
if($ifexists->fetchColumn()==0){exit();}

$getuserdata = $db->prepare("SELECT * FROM users WHERE userName=:userName");
$getuserdata->execute(["userName"=>$userName]);

$getaccdata = $db->prepare("SELECT * FROM accounts WHERE userName=:userName");
$getaccdata->execute(["userName"=>$userName]);

$rowx = $getaccdata->fetchAll(PDO::FETCH_ASSOC)[0];
$row = $getuserdata->fetchAll(PDO::FETCH_ASSOC)[0];

$stars = $row["stars"];
$diamonds = $row["diamonds"];
$demons = $row["demons"];
$cp = $row["creatorPoints"];
$icon = $row["accIcon"];
$color1 = $row["color1"];
$color2 = $row["color2"];
$accountID = $row["extID"];
$userID = $row["userID"];
$time = $row["lastPlayed"];
$ucoins = $row["userCoins"];
$coins = $row["coins"];

$ms = $rowx["mS"];
$frs = $rowx["frS"];
$cs = $rowx["cS"];

$regdate = $rowx["registerDate"];
$yt = $rowx["youtubeurl"];
$tw = $rowx["twitter"];
$th = $rowx["twitch"];

$generatedicon = "https://gdbrowser.com/icon/icon?icon=".$icon."&form=cube&col1=".$color1."&col2=".$color2;

$ifmod = $db->prepare("SELECT count(*) FROM roleassign WHERE accountID=".$accountID);
$ifmod->execute([]);

if($ifmod->fetchColumn()!=0) {
$checkifmod = $db->prepare("SELECT roleID FROM roleassign WHERE accountID=".$accountID);
$checkifmod->execute([]);
$roleID = $checkifmod->fetchColumn();
$whatkindamod = $db->prepare("SELECT roleName FROM roles WHERE roleID=:roleID");
$whatkindamod->execute(["roleID"=>$roleID]);
$roleName = $whatkindamod->fetchColumn();

$true = "True";
$mod = array(
    "isMod"=>$true,
    "roleName"=>$roleName);
} else {
    $mod = array("isMod"=>"False", "roleName"=>"None");
}

$data = array(
"user_name"=>$userName,
"stars"=>$stars,
"diamonds"=>$diamonds,
"demons"=>$demons,
"creatorPoints"=>$cp,
"icon"=>$icon,
"color1"=>$color1,
"color2"=>$color2,
"accountID"=>$accountID,
"time"=>$time,
"userID"=>$userID,
"iconSprite"=>$generatedicon,
"ucoins"=>$ucoins,
"coins"=>$coins,
"regdate"=>$regdate,
"yt"=>$yt,
"tw"=>$tw,
"th"=>$th,
"ms"=>$ms,
"frs"=>$frs,
"cs"=>$cs,
"isMod"=>$mod["isMod"],
"role"=>$mod["roleName"]
);

echo json_encode($data);
?>