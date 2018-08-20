<?php

class Uploads {
    private $uploads_path = null;
    private $user_id      = null;
    
    protected $upload_errors = array(
           UPLOAD_ERR_OK           => "No errors.",
           UPLOAD_ERR_INI_SIZE     => "File is larger than upload maximum size.",
           UPLOAD_ERR_FORM_SIZE    => "File is larger than upload maximum size.",
           UPLOAD_ERR_PARTIAL      => "THE upload was incomplete.",
           UPLOAD_ERR_NO_FILE      => "No file was selected.",
           UPLOAD_ERR_NO_TMP_DIR   => "No temporal directory.",
           UPLOAD_ERR_CANT_WRITE   => "Can't write to disk.",
           UPLOAD_ERR_EXTENSION    => "File upload stopped by extension."
    );
    
    function __construct() {
        global $Session;
        $this->user_id = $Session->user_id();
        $this->set_uploads_path();
    }
    
    function uploads_path() {
        return $this->uploads_path;
    }
    
    public function make_gallery_dir() {
        mkdir($this->uploads_path."/photos/main", 0755, true);
    }
    
    
    function set_uploads_path() {
        $path =  SITE_ROOT."uploads".DS.$this->gen_dir_name();
        $this->uploads_path = $path;
    }
    
    // uses sessions ID by default
    public function gen_dir_name($user_id="") {
        global $Session;
        if($user_id == null) {
            $user_id = $Session->user_id();
        }
        return md5($user_id);
    }
}

$Uploads = new Uploads ();

?>