<?php
function preprint($string = null, $die = 0 ){
  if (!is_null($string) && $die == 0) {
    
    echo "<pre>";
    print_r($string);
    echo "</pre> \n";
  
  }elseif (!is_null($string) && $die = 1) {
  
    echo "<pre>";
    print_r($string);
    echo "</pre> \n";
    die();
  
  }
}