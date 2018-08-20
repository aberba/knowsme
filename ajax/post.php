<?php
require_once("../includes/initialize.php");


if(isset($_POST['choice']) && isset($_POST['post_id'])) {
    $post_id   = $Database->clean_data($_POST['post_id']);
    $action  = $Database->clean_data($_POST['choice']);
    $choice  = ($action == "minus") ? "minus" : "plus";
    
    $sql = "UPDATE posts SET {$choice} = {$choice} +1 WHERE post_id = '$post_id' LIMIT 1";
    $result = $Database->query($sql);
    
    if($result) {
        echo 1;
    }else {
        echo 2;
    } 
    
    exit();
}

if(isset($_POST['comment']) && isset($_POST['user_id']) && isset($_POST['post_id'])) {
    $post_id = $Database->clean_data($_POST['post_id']);
    $user_id = $Database->clean_data($_POST['user_id']);
    $comment = $Database->clean_data($_POST['comment']);
    $date    = time();
     
    if(empty($user_id) || empty($comment) || empty($post_id)) return false;
    
    $sql  = "INSERT INTO comments (post_id_fk, user_id_fk, comment, date_posted) ";
    $sql .= "VALUES ('$post_id', '$user_id', '$comment', '$date')";
    $result = $Database->query($sql);
    
    if($Database->affected_rows() == 1) {
        echo $Posts->generate_comment($comment);
    }else {
        echo "failed";
    }
    exit();
}

if(isset($_POST['password'])) {
   
}
?>