<?php

class Session {
    private  $logged_id = false;
    private  $user_id = null;
    
    function __construct() {
        session_start();
        $status = $this->check_login();
    }
    
    // Log a user into a session
    public function login($user_id="", $keep_me_logged_id="no") {
      
       if(empty($user_id)) return false;
          $this->logged_id        = true;
          $_SESSION['kme_u_id']   = $user_id;
          
       if($keep_me_logged_id == "yes") {
          setcookie("kme_u_id", $user_id, time()+30*60*2);
       }
       return true;
    }
    
    public function user_id() {
        return $this->user_id;
    }
    
    //Checks if user is logged in
    private function check_login() {
        
        if(isset($_SESSION['kme_u_id'])) {
           $this->logged_id = true;
           $this->user_id   = (int) $_SESSION['kme_u_id'];
        }else {
           unset($this->logged_id);
           $this->logged_id = false;
        }
    }
    
    public function logged_in() {
       return $this->logged_id;
    }
    
    // Logout function
    public function logout() {
         unset($this->logged_id);
         
         $this->logged_id = false;
         
         unset($_SESSION['kme_u_id']);
         
         @setcookie(session_name(), '', time()-3600);
         unset($_COOKIE['kme_u_id']);
           
         //delete cokkies if any 
         setcookie('kme_u_id', '', time()-3600*5);  
        
         session_destroy();
    }  
}

$Session = new Session();
?>