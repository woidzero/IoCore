<?php
session_start();

include "../../include/lib/connection.php";
require "../../include/lib/generatePass.php";
require_once "../../include/lib/exploitPatch.php";
require_once "../../include/lib/mainLib.php";

$ep = new exploitPatch();
$gs = new mainLib();

if (!empty($_POST["userName"]) AND !empty($_POST["password"])) {
    $userName = $ep->remove($_POST["userName"]);
    $password = $ep->remove($_POST["password"]);
    $generatePass = new generatePass();

    $pass = $generatePass->isValidUsrname($userName, $password);
    if ($pass == 1) {
        $query = $db->prepare("SELECT accountID FROM accounts WHERE userName=:userName");	
        $query->execute([':userName' => $userName]);
        $accountID = $query->fetchColumn();
        if ($query->rowCount()==0) {
            $log = "Invalid user/password. <a href='index.php'>Try again.</a>";
        } elseif ($gs->checkPermission($accountID, "toolSuggestlist")) {
            $_SESSION['user'] = true;
            header('Refresh: 0; url="https://woidzero.xyz/tps/database/tools/mod/panel.php"');
        } else {
            $log = "You don't have permissions to view content on this page.";
        }
    } else {
        $log = "Invalid user/password. <a href='index.php'>Try again.</a>";
    }
}
?>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, maximum-scale=1.0, user-scalable=no">
<link href="../../include/components/css/styles.css" rel="stylesheet">
<link href="../../include/components/images/tools_favicon.png" rel="shortcut icon">
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.6.2/dist/chart.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<title></title>

<body>
	<main id="tools">
		<h1>Log-in</h1>
		<section id="toolbox" style="height: 20rem; width: 50%;">
			<h2 class="toolName">Login</h2>
            <form class="form" action="index.php" method="post">
                Username: <input type="text" name="userName"><br>
                Password: <input type="password" name="password"><br>
                <input type="submit" value="Login">
            </form>
            <div id="toolbox__log">
                <p><?php echo $log ?></p>
            </div>
		</section>
	</main>
	<footer>Provided by <span><a href="https://github.com/WoidZero/IoCore">IoCore</a></span> / Developed by <a href="https://github.com/WoidZero">WoidZero</a></footer>
</body>