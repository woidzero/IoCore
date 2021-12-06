<?php
function listdir($dir){
	$dirstring = "";
	$files = scandir($dir);
	foreach($files as $file) {
		if(pathinfo($file, PATHINFO_EXTENSION) == "php" AND $file != "index.php"){
			$dirstring .= "<li><a href='$dir/$file'>$file</a></li>";
		}
	}
	return $dirstring;
}

echo '<h1>Account:</h1><ul>';
echo listdir("account");
echo'</ul><h1>Upload:</h1><ul>';
echo listdir(".");
echo "</ul><h1>The cron (fixing CPs, autoban.)</h1><ul>";
echo "<li><a href='cron/cron.php'>cron.php</a></li>";
echo "</ul><h1>Stats:</h1><ul>";
echo listdir("stats");
?>
<style>
	body {
		background-color: gray;
	}
</style>