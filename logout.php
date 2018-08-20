<?php
//session_start();
require_once("includes/initialize.php");

$Session->logout();

redirect_to("index.php");

?>