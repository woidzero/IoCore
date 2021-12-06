<?php
include "../../include/lib/connection.php";
$query = $db->prepare("DELETE FROM users WHERE extID = ''");
$query->execute();
$query = $db->prepare("DELETE FROM songs WHERE download = ''");
$query->execute();
ob_flush();
flush();

$query = $db->prepare("UPDATE levels SET password = 0 WHERE password = 2");
$query->execute();

echo "1";
ob_flush();
flush();
?>