<?php

class User {
   public $user_id;
   public $email;
   public $password;
   public $fname;
   public $lname;
   public $gender;
   
   public function are_friends($friend_id="") {
       global $Database, $Session;
       
       $session_id = $Session->user_id();
       $friend_id  = (int) $Database->clean_data($friend_id);
       
       $sql  = "SELECT * FROM friends WHERE ";
       $sql .= "(friend_one = '{$friend_id}' AND friend_two = '{$session_id}') OR ";
       $sql .= "(friend_one = '{$session_id}'AND friend_two = '{$friend_id}') LIMIT 1";
       
       $data = $Database->query($sql);
        
       if($Database->num_rows($data) == 1) {
          return true;
       }else {
          return false;
       }
   }
   
   public function fetch_user() {
       global $Database,
              $Session,
              $Photos;
       $user_id = $Session->user_id();
       
       $sql  = "SELECT M.*, I.* FROM members M, profile_information I ";
       $sql .= "WHERE M.user_id = '{$user_id}' AND I.user_id_fk = M.user_id LIMIT 1";
      
      $data  = $Database->query($sql);
      if($Database->num_rows($data) != 1) return false;
      
      return $Database->fetch_data($data);
   }
   
   public function fetch_friends() {
       global $Database, $Session, $Uploads, $Photos;
       $user_id = $Session->user_id();
       $sql  = "SELECT * FROM friends WHERE friend_one = '$user_id' OR ";
       $sql .= "friend_two = '$user_id'";
       $data = $Database->query($sql);
       $friends_ids = array();
       if($Database->num_rows($data) < 1) return false;
       
       while ($row = $Database->fetch_data($data)) {
          if($row->friend_one == $user_id) {
              $friends_ids[] = $row->friend_two;
          }else {
              $friends_ids[] = $row->friend_one;
          }
       }
       
       $sql2 = "SELECT * FROM members WHERE user_id = 0";
       $clause = "";
       
       foreach ($friends_ids as $id => $value) {
          $clause .= " OR user_id = '$value'";
       }
       
       if(!empty($clause)) {
          $sql2 .= $clause;
       }
       $sql2 .= " ORDER BY first_name ASC, last_name ASC";
       $users = $Database->query($sql2);
       
       $output = array();
       
       while ($row = $Database->fetch_data($users)) {
          $path = "img/icons/default_male.png";
          if($row->gender == "F") {
              $path = "img/icons/default_female.png";
          }
          
          $photo_path   = str_replace("/", DS, $row->profile_photo_path);
          $photo_file   = $Uploads->uploads_path().DS.$photo_path;
          
          if(!empty($photo_path)) {
             $dir = $Photos->user_photos_url($row->user_id);
             $path = $dir."/".$row->profile_photo_path;
             $photo_file = "hello";
          }
          
          $oname = ($row->other_name == null) ? "" : $row->other_name;
          $fullname = $row->first_name." ".$oname." ".$row->last_name;
          $output[] = array("user_id" => $row->user_id,
                            "name" => $fullname,
                            "profile_photo" => $path,
                            "country" => "Ghana");
       }
       return $output;
   }
   
   public function fetch_friends_ids() {
        global $Database, $Session;
        
        $user_id = $Session->user_id();
        $sql  = "SELECT * FROM friends WHERE friend_one = '$user_id' OR ";
        $sql .= "friend_two = '$user_id' AND status = '1'";
        $data = $Database->query($sql);
        
        $friends_ids = array();
        if($Database->num_rows($data) < 1) return false;
       
        while ($row = $Database->fetch_data($data)) {
           if($row->friend_one == $user_id) {
              $friends_ids[] = $row->friend_two;
           }else {
              $friends_ids[] = $row->friend_one;
           }
        }
        return $friends_ids;
   }
   
   public function search_friends($name="") {
         global $Database, $User, $Uploads;
         
         $name        = $Database->clean_data($name);
         $friends_ids = $this->fetch_friends_ids();
         
         if(!is_array($friends_ids)) return false;
         $sql  = "SELECT * FROM members WHERE (first_name LIKE '%$name%' OR ";
         $sql .= "last_name LIKE '%$name%' OR other_name LIKE '%$name%') AND (";
         $sql .= " user_id = '0'";
        
         foreach($friends_ids as $id) {
             $sql .= " OR user_id = '{$id}'";
         }
         $sql .= ") ORDER BY first_name ASC";
         
         $data   = $Database->query($sql);
         $output = array();
         $all = array();
         while ($row = $Database->fetch_data($data)) {
             $all[] = $row;
             $path = "img/icons/default_male.png";
             if($row->gender == "F") {
                 $path = "img/icons/default_female.png";
             }
          
             $photo_path   = str_replace("/", DS, $row->profile_photo_path);
             $photo_file   = $Uploads->uploads_path().DS.$photo_path;
          
             if(!empty($photo_path)) {
                $dir = $Photos->user_photos_url($row->user_id);
                $path = $dir."/".$row->profile_photo_path;
                $photo_file = "hello";
             }
             $oname = ($row->other_name == null) ? "" : $row->other_name;
             $fullname = $row->first_name." ".$oname." ".$row->last_name;
             $output[] = array("user_id" => $row->user_id,
                               "name" => $fullname,
                               "profile_photo" => $path,
                               "country" => "Ghana");
         }
         return $output;
   }
   
   public function add_user(array $data) {
       global $Database, 
              $Secure,
              $User,
              $Session,
              $Uploads;
       
       $email       = $data['email'];
       $password    = $Secure->secure_password($data['password']);
       $activation  = md5(time().$email);
       $date        = time();
       
       $sql  = "INSERT INTO members (email, password, activation, date_registered) ";
       $sql .= "VALUES ('$email', '$password', '$activation', '$date') LIMIT 1";
       
       if(!$Database->query($sql)) return false;
       
       $insert_id = $Database->insert_id();
       $result = $this->register_user_information($insert_id, $data);
       
       if($result) {
           $Session->login($insert_id);
           $Uploads->make_gallery_dir(); // make an initial dir for photos
           
           return true;
       }else {
           return false;
       }
   }
   
   public function register_user_information($insert_id, $data) {
       global $Database;
       
       $id          = (int) $insert_id;
       $fname       = strtoupper($data['fname']);
       $lname       = strtoupper($data['lname']);
       $gender      = $data['gender'];
       
       $sql  = "INSERT INTO profile_information (user_id, first_name, last_name, ";
       $sql .= "gender) VALUES ('$id', '$fname', '$lname', '$gender')";
       return $Database->query($sql);
   }  
   
   
   public function authenticate($email="", $password="") {
      global $Database,
             $Secure;
      
      $email    = $Database->clean_data($email);
      $password = $Secure->secure_password($password);
      //echo $Secure->secure_password("aberba1313");
      
      $sql  = "SELECT * FROM members ";
      $sql .= "WHERE email = '{$email}' ";
      $sql .= "AND password = '{$password}' ";
      $sql .= "LIMIT 1";
      
      $record = $this->find_by_sql($sql);
      
      if($Database->num_rows($record) == 1) {
         $row = $Database->fetch_data($record);
      }
      return !empty($row) ? $row->user_id : false;
   }
   
   public function validate_from_blacklist($category, $value) {
      global $Database;
      
      $category = strtoupper($Database->clean_data($category));
      $value    = strtoupper($Database->clean_data($value));
      
      $sql  = "SELECT * FROM blacklist ";
      $sql .= "WHERE category = '{$category}' ";
      $sql .= "AND value = '{$value}' LIMIT 1";
      $record = $Database->query($sql);
      
      if($Database->num_rows($record) == 1) {
         return false;
      }else {
         return true;
      }
   }
   
   public function validate_email($email) {
      $email = strtolower($Database->clean_data($email));
      
      if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
         if($this->validate_from_blacklist("EMAIL", $email)) {
            return true;
         }else {
            return false;
         } 
      }else {
         return false;
      }
   }
   
   public function validate_password($password) {
      if (!isset($password[4])) {
         return false;
      }else {
         return true;
      }
   }
   
   public function validate_user($id) {
      global $Database;
      $id = (int) $Database->clean_data($id);
      
      $data = $this->find_by_sql("SELECT * FROM members WHERE " .
      "user_id = '$id' LIMIT 1");
       $result = $Database->fetch_data($data);
       if ($Database->num_rows($result) == 1) {
          return true;
       }else {
          return false;
       }
   }
   
   public function find_all() {
      global $Database;
      return $this->find_by_sql("SELECT * FROM members");
   }
    
   public function find_by_id($id=0) {
      global $Database;
      $id = $Database->clean_data($id);
      
      $data = $this->find_by_sql("SELECT * FROM members WHERE " .
      "user_id='$id' LIMIT 1");
      return $Database->fetch_data($data);
   }
   
   public function find_by_sql($sql="") {
      global $Database;
      return $Database->query($sql);  
   }               
}

$User = new User();
?>