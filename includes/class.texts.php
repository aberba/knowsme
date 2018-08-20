<?php

class Texts {
    
    public function add_emoticons($comment) {
          $happy = " <img src='".IMG_EMOT."/happy.png' /> ";
          $smile = " <img src='".IMG_EMOT."/smile.png' /> ";
          $sad   = " <img src='".IMG_EMOT."/sad.png' /> ";
          $wink  = " <img src='".IMG_EMOT."/wink.png' /> ";
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
}

$Texts = new Texts();

?>