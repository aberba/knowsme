<?php

class Dates {
    
    public function string_date($unix_timestamp) {
        return date("jS F Y \a\\t H:I  A", $unix_timestamp);
    }
    
}


$Dates = new Dates();
?>