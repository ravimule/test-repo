<?php
ini_set('file_uploads', 'on');
$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["file"]["name"]);

if (move_uploaded_file($_FILES['file']['tmp_name'], $target_file )) {
    echo $_FILES["file"]["name"];
} else {
   echo "File was not uploaded";
}