<?php
require_once("../includes/initialize.php");


if(isset($_POST['fetch_album_photos'])) {
    
    $album_photos = $Photos->fetch_album_photos($_POST['album_id']);
    if(is_array($album_photos) && count($album_photos) > 0) {
        echo json_encode($album_photos);
    }else {
        echo 0;
    }
    exit();
}


if(isset($_POST['fetch_photo_comments'])) {
    
    $photo_comments = $Photos->fetch_photo_comments($_POST['photo_id']);
    if(is_array($photo_comments) && count($photo_comments) > 0) {
        echo json_encode($photo_comments);
    }else {
        echo 0;
    }
    exit();
}

if(isset($_POST['upload_album_photos'])) {
    if(empty($_POST['album_id'])) {
        echo 0;
        exit();
    }
    
    $result = $Photos->upload_album_photos($_POST['album_id'], $_FILES);
    if(is_array($result) && count($result) >= 1) {
       echo 1;
    }else {
       echo 0;
    }
    exit();
}

























?>