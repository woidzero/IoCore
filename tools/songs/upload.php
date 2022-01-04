<?php
include "../../include/lib/connection.php";
require_once "../../include/lib/exploitPatch.php";

if ($_FILES && $_FILES['filename']['error'] == UPLOAD_ERR_OK) {
    $file_type = $_FILES['filename']['type'];
    $allowed = array("audio/mpeg", "audio/ogg", "audio/mp3");
    if(!in_array($file_type, $allowed)) {
        $log = "[TYPE.EXCEPTION]: You can upload only audios!";
    } else {
        $maxsize = 10485760;
        if($_FILES['filename']['size'] >= $maxsize) {
            $log = "[SIZE.EXCEPTION]: Max file size is 8mb";
        } else {
            $string = $_FILES['filename']['name']; // song_name.mp3
            $songname = str_replace('.mp3', '', $string); //song_name
            $song_name = str_replace('_', ' ', $songname); // song name
            $name = str_replace(' ', '%20', $string); // song%20name

            move_uploaded_file($_FILES['filename']['tmp_name'], "song/$songname.mp3");

            $size = round($_FILES['filename']['size'] / 1024 / 1024, 2);
            $hash = "";
            $servername = $_SERVER['SERVER_NAME'];
            $song = "https://$servername/database/tools/songs/song/$name";
            $query = $db->prepare("INSERT INTO songs (name, authorID, authorName, size, download, hash)
            VALUES (:name, '9', :author, :size, :download, :hash)");
            $query->execute([':name' => $song_name, ':download' => $song, ':author' => "IoCore Music", ':size' => $size, ':hash' => $hash]);
            $log = "[SUCCES] ID: <b>ID ".$db->lastInsertId()."</b>";
        }
    }
} else {}
?>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, maximum-scale=1.0, user-scalable=no">
<link href="../../include/components/css/styles.css" rel="stylesheet">
<link href="../../include/components/images/tools_favicon.png" rel="shortcut icon">

<body>
	<main id="tools">
		<h1>GDPS Tools > Song Upload</h1>
		<section id="toolbox" style="height: 30rem;">
            <form method="post" action="upload.php" enctype='multipart/form-data'>
                <input type='file' name='filename' size='10' /><br>
                <input class="button" type="submit" value="Upload" />
            </form>
            <div id="toolbox__log">
                <p><?php echo $log ?></p>
            </div>
		</section>
	</main>
	<footer>Provided by <span><a href="https://github.com/WoidZero/IoCore">IoCore</a></span> / Developed by <a href="https://github.com/WoidZero">WoidZero</a></footer>
</body>
