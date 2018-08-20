<?php
require_once("../includes/initialize.php");

$requested_with = $_SERVER["HTTP_X_REQUESTED_WITH"];
if(!is_ajax_request($requested_with)) exit();


if(isset($_POST['search_friend'])) {
    if(empty($_POST['name'])) exit();
    
    $friends = $User->search_friends($_POST['name']);
    if(is_array($friends) && count($friends) >= 1) {
        echo json_encode($friends);
    }else {
        echo 0;
    }
    exit();  
}


if(isset($_POST['fetch_all_friends'])) {
    $friends = $User->fetch_friends();
    
    if(is_array($friends) && count($friends) >= 1) {
        echo json_encode($friends);
    }else {
        echo 0;
    }
    exit();  
}























?>