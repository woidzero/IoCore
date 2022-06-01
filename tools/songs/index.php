<?php
error_reporting(E_ALL);
include "../../include/lib/connection.php";
require_once "../../include/lib/exploitPatch.php";

$log = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_FILES && $_FILES['filename']['error'] == UPLOAD_ERR_OK) {
        if (isset($_POST['authorname'] ) && isset($_POST['songname'])) {
            if ($_FILES['filename']['size'] >= 10485760) {
                $log = "Max file size is 10mb";
            } else {
                $author_name = $_POST['authorname'];
                $song_name = $_POST['songname'];

                $songName = $author_name . " - " . $song_name;
                $url = str_replace(" ", "", $songName);

                move_uploaded_file($_FILES['filename']['tmp_name'], "song/$url.mp3");

                $size = round($_FILES['filename']['size'] / 1024 / 1024, 2);
                $hash = hash_file('sha256', "song/$url.mp3");

                $song = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."song/";
                $cur = str_replace('upload.php', '', $song) . $url . ".mp3";

                $query = $db->prepare("INSERT INTO songs (name, authorID, authorName, size, download, hash)
                VALUES (:name, '9', :author, :size, :download, :hash)");
                $query->execute([':name' => $songName, ':download' => $cur, ':author' => $author_name, ':size' => $size, ':hash' => $hash]);
                
                $log = "Success! ID: <b>". $db->lastInsertId() ."</b>";
            }
        } else {
            $log = "Provide a song name and an author name.";
        }    
    } else {
        $log = "Error uploading file: ".$_FILES['filename']['error'];
    }
}

?>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, maximum-scale=1.0, user-scalable=no">
    <link href="../../include/components/css/styles.css" rel="stylesheet">
    <link href="../../include/components/images/tools_favicon.png" rel="icon">
    <title>Song Upload</title>
</head>

<body>
	<section id="toolbox">
        <h1>Song Upload</h1>
        <form class="form" method="post"  action="index.php" enctype='multipart/form-data'>
            <input type='text' style="margin-top: 5%;" placeholder="Song Name" name='songname'><br>
            <input type='text' placeholder="Author" name='authorname'><br>
            <input type='file' name='filename' size='10'><br>
            <input class="button" type="submit" value="Upload">
        </form>
        <p class='log'>
            <?php echo $log ?>
        </p>
	</section>
	<footer>Supported by <span><a href="https://github.com/WoidZero/IoCore">IoCore</a></span> / Developed by <a href="https://github.com/WoidZero">WoidZero</a></footer>
</body>
