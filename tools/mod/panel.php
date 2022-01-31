<?php
    session_start();

    include "../../include/lib/connection.php";
    require "../../include/lib/generatePass.php";
    require_once "../../include/lib/exploitPatch.php";
    require_once "../../include/lib/mainLib.php";

    $ep = new exploitPatch();
    $gs = new mainLib();

    if (isset($_SESSION['user']) && $_SESSION['user'] == true) {
        echo '
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, maximum-scale=1.0, user-scalable=no">
        <link href="../../include/components/css/styles.css" rel="stylesheet">
        <link href="../../include/components/images/tools_favicon.png" rel="shortcut icon">
        <title>Mod Panel</title>
        
        <body>
            <main id="tools">
                <h1>Mod Panel</h1>
                <section id="toolbox">
                    <h2 class="toolName">Mod Tools</h2>
                    <a class="button" href="suggestionList.php">Suggestions</a>
                </section>
            </main>
            <footer>Provided by <span><a href="https://github.com/WoidZero/IoCore">IoCore</a></span> / Developed by <a href="https://github.com/WoidZero">WoidZero</a></footer>
        </body>';

        } else {
            echo "<body style='background: #151515'><h1 style='color: #fff'>You not logined.";
    }
?>