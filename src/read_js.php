<?php
include "tools/preprint.php";
include "read_svelte.php";
include "tools/scan_dir.php";
class read_js
{
  public $js_file;
  public $dic = "frontend"; //change this variable to change the reader of the dictionary folder
  public $nome_dizionario = "dizionario_"; //TODO: da cambiare come argomento di input
  public function __construct()
  {
    $this->js_file = array();
  }


  /**
   * Get the path of every dictionary file
   * @params none
   * @return array
   */
  function getPath($dic){
    $dir_path = $dic;
    //enter in the dir_path and scan all the files that are in the folder
    $dir_handle = opendir($dir_path); //open the current directory where the dictionary files are stored



    while (false!== ($file = readdir($dir_handle))) { //if directory exists and php can read it
      while (scandir($file)){
        if(is_dir($file)){
          $this->getPath($file);
      }
    }
      if ($file != "." && $file != "..") { //exclude parent directory
        $file_info = pathinfo($file);
        $sub_folders = (scandir($dir_path));
        if ($file_info['extension'] == "js") {
          $file_path = $dir_path. "/". $file; //get file path

          if (is_dir($file_path)) {
            $this->dic = $dir_path;
            // preprint($this->dic);
            $this->getPath($dir_path); //recursive research
          } else {
            //push filepath in an array
            array_push($this->js_file, $file_path);
          }

        }else if($file_info['extension'] == 'svelte'){
          //try svelte
          $svelte = new read_svelte;
          $svelte->read();
        }else{
          preprint($file);
        }

    }
    closedir($dir_handle);
    return $this->js_file; //return an array
  // }
}
  /**
   * Read the dictionary file
   * @params none
  *
   */
  function read(){
    $path = $this->getPath($this->dic); //get the path of every dictionary file
    foreach ($path as $key => $value) {
      $this->js_file[$key] = file_get_contents($value);
    }
    return $this->clean($this->js_file[0]); //remove bad char from encoding
  }

  /**
   * just clean the current file
   * @params string
   * @return string
   */
  function clean($str){
    $str_array = explode("const $this->nome_dizionario", $str); //remove const var =
    $re = '/^(.*?)\{/ms';
    preg_match($re, $str_array[1], $matches);
    if($matches[1] == null){

    }else{
      $str_array = explode($matches[1], $str); //remove const var =
    }

    return $str_array[1];
  //test
  }
}