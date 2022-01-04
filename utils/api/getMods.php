<?php
include "../database/incl/lib/connection.php";

$roleassign = $db->prepare("SELECT * FROM roleassign WHERE 1");
$roleassign->execute([]);

function getRoleName($roleID){
    global $db;
    $rolename = $db->prepare("SELECT roleName FROM roles WHERE roleID=:roleID");
    $rolename->execute(["roleID"=>$roleID]);
    $name = $rolename->fetchColumn();
    return $name;
}
function getUserName($accountID){
    global $db;
    $username = $db->prepare("SELECT userName FROM accounts WHERE accountID=:accountID");
    $username->execute(["accountID"=>$accountID]);
    $name = $username->fetchColumn();
    return $name;
}
$rows = $roleassign->fetchAll(PDO::FETCH_ASSOC);
$mods = array();
foreach($rows as $row){
    $username = getUserName($row["accountID"]);
    $rolename = getRoleName($row["roleID"]);
    $mods[] = array("user_name"=>$username, "role_name"=>$rolename);
}
echo(json_encode($mods));