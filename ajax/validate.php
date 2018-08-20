<?php
require_once("../includes/initialize.php");

if(isset($_POST['validate'])) {
    
    $input_name   = $Database->clean_data($_POST['input_name']);
    $input_value  = $Database->clean_data($_POST['input_value']);
    $result = 2; //set iniatial avlue to 2 ~ false;
    
    if(empty($input_name) || empty($input_name)) {
        echo 2;
        exit();
    }
    
    switch ($input_name) {
        case "fname": case "lname": case "oname":
            $result = $Validate->validate_name($input_value);
        break;
        case "hometown": case "current-city":
            $result = $Validate->validate_town($input_value);
        break;
        case "high-school":
            $result = $Validate->validate_school($input_value, HIGH_SCHOOL);
        break;
        case "tertiary":
            $result = $Validate->validate_school($input_value, TERTIARY);
        break;
        case "profession":
            $result = $Validate->validate_profession($input_value);
        break;
        default:
            $result = 2;
    }
    echo $result;
    exit();
}



?>