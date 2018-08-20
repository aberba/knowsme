<?php

class Photos extends Uploads {
    
    private $table_name = "photos";
    private $uploads_path = null;

    private $photo_id;
    private $user_id;
    private $photo_name;
    private $caption;
    private $date_uploaded;
    
    private $temp_name;
    private $upload_path;
    private $allowed_exts = array("png", "jpeg", "jpg", "gif");
        
    
    function __construct() {
        $this->user_id = @$_SESSION['kme_user_id'];
        $user_info = $this->find_by_id($this->user_id);
        $this->set_uploads_path();
        
        $this->upload_path = $user_info['uploads_path']; // asign upload path of user
    }
    
    function album_path($album_name) {
        return $this->upload_path."/photos/$album_name";
    }
    
    function album_url($album_name, $photos_name) {
        global $Session;
        $user_id = $Session->user_id();
        $dir = $this->gen_dir_name(null); // dir of users_uploads
        
        return SITE_URL."/uploads/$dir/photos/$album_name";
    }
    
    function photos_url() {
        $dir = $this->gen_dir_name(null);
        return SITE_URL."/uploads/$dir/photos";
    }
    
    function user_photos_url($user_id) {
        $dir = $this->gen_dir_name($user_id);
        return SITE_URL."/uploads/$dir/photos";
    }
    
    
    
    public function create_album_dir($name) {
        global $Database,
               $Session;
        
        // Sanitize name 
        $name = $name = $Database->clean_data($name);
        $name = stripcslashes($name);
        echo $name;
        
        $new_dir = $this->uploads_path."/photos/$name";
        
        if(is_dir($new_dir)) {
            return 2;
        }else {
            
            if(mkdir($new_dir, 0755, true)) {
                return 1;
            }else {
                return 0;
            }
        }  
    }
    
    public function fetch_photo_comments($photo_id) { 
        global $Database, $User, $Dates, $Texts;
        
        $photo_id = (int) $Database->clean_data($photo_id);
        $sql = "SELECT * FROM photos_comments WHERE photo_id_fk = '{$photo_id}'";
        $data = $Database->query($sql);
        
        if($Database->num_rows($data) < 1) return false;
        
        $output = array();
        while($row = $Database->fetch_data($data)) {
            $c_user  = $User->find_by_id($row->user_id_fk);
            $oname   = ($c_user->other_name == null) ? "" : $c_user->other_name;
            $user_name = $c_user->first_name." ".$oname." ".$c_user->last_name;
            
            $dir      = $this->gen_dir_name($row->user_id_fk);
            $img_path = $c_user->profile_photo_path;
            $path     = "uploads/{$dir}/photos/$img_path";
            $output[] = array("user_id"    => $row->user_id_fk,
                              "user_name"  => $user_name,
                              "user_photo" => $path,
                              "comment"    => $Texts->add_emoticons($row->comment),
                              "date"       => $Dates->string_date($row->date_posted)
                             );   
        }
        return $output;
    }
    
    public function fetch_album($album_id="") {
        global $Database;
        
        $album_id = (int) $Database->clean_data($album_id);
        
        $sql = "SELECT * FROM photo_albums WHERE album_id = '{$album_id}' LIMIT 1";
        $data = $Database->query($sql);
        return $Database->fetch_data($data); 
    }
    
    public function fetch_album_photos($album_id) {
        global $Database, $Session, $Uploads;
        
        $session_id = $Session->user_id();
        $album_id = (int) $Database->clean_data($album_id);
        
        $album       = $this->fetch_album($album_id);
        $album_name  = $album->album_name;
        
        $sql  = "SELECT * FROM photos WHERE album_id_fk = '{$album_id}'";
        $data = $Database->query($sql);
        if($Database->num_rows($data) < 1) return false;
        
        $output = array();
        $dir_name = $this->gen_dir_name($session_id);
        $path     = "uploads/{$dir_name}/photos/{$album_name}/";        
        while($row = $Database->fetch_data($data)) {
            $row->path = $path.$row->photo_name;
            $output[] = $row;
        } 
        return $output;
    }
    
    // fetches albums of sessions users and visitors of profile 
    public function fetch_albums($user_id) {
        global $Database;
        $user_id = (int) $Database->clean_data($user_id);
        
        $sql  = "SELECT * FROM photo_albums WHERE user_id_fk = '$user_id' ORDER BY ";
        $sql .= "date_created DESC";
        $data = $Database->query($sql);
        if($num = $Database->num_rows($data) < 1) return false;
        
        $output = array();
        
        while($row = $Database->fetch_data($data)) {
           $cover_path = $row->album_cover_path;
           $dir  = $this->gen_dir_name($user_id);
           $path = "uploads/$dir/photos/$cover_path";
           
           $output[] = array("album_id" => $row->album_id,
                             "album_name" => $row->album_name,
                             "album_cover" => $path);  
        }
        return $output;
    }
    
    
    function upload_album_photos($album_id="", array $files) {
         global $Database, $Uploads, $Session;
         
         $session_id = $Session->user_id();
         $album_id = (int) $Database->clean_data($album_id);
         $num      = count($files["file"]["name"]);
         $date     = time();
         
         $album      = $this->fetch_album($album_id);
         $album_name = $album->album_name;
         $album_dir  = $this->uploads_path().DS."photos".DS.$album_name;
         
         if(count($files) < 1) return false;
         
         $output = array();
         for($i=0; $i<$num; $i++) {
            $exp_array   =  explode(".", basename($files["file"]["name"][$i]));
            $ext         = $exp_array[count($exp_array) -1];
            $file_name   = $album_name."_".md5(uniqid("", true)). ".". $ext;
            $upload_path = $album_dir.DS.$file_name;
            
            if(in_array($ext, $this->allowed_exts)) {
               if(move_uploaded_file($files["file"]["tmp_name"][$i], $upload_path)) {
                  $output[] = $file_name;
                  
                  $sql  = "INSERT INTO photos (album_id_fk, user_id_fk, photo_name, ";
                  $sql .= "date_uploaded) VALUES('{$album_id}', '{$session_id}', ";
                  $sql .= "'{$file_name}', '{$date}')";
                  $Database->query($sql);
               }
            } 
         }
         return $output;
    }
    
    public function validate_image_ext($file_name="") {
        $allowed_exts = array("image/jpg", 
                              "image/jpeg", 
                              "image/png", 
                              "image/gif", 
                              "image/pjpeg"
                             );
        
        $photo_type = $image_file['name'];
        $photo_type = $photo_file['type']; 
        $photo_size = $photo_file['size'];
        $photo_error = $image_file['error'];
        $max_size = 1000000;
        
        //validate images 
        if(in_array($photo_error, $this->upload_errors)) {
            //$error['format'] = $upload_errors[$photo_error];
            return false;
        }elseif($photo_size > $max_size) {
            //$error['size'] = "File too large";
            return false;
        }else {
            return true;
        }
    }
    
    public function delete_photo($file_name, $path) {
        return unlink("$path/$file_name"); 
    }
    
    public function save_photo() {
        
    }
    
    
    // this Pictures class also contains common functions of the user class
    
    public function find_all() {
      global $Database;
      $table_name = $this->table_name;
      
      return $this->find_by_sql("SELECT * FROM ".$table_name);
    }
    
   public function find_by_id($id=0) {
      global $Database;
      $table_name = $this->table_name;
      $data = $this->find_by_sql("SELECT * FROM ".$table_name." WHERE " .
      "photo_id = '$id' LIMIT 1");
      
      
      return $Database->fetch_data($data);
   }
   
   public static function find_by_sql($sql="") {
      global $Database;
      return $Database->query($sql);
   }     
}
$Photos = new Photos();
?>