<?php
session_start();
?>

<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, maximum-scale=1.0, user-scalable=no">
        <link href="../../include/components/css/styles.css" rel="stylesheet">
        <link href="../../include/components/images/tools_favicon.png" rel="shortcut icon">
        <script src="https://cdn.jsdelivr.net/npm/chart.js@3.6.2/dist/chart.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        <title>Mod Panel</title>
    </head>
    <body>

    <h1 class='title'>Suggestions</h1>
    <table class='table'>
        <tr>
            <th>Time</th>
            <th>Suggester</th>
            <th>Level ID</th>
            <th>Difficulty</th>
            <th>Stars</th>
            <th>Featured</th>
            <th>Close</th>
        </tr>
    <?php

    include "../../include/lib/connection.php";
    require "../../include/lib/generatePass.php";
    require_once "../../include/lib/exploitPatch.php";
    require_once "../../include/lib/mainLib.php";

    $ep = new exploitPatch();
    $gs = new mainLib();

    function closeSuggestion($id) {
        include "../../include/lib/connection.php";
        $query = $db->prepare("DELETE FROM `suggest` WHERE `suggest`.`ID` = :id");
        $query->execute(array(':id' => $id));
        return 1;
    }

    if (isset($_SESSION['user']) && $_SESSION['user'] == true) {
        $query = $db->prepare("SELECT ID,suggestBy,suggestLevelId,suggestDifficulty,suggestStars,suggestFeatured,suggestAuto,suggestDemon,timestamp FROM suggest ORDER BY timestamp DESC");
        $query->execute();
        $result = $query->fetchAll();

        foreach ($result as &$sugg) {
            $suggestID = (int)$sugg['ID'];

            echo "
            <tr>
            <td>".date("d/m/Y G:i", $sugg["timestamp"]).
            "</td>
                <td><a href='https://woidzero.xyz/tps/database/tools/account/profile.php?user=".$gs->getAccountName($sugg["suggestBy"])."'>".$gs->getAccountName($sugg["suggestBy"])."</a> (".$sugg["suggestBy"].")".
            "</td>
                <td>".htmlspecialchars($sugg["suggestLevelId"],ENT_QUOTES).
            "</td>
                <td>".htmlspecialchars($gs->getDifficulty($sugg["suggestDifficulty"],$sugg["suggestAuto"],$sugg["suggestDemon"]), ENT_QUOTES).
            "</td>
                <td>".htmlspecialchars($sugg["suggestStars"],ENT_QUOTES).
            "</td>
                <td>".htmlspecialchars($sugg["suggestFeatured"],ENT_QUOTES).
            "</td>
                <td><a class='button' href='suggestionList.php?delete=true'>Delete</a>".
            "</td></tr>";
        }

        echo "</table>";

        if (isset($_GET['delete'])) {
            closeSuggestion($suggestID);
            header("Refresh: 0; url='https://woidzero.xyz/tps/database/tools/mod/suggestionList.php'");
            $log = "Suggestion with id: <b>".$suggestID."</b> was deleted.";
        }
    } else {
        echo "You not logined.";
    }
    ?>

    <div align='center' id="toolbox__log">
        <p><?php echo $log; ?></p>
    </div>
    </body>
</html>