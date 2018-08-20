<?php

class Chat {
    private $friend        = null;
    private $message       = null;
    private $last_msg_time = null;
    
    public function fetch_online_users() {
       global $Database, $User, $Session;
        
       $friends_ids = $User->fetch_friends_ids();
       
       if(count($friends_ids) >=1) { // if user has friends, fetch those online
          $array  = array();
          $sql2  = "SELECT user_id, first_name, last_name, other_name FROM members ";
          $sql2 .= "WHERE user_id = '0'";
          
          foreach($friends_ids as $id) {
             $sql2 .= " OR user_id = '{$id}'";
          }
          $sql2 .= " AND online = '1'";
          $data2 = $Database->query($sql2);
          
          if($Database->num_rows($data2) >= 1) { // if there are online friends
              $online = array();
             
              while($row = $Database->fetch_data($data2)) {
                 $online[] = $row;
              }
              return $online;
          }else {
              return false;
          }// end of if there are online friends
          
        }else {
           return false;
        }  // end of if users has friends
    }
    
    public function fetch_chat($friend_id="") {
        global $Database, $Session, $Posts;
        
        $friend_id  = (int) $Database->clean_data($friend_id);
        $session_id = $Session->user_id();
        
        $sql  = "SELECT * FROM chat WHERE ";
        $sql .= "(friend_one = '{$session_id}' AND friend_two = '{$friend_id}') OR ";
        $sql .= "(friend_one = '{$friend_id}' AND friend_two = '{$session_id}') ";
        $sql .= "ORDER BY date_sent ASC";
        
        $data = $Database->query($sql);
        
        if($Database->num_rows($data) >=1 ) {
            $output = array();
            while($row = $Database->fetch_data($data)) {
                $row->iclass = "other";
                if($row->friend_one == $session_id) $row->iclass = "me";
                $row->message = $Posts->generate_comment($row->message);
        
                $output[] = $row;
            }
            return $output;
        }else {
            return false;
        }
    }
    
    public function send_message($friend_id="", $message="") {
        global $Database, $Session;
        
        $session_id = $Session->user_id();
        $friend_id  = (int) $Database->clean_data($friend_id);
        $message    = $Database->clean_data($message);
        $date       = time();
        $this->last_msg_time = $date+1;
        
        $sql  = "INSERT INTO chat (friend_one, friend_two, message, date_sent) ";
        $sql .= "VALUES ('{$session_id}', '{$friend_id}', '{$message}', '{$date}')";
        $Database->query($sql);
        
        if($Database->affected_rows() == 1) {
            return true;
        }else {
            return false;
        }
    }
    
    public function clear_messages($friend_id="") {
        global $Database, $Session;
        
        $session_id = $Session->user_id();
        $friend_id = (int) $Database->clean_data($friend_id);
        
        $sql  = "DELETE FROM chat WHERE ";
        $sql .= "(friend_one = '{$session_id}' AND friend_two = '{$friend_id}') OR ";
        $sql .= "(friend_one = '{$friend_id}' AND friend_two = '{$session_id}')";
        $Database->query($sql);
        
        if($Database->affected_rows() >= 1) {
            return true;
        }else {
            return false;
        }
    }
    
    public function last_msg_time() {
        return $this->last_msg_time;
    }
    
    public function fetch_unread_msg($friend_id="") {
        global $Database, $Posts, $Session;
        
        $friend_id  = (int) $Database->clean_data($friend_id);
        $session_id = $Session->user_id();
        $last_time = $this->last_msg_time();
        
        $sql  = "SELECT * FROM chat WHERE ";
        $sql .= "(friend_one = '{$session_id}' AND friend_two = '{$friend_id}') OR ";
        $sql .= "(friend_one = '{$friend_id}' AND friend_two = '{$session_id}') ";
        $sql .= "AND date_sent > '{$last_time}' ORDER BY date_sent ASC";
        
        $data = $Database->query($sql);
        
        if($Database->num_rows($data) >=1 ) {
            $output = array();
            while($row = $Database->fetch_data($data)) {
                $row->iclass = "other";
                if($row->friend_one == $session_id) $row->iclass = "me";
                $row->message = $Posts->generate_comment($row->message);
                
                $output[] = $row;
                $this->last_msg_time = $row->date_sent;
            }
            return $output;
        }else {
            return false;
        }
    } 
}

$Chat = new Chat();














?>
