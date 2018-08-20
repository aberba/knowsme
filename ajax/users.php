<?php
require_once("../includes/initialize.php");


if(isset($_POST['unfriend'])) {
    
    $friend_id = (int) $Database->clean_data($_POST['user_id']);
    
    if(empty($friend_id)) exit();
    
    $user_id = $Session->user_id();
    
    $sql  = "DELETE FROM friends WHERE ";
    $sql .= "(friend_one = '{$user_id}' AND friend_two = '{$friend_id}') OR ";
    $sql .= "(friend_one = '{$friend_id}' AND friend_two = '{$user_id}') LIMIT 1";
    
    $result = $Database->query($sql);
    
    if($Database->affected_rows() == 1)  {
        echo 1;
    }else {
        echo 0;
    }
    exit();
}


if(isset($_POST['save-profile'])) {
    $fname  = $Database->clean_data($_POST['fname']);
    $lname  = $Database->clean_data($_POST['lname']);
    $oname  = $Database->clean_data($_POST['oname']);
    
    $birth_month  = $Database->clean_data($_POST['birth-month']);
    $birth_day  = $Database->clean_data($_POST['birth-day']);
    $birth_year  = $Database->clean_data($_POST['birth-year']);
    
    $hometown  = $Database->clean_data($_POST['hometown']);
    $city  = $Database->clean_data($_POST['current-city']);
    
    $high_school = $Database->clean_data($_POST['high-school']);
    $college    = $Database->clean_data($_POST['college']);
    $university    = $Database->clean_data($_POST['university']);
    $profession  = $Database->clean_data($_POST['profession']);
    
    print_r($_POST);
    exit();
}























?>