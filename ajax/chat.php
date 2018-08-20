<?php
require_once("../includes/initialize.php");

$requested_with = $_SERVER["HTTP_X_REQUESTED_WITH"];
if(!is_ajax_request($requested_with)) exit();


if(isset($_GET['fetch_online_users'])) {
    $online = $Chat->fetch_online_users();
     
    if(is_array($online)) {
        echo json_encode($online);
    }else {
        echo 0;
    }
    exit();  
}


if(isset($_POST['fetch_chat'])) {
    if(empty($_POST['friend_id'])) exit();
    
    $messages = $Chat->fetch_chat($_POST['friend_id']);
    if(is_array($messages)) {
        echo json_encode($messages);
    }else {
        echo 0;
    }
    exit();  
}


if(isset($_POST['fetch_unread'])) {
    if(empty($_POST['friend_id'])) exit();
    
    $messages = $Chat->fetch_unread_msg($_POST['friend_id']);
    if($messages) {
        echo json_encode($messages);
    }else {
        echo 0;
    }
    exit();  
}


if(isset($_POST['send_message'])) {
    if(empty($_POST['message'])) exit();
    
    $result = $Chat->send_message($_POST['friend_id'], $_POST['message']);
    if($result) {
        $message = $Database->clean_data( $_POST['message']);
        echo $Posts->generate_comment($message);
    }else {
        echo 0;
    }
    exit();
}


if(isset($_POST['clear_messages'])) {
    
    $result = $Chat->clear_messages($_POST['friend_id']);
    if($result) {
        echo 1;
    }else {
        echo 0;
    }
    exit();
}
























?>