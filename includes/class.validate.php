<?php

// Define URLS and Other Constants
defined("HIGH_SCHOOL") ?   null : define("HIGH_SCHOOL", "HIGH_SCHOOL"); 
defined("TERTIARY")    ?   null : define("TERTIARY", "TERTIARY"); 

class Validate {
    
    public function validate_name($name="", $parameter="") {
        if(preg_match("/^[a-zA-Z]+$/", $name)) {
            return 1;
        }else {
            return 2;
        }
    }
    
    public function validate_town($town_name="", $parameter="") {
        if(preg_match("/^[a-zA-Z1-9']+$/", $town_name)) {
            return 1;
        }else {
            return 2;
        }
    }
    
    
    public function validate_school($school_name="", $parameter="") {
        if(preg_match("/^[a-zA-Z1-9']+$/", $school_name)) {
            return 1;
        }else {
            return 2;
        }
    }
    
    public function validate_profession($profession="") {
        if(preg_match("/^[a-zA-Z1-9']+$/", $profession)) {
            return 1;
        }else {
            return 2;
        }
    } 
}


$Validate = new Validate();

?>