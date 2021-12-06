<?php
chdir(dirname(__FILE__));
require_once "../lib/GJPCheck.php";
require_once "../lib/exploitPatch.php";
require_once "../lib/mainLib.php";
$ep = new exploitPatch();
$gs = new mainLib();

$gjp = $ep->remove($_POST["gjp"]);
$accountID = $ep->remove($_POST["accountID"]);

if($accountID != "" AND $gjp != ""){
	$GJPCheck = new GJPCheck();
	$gjpresult = $GJPCheck->check($gjp,$accountID);
	if($gjpresult == 1){
		if ($gs->getMaxValuePermission($accountID,"actionRequestMod") == 1) {
			$permState = $gs->getMaxValuePermission($accountID,"modBadgeLevel");			   
			if ($permState >= 2){
				exit("2");
			}
		echo $permState; 
		}
	} else {
			echo -1; 
		}
	} else {
		echo -1;
}
?>
