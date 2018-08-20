<?php
require_once("../includes/initialize.php");

$requested_with = $_SERVER["HTTP_X_REQUESTED_WITH"];
if(!is_ajax_request($requested_with)) exit();

if(isset($_POST['fetch_session'])) {
    echo $Session->user_id();
    exit();
}























?>