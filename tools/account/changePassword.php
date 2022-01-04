<?php
include "../../include/lib/connection.php";
include_once "../../settings/security.php";
require "../../include/lib/generatePass.php";
require_once "../../include/lib/exploitPatch.php";
include_once "../../include/lib/defuse-crypto.phar";
use Defuse\Crypto\KeyProtectedByPassword;
use Defuse\Crypto\Crypto;
use Defuse\Crypto\Key;

$ep = new exploitPatch();
$userName = $ep->remove($_POST["userName"]);
$oldpass = $_POST["oldpassword"];
$newpass = $_POST["newpassword"];
$salt = "";
if($userName != "" AND $newpass != "" AND $oldpass != ""){
$generatePass = new generatePass();
$pass = $generatePass->isValidUsrname($userName, $oldpass);
if ($pass == 1) {
	if($cloudSaveEncryption == 1){
		$query = $db->prepare("SELECT accountID FROM accounts WHERE userName=:userName");	
		$query->execute([':userName' => $userName]);
		$accountID = $query->fetchColumn();
		$saveData = file_get_contents("../../data/accounts/$accountID");
		if(file_exists("../../data/accounts/keys/$accountID")){
			$protected_key_encoded = file_get_contents("../../data/accounts/keys/$accountID");
			$protected_key = KeyProtectedByPassword::loadFromAsciiSafeString($protected_key_encoded);
			$user_key = $protected_key->unlockKey($oldpass);
			try {
				$saveData = Crypto::decrypt($saveData, $user_key);
			} catch (Defuse\Crypto\Exception\WrongKeyOrModifiedCiphertextException $ex) {
				exit("-2");	
			}
			$protected_key = KeyProtectedByPassword::createRandomPasswordProtectedKey($newpass);
			$protected_key_encoded = $protected_key->saveToAsciiSafeString();
			$user_key = $protected_key->unlockKey($newpass);
			$saveData = Crypto::encrypt($saveData, $user_key);
			file_put_contents("../../data/accounts/$accountID",$saveData);
			file_put_contents("../../data/accounts/keys/$accountID",$protected_key_encoded);
		}
	}
	//creating pass hash
	$passhash = password_hash($newpass, PASSWORD_DEFAULT);
	$query = $db->prepare("UPDATE accounts SET password=:password, salt=:salt WHERE userName=:userName");	
	$query->execute([':password' => $passhash, ':userName' => $userName, ':salt' => $salt]);
	echo "Password changed. <a href='..'>Go back to tools</a>";
}else{
	echo "<h4 class='error'>Invalid old password or non-existent account. <a href='changePassword.php'>Try again</a></h4>";
}
}else{
}
?>
<head>
	<title>Change Password</title>
</head>

<body><br><br>
	<div class="form-modal">
		<div class="logo-container">
			<svg class="logo" width="94.4px" height="56px">
				<g><polygon points="49.3,56 49.3,0 0,28 	" /><path d="M53.7,3.6v46.3l40.7-23.2L53.7,3.6z M57.7,10.6l28.4,16.2L57.7,42.9V10.6z" /></g>
			</svg>
		</div>
		<form class="details" method="post" action="changePassword.php">
			<div class="input-container">
				<input class="col-sm-5 col-sm-push-2 in-input with-placeholder" name="userName" type="text" placeholder="Username"/>
			</div>
			<div class="input-container">
				<input class="col-sm-5 col-sm-push-2 in-input with-placeholder" name="oldpassword" type="password" placeholder="Old Password" />
			</div>
			<div class="input-container">
				<input class="col-sm-5 col-sm-push-2 in-input with-placeholder" name="newpassword" type="password"  placeholder="New Password"/>
			</div>
			<button type="submit">Change</button>
		</form>
	</div>
</body>

<style>
@import url('https://fonts.googleapis.com/css2?family=Titillium+Web:wght@600&display=swap');
body { background-color: #1f2029;}
.logo { fill: #FEFEFE; }

.logo-container {
	width: 100%;
	margin-top: 50px;
	position: relative;
	text-align: center;
}

.form-modal {
		font-family: 'Titillium Web', sans-serif;
		color: #ffeba7;
		border-color: rgb(254, 254, 254);
		border-radius: 10px;
		background-color: #2a2b38;
		width: 90%;
		min-width: 400px;
		max-width: 700px;
		margin: 0 auto;
		padding: 25px;
}

form {
		margin: 0 auto;
		text-align: center;
}

input::-webkit-input-placeholder {
		font-size: 15px;
		color: #ffeba7;
		opacity: 1;
}

.form-modal .input-container { margin: 10px; }
* { outline: 0; }

input {
	font-weight: 700;
	font-size: 1.4em;
	padding: 10px;
	border-width: 2px;
	border-color: rgba(247, 247, 247, .3);
	border-style: solid;
	background: transparent;
}

input:focus {
	background: white;
	transition: all 0.3s ease;
	color: #222;
}

button {
	font-weight: 700;
	font-size: 1.8em;
	color: #111;
	background: #fefefe;
	box-shadow: 0px 4px 0px 0px #1f2029;
	border-style: none;
	padding: 10px 50px;
	margin: 30px;
	position: relative;
	display: inline-block;
	transition: all .1s linear;
}

button:active {
	transform: translateY(3px);
	-webkit-transform: translateY(3px);
	-ms-transform: translateY(3px);
}

@media only screen and (min-width: 768px) {
		.form-modal .in-input {
				width: 47.5%;
				margin-left: -11.5%;
		}
		.form-modal .in-input {
				width: 47.5%;
		}

		.form-modal form {
				width: 70%;
		}
}
</style>