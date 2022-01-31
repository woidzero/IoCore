<?php
	include "../config/name.php";
	include "../include/lib/connection.php";
	require_once "../include/lib/generatePass.php";
	require_once "../include/lib/exploitPatch.php";

	$generatePass = new generatePass();
	$ep = new exploitPatch();

	if(!empty($_POST["userhere"]) AND !empty($_POST["passhere"]) AND !empty($_POST["usertarg"]) AND !empty($_POST["passtarg"])){
		$userhere = $ep->remove($_POST["userhere"]);
		$passhere = $ep->remove($_POST["passhere"]);
		$usertarg = $ep->remove($_POST["usertarg"]);
		$passtarg = $ep->remove($_POST["passtarg"]);
		$url = $_POST["server"];

		$pass = $generatePass->isValidUsrname($userhere, $passhere);
		//echo $pass;
		if ($pass == 1) {
			
			$udid = "S" . mt_rand(111111111,999999999) . mt_rand(111111111,999999999) . mt_rand(111111111,999999999) . mt_rand(111111111,999999999) . mt_rand(1,9);
			$sid = mt_rand(111111111,999999999) . mt_rand(11111111,99999999);

			$post = ['userName' => $usertarg, 'udid' => $udid, 'password' => $passtarg, 'sID' => $sid, 'secret' => 'Wmfv3899gc9'];
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			$result = curl_exec($ch);
			curl_close($ch);
			if($result == "" OR $result == "-1" OR $result == "No no no"){
				if($result==""){
					$log =  "An error has occured while connecting to the server.";
				}else if($result=="-1"){
					$log =  "Login to the target server failed.";
				}else{
					$log =  "RobTop doesn't like you or something...";
				}
				$log =  "Error code: $result";
			}else{
				if($_POST["debug"] == 1){
					echo "$result";
				}
				$parsedurl = parse_url($url);
				if($parsedurl["host"] == $_SERVER['SERVER_NAME']){
					$log = "You can't link 2 accounts on the same server.";
					exit();
				}
				//getting stuff
				$query = $db->prepare("SELECT accountID FROM accounts WHERE userName = :userName LIMIT 1");
				$query->execute([':userName' => $userhere]);
				$accountID = $query->fetchColumn();
				$query = $db->prepare("SELECT userID FROM users WHERE extID = :extID LIMIT 1");
				$query->execute([':extID' => $accountID]);
				$userID = $query->fetchColumn();
				$targetAccountID = explode(",",$result)[0];
				$targetUserID = explode(",",$result)[1];
				$query = $db->prepare("SELECT count(*) FROM links WHERE targetAccountID = :targetAccountID LIMIT 1");
				$query->execute([':targetAccountID' => $targetAccountID]);
				if($query->fetchColumn() != 0){
					$log = "The target account is linked to an account already.";
					exit();
				}

				if(!is_numeric($targetAccountID) OR !is_numeric($accountID)){
					$log = "Invalid AccountID found";
					exit();
				}
				$server = $parsedurl["host"];
				//query
				$query = $db->prepare("INSERT INTO links (accountID, targetAccountID, server, timestamp, userID, targetUserID)
												VALUES (:accountID,:targetAccountID,:server,:timestamp,:userID,:targetUserID)");
				$query->execute([':accountID' => $accountID, ':targetAccountID' => $targetAccountID, ':server' => $server, ':timestamp' => time(), 'userID' => $userID, 'targetUserID' => $targetUserID]);
				$log =  "Account linked succesfully.";
			}
		}else{
			$log = "Invalid local username/password combination.";
		}
	}
?>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, maximum-scale=1.0, user-scalable=no">
		<link href="../include/components/css/styles.css" rel="stylesheet">
		<link href="../include/components/images/tools_favicon.png" rel="shortcut icon">
		<title>Link Account</title>
	</head>

	<body>
		<main id="tools">
			<h1>Link Account</h1>
			<section id="toolbox" style="height: 40rem; width: 50rem;">
				<form class="form" method="post" action="linkAccount.php">
					<h3 style="padding: 15px;"><?php echo $gdpsname ?></h3>
					Username: <input type="text" name="userhere"><br>
					Password: <input type="text" name="passhere"><br>

					<h3>Target Server</h3>
					Username: <input type="text" name="usertarg"><br>
					Password: <input type="password" name="passtarg"><br>
					URL: <input type="text" name="server" value="http://www.boomlings.com/database/accounts/loginGJAccount.php"><br>
					
					<input class="button" type="submit" value="Link">
				</form>

				<div id="toolbox__log">
					<p><?php echo $log ?></p>
				</div>
			</section>
		</main>
		<footer>Provided by <span><a href="https://github.com/WoidZero/IoCore">IoCore</a></span> / Developed by <a href="https://github.com/WoidZero">WoidZero</a></footer>
	</body>
</html>