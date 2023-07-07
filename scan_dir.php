<?php

class scan_dir{
  /**
   * $file = array()
   * is the output of all files recursive
   */
  public $file = array();

  /**
   * Scan all dir from the dir passed
   * @param string : dir
   * @param int : deep = to enter in sub_dir
   * @return array : $this->file
   */
  function scan($dir, $deep = 0) {
    $list = scandir($dir);
    $list = array_diff($list, array(".", "..")); // remove . and ..

    foreach($list as $sub_dir_file) {
      // $file[] += "$dir/$sub_dir_file";
      array_push($this->file,"$dir/$sub_dir_file");

      if(is_dir("$dir/$sub_dir_file")) {
        $this->scan("$dir/$sub_dir_file", $deep + 1); // recursive function
      }
    }
    return $this->file;
  }
}

// $file = new scan_dir();
// print_r($file->scan("frontend"));
?>