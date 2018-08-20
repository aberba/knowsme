<?php

class Post {
      
      function fetch_posts($user_id) {
        global $Database;
        
       $query  = "SELECT M.*, P.*, F.* ";
       $query .= "FROM members M, posts P, friends F WHERE ";
       // case statement here
       $query .= "P.user_id_fk = M.user_id AND CASE ";
       $query .= "WHEN F.friend_one = '$user_id' ";
       $query .= "THEN F.friend_two = P.user_id_fk ";
       $query .= "WHEN F.friend_two = '$user_id' ";
       $query .= "THEN F.friend_one = P.user_id_fk END ";
       $query .= "AND F.status > '0' ";
       $query .= "ORDER BY P.date_posted DESC";
       
       $data   = $Database->query($query);
       
       if(!$data) return false;
       $posts = array();
       
       while ($row = $Database->fetch_data($data)) {
          $posts[] = array("post_id" => $row->post_id,
                           "name" => $row->first_name." ".$row->last_name,
                           "time" => $row->date_posted,
                           "post" => $row->post,
                           "plus" => $row->plus,
                           "minus" => $row->minus,
                           "comments" => $row->comments,
                           "date" => $this->generate_date($row->date_posted));
       }
       return $posts;
      }
      
      public function fetch_comments($post_id) {
          global $Database;
          
          $sql   = "SELECT M.*, C.* FROM members M, comments C ";
          $sql  .= "WHERE M.user_id = C.user_id_fk AND C.post_id_fk = ";
          $sql  .= "'$post_id' ORDER BY C.date_posted ASC";
          //echo $sql;
          
          $data = $Database->query($sql);
          if(!$data) return false;
          $comments = array();
          
          while ($row = $Database->fetch_data($data)) {
             $comments[] = array("name" => $row->first_name." ".$row->last_name,
                                 "comment" => $this->generate_comment($row->comment),
                                 "date" => $this->generate_date($row->date_posted));
          }
          //print_r($comments);
          if(empty($comments)) return false;
          return $comments; 
      }
      
      public function generate_comment($comment) {
          $happy = " <img src='".IMG_EMOT."/happy.png' /> ";
          $smile = " <img src='".IMG_EMOT."/smile.png' /> ";
          $sad = " <img src='".IMG_EMOT."/sad.png' /> ";
          $wink = " <img src='".IMG_EMOT."/wink.png' /> ";
          $angry = " <img src='".IMG_EMOT."/angry.png' /> ";
          
          $comment = str_replace(" :)",  $happy, $comment);
          $comment = str_replace(" :) ", $happy, $comment);
          $comment = str_replace(":) ",  $happy, $comment);
          $comment = str_replace(":)",   $happy, $comment);
          
          $comment = str_replace(" :(",  $sad, $comment);
          $comment = str_replace(" :( ", $sad, $comment);
          $comment = str_replace(":( ",  $sad, $comment);
          $comment = str_replace(":(",   $sad, $comment);
          
          $comment = str_replace(" ;)",  $wink, $comment);
          $comment = str_replace(" ;) ", $wink, $comment);
          $comment = str_replace(";) ",  $wink, $comment);
          $comment = str_replace(";)",   $wink, $comment);
          
          $comment = str_replace(" ^=^",  $angry, $comment);
          $comment = str_replace(" ^=^ ", $angry, $comment);
          $comment = str_replace("^=^ ",  $angry, $comment);
          $comment = str_replace("^=^",   $angry, $comment);
          return $comment;
      }
      
      public function generate_date($unix_timestamp) {
          return date("jS F Y \a\\t H:I  A", $unix_timestamp);
      }
      
      function find_by_id($post_id) {
        global $Database;
        $post_id = $Database->clean_data($post_id);
        
        $query    = "SELECT * FROM posts WHERE ";
        $query   .= "post_id = '$post_id' LIMIT 1";
        $data   = $Database->query($query);
        return $Database->fetch_data($data);
      }
}

$Posts = new Post();


?>