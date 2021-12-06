<?php
include "../../include/lib/connection.php";
require_once "../../include/lib/exploitPatch.php";

if ($_FILES && $_FILES['filename']['error'] == UPLOAD_ERR_OK) {
    $file_type = $_FILES['filename']['type'];
    $allowed = array("audio/mpeg", "audio/ogg", "audio/mp3");
    if(!in_array($file_type, $allowed)) {
        $er = "You can upload only audios!";
    } else {
        $maxsize = 10485760;
        if($_FILES['filename']['size'] >= $maxsize) {
            $er = "Max file size is 8mb";
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
            $query->execute([':name' => $song_name, ':download' => $song, ':author' => "RainixGDPS", ':size' => $size, ':hash' => $hash]);
            $er = "Загружено. Айди: <b>ID ".$db->lastInsertId()."</b>";
        }
    }
} else {}
?>

<p>
<?php
if($er == "") {
    echo "Загрузка музыки";
} else {
    echo $er;
}
?>
</p>
<form method="post" action="upload.php" enctype='multipart/form-data'>
<input type='file' name='filename' size='10' />
<input type="submit" value="Upload" />
</form>
