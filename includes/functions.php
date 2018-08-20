<?php

function redirect_to($location = null) {
    header("Location: $location");
}

//validate an ajax XMLhttpRequest
function is_ajax_request($reqested_with) {
    if($reqested_with == "XMLHttpRequest"){
        return true;
    }else {
        return false;
    }
}

function __autoload($class_name) {
    $class_name = strtolower($class_name);
    $path = INC_PATH."class.{$class_name}.php";
    
    if(file_exists($path)) {
       require_once($path);
        
    }else {
        die("The file {$class_name}.php could not be found");
    }
}

function include_template($template) {
    include(TEMP_PATH.$template);
}

function require_connection() {
    return require_once(INC_PATH."kme_connect.php");
}

?>