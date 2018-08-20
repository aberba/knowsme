<?php
require_once("../includes/initialize.php");


if(isset($_POST['fetch_album'])) {
    
    $user_id = (int) $Database->clean_data($_POST['user_id']);
    $album_id = (int) $Database->clean_data($_POST['album_id']);
    
    if(empty($user_id) || !is_int($user_id) || 
    empty($album_id)   || !is_int($album_id)) exit();
    
    $sql  = "SELECT * FROM photos WHERE album_id_fk = {$album_id} AND ";
    $sql .= "user_id_fk = {$user_id} ORDER BY date_uploaded DESC";
    
    $data = $Database->query($sql);
    if($Database->num_rows($data) < 1 ) { 
        echo "No photos Uploaded into album";
        exit();
    }
    
    $album = $Photos->fetch_album($album_id);
    
    if(!$album) exit();
    
    $album_name  = $album->album_name;
    $photos_url  = $Photos->photos_url();
    $album_path  = "{$photos_url}/{$album_name}";
    $output      = "";
    
    while ($row = $Database->fetch_data($data)) {
        $path   = "{$album_path}/".$row->photo_name;
        
        $output .= "<img src='".$path."' id='".$row->photo_id."' ";
        $output .= "alt='".$row->photo_name."' class='thumb' />";
    }
        
    echo $output;
    exit();
}























?>