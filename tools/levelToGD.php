<?php
function chkarray($source){
	if($source == ""){
		$target = "0";
	}else{
		$target = $source;
	}
	return $target;
}

include "../include/lib/connection.php";
include "../config/name.php";
require "../include/lib/XORCipher.php";

require_once "../include/lib/generateHash.php";
require_once "../include/lib/generatePass.php";
require_once "../include/lib/exploitPatch.php";

$generatePass = new generatePass();
$xc = new XORCipher();
$ep = new exploitPatch();
$gh = new generateHash();

if(!empty($_POST["userhere"]) AND !empty($_POST["passhere"]) AND !empty($_POST["usertarg"]) AND !empty($_POST["passtarg"]) AND !empty($_POST["levelID"])){
	$userhere = $ep->remove($_POST["userhere"]);
	$passhere = $ep->remove($_POST["passhere"]);
	$usertarg = $ep->remove($_POST["usertarg"]);
	$passtarg = $ep->remove($_POST["passtarg"]);
	$levelID = $ep->remove($_POST["levelID"]);
	$server = trim($_POST["server"]);

	$pass = $generatePass->isValidUsrname($userhere, $passhere);

	if ($pass != 1) {
		$log = "Wrong local username/password combination";
		exit();
	}

	$query = $db->prepare("SELECT * FROM levels WHERE levelID = :level");
	$query->execute([':level' => $levelID]);
	$levelInfo = $query->fetch();
	$userID = $levelInfo["userID"];
	$query = $db->prepare("SELECT accountID FROM accounts WHERE userName = :user");
	$query->execute([':user' => $userhere]);
	$accountID = $query->fetchColumn();
	$query = $db->prepare("SELECT userID FROM users WHERE extID = :ext");
	$query->execute([':ext' => $accountID]);

	if($query->fetchColumn() != $userID){
		$log = "This level doesn't belong to the account you're trying to reupload from";
	}

	$udid = "S" . mt_rand(111111111,999999999) . mt_rand(111111111,999999999) . mt_rand(111111111,999999999) . mt_rand(111111111,999999999) . mt_rand(1,9); //getting accountid
	$sid = mt_rand(111111111,999999999) . mt_rand(11111111,99999999);
	$post = ['userName' => $usertarg, 'udid' => $udid, 'password' => $passtarg, 'sID' => $sid, 'secret' => 'Wmfv3899gc9'];
	$ch = curl_init($server . "/accounts/loginGJAccount.php");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	$result = curl_exec($ch);
	curl_close($ch);
	if($result == "" OR $result == "-1" OR $result == "No no no"){
		if ($result=="") {
			$log =  "An error has occured while connecting to the login server.";
		} elseif($result=="-1") {
			$log =  "Login to the target server failed.";
		}else{
			$log =  "RobTop doesn't like you or something...";
		}

		$log = "Error code: $result";
	}
	if(!is_numeric($levelID)){
		$log = "Invalid levelID, ( exploit :3? )";
		exit();
	}

	$levelString = file_get_contents("../data/levels/$levelID"); //generating seed2
	$seed2 = base64_encode($xc->cipher($gh->genSeed2noXor($levelString),41274));
	$accountID = explode(",",$result)[0]; //and finally reuploading
	$gjp = base64_encode($xc->cipher($passtarg,37526));
	$post = ['gameVersion' => $levelInfo["gameVersion"], 
	'binaryVersion' => $levelInfo["binaryVersion"], 
	'gdw' => "0", 
	'accountID' => $accountID, 
	'gjp' => $gjp,
	'userName' => $usertarg,
	'levelID' => "0",
	'levelName' => $levelInfo["levelName"],
	'levelDesc' => $levelInfo["levelDesc"],
	'levelVersion' => $levelInfo["levelVersion"],
	'levelLength' => $levelInfo["levelLength"],
	'audioTrack' => $levelInfo["audioTrack"],
	'auto' => $levelInfo["auto"],
	'password' => $levelInfo["password"],
	'original' => "0",
	'twoPlayer' => $levelInfo["twoPlayer"],
	'songID' => $levelInfo["songID"],
	'objects' => $levelInfo["objects"],
	'coins' => $levelInfo["coins"],
	'requestedStars' => $levelInfo["requestedStars"],
	'unlisted' => "0",
	'wt' => "0",
	'wt2' => "3",
	'extraString' => $levelInfo["extraString"],
	'seed' => "v2R5VPi53f",
	'seed2' => $seed2,
	'levelString' => $levelString,
	'levelInfo' => $levelInfo["levelInfo"],
	'secret' => "Wmfd2893gb7"];

	if($_POST["debug"] == 1){
		var_dump($post);
	}

	$ch = curl_init($server . "/uploadGJLevel21.php");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	$result = curl_exec($ch);
	curl_close($ch);
	if($result == "" OR $result == "-1" OR $result == "No no no"){
		if($result==""){
			$log =  "An error has occured while connecting to the target server.";
		} elseif ($result=="-1"){
			$log =  "Reuploading level failed.";
		} else {
			$log =  "RobTop doesn't like you or something...";
		}
		$log = "Error code: $result";
	}
	$log = "Level reuploaded: <b>$result</b>";
}
?>
<html>
	<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, maximum-scale=1.0, user-scalable=no">
	<link href="../include/components/css/styles.css" rel="stylesheet">
	<link href="../include/components/images/tools_favicon.png" rel="shortcut icon">
	<title>Level To GD</title>
	</head>

	<body>
		<main id="tools">
			<h1>Level To GD</h1>
			<section id="toolbox" style="height: 45rem; width: 50rem;">
			<form class="form" method="post" action="levelToGD.php">
					<h3 style="padding: 15px;"><?php echo $gdpsname ?></h3>
					Username: <input type="text" name="userhere"><br>
					Password: <input type="text" name="passhere"><br>
					Level ID: <input type="text" name="levelID"><br>

					<h3>Target Server</h3>
					Username: <input type="text" name="usertarg"><br>
					Password: <input type="password" name="passtarg"><br>
					URL: <input type="text" name="server" value="http://www.boomlings.com/database/"><br>
					Unlisted (0=false, 1=true): <input type="text" name="debug" value="0"><br>

					<input class="button" type="submit" value="Reupload">
				</form>

				<div id="toolbox__log">
					<p><?php echo $log ?></p>
				</div>
			</section>
		</main>
		<footer>Provided by <span><a href="https://github.com/WoidZero/IoCore">IoCore</a></span> / Developed by <a href="https://github.com/WoidZero">WoidZero</a></footer>
	</body>
</html>