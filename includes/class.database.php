<?php
//require_once(CONNECT_PATH. "kme_connect.php");

class MySQLDatabase extends DatabaseObject {
    
  private $connection;
  public $last_query;
  
  // automaticallly srat connection 
  function __construct() {
    $this->start_connection();
  }
  
  function __destruct() {
    $this->close_connection();
  }
    
  public function start_connection() {
        $this->connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if(!$this->connection) {
            die("Database connection failed: ". mysqli_error());
        }
    }
  
  public function close_connection() {
    if(isset($this->connection)) {
        mysqli_close($this->connection);
        unset($this->connection);
    }
  }
  
  public function query($sql) {
    $this->last_query = $sql;
    $result = mysqli_query($this->connection, $sql);
    $this->confirm_query($result); // confirm_query() is defined to print errors  
    return $result;
  } 
  
  // this function cleans all user submitted data
  public function clean_data($data, $allowed_tags="") {
    $data = trim(strip_tags($data, $allowed_tags));
    return mysqli_real_escape_string($this->connection, $data);
  } 
  
  public function fetch_data($result) {
    return mysqli_fetch_object($result);
  }
  
  private function confirm_query($result) {
    if(!$result) {
        $output  = "Database query failed: ".mysqli_error(). "<br />";      
        $output .= "Last query: ". $this->last_query;
        die($output);
    }
  }
  
  function num_rows($result_set) {
    return @mysqli_num_rows($result_set);
  }
  
  function insert_id() {
    return mysqli_insert_id($this->connection);
  }
  
  function affected_rows() {
    return mysqli_affected_rows($this->connection);
  } 
}

$Database = new MySQLDatabase();

?>