<?php
include "tools/preprint.php";
class read_js
{
  public $js_file;
  public $dic = "dic"; //change this variable to change the reader of the dictionary folder
  public $nome_dizionario = "dizionario_generico"; //TODO: da cambiare come argomento di input
  public function __construct()
  {
    $this->js_file = array();
  }


  /**
   * Get the path of every dictionary file
   * @params none
   * @return array
   */
  function getPath(){
    $dir_path = $this->dic;
    //enter in the dir_path and scan all the files that are in the folder
    $dir_handle = opendir($dir_path); //open the current directory where the dictionary files are stored
    // echo ($dir_handle);
    while (false!== ($file = readdir($dir_handle))) { //if directory exists and php can read it
      if ($file!= "." && $file!= "..") { //exclude parent directory
        $file_path = $dir_path. "/". $file; //get file path
        if (is_dir($file_path)) {
          echo $this->getPath($file_path); //recursive research
        } else {
          //push filepath in an array
          array_push($this->js_file, $file_path);
        }
      }
    }
    closedir($dir_handle);
    return $this->js_file; //return an array
  }

  /**
   * Read the dictionary file
   * @params none
  *
   */
  function read(){
    $path = $this->getPath(); //get the path of every dictionary file
    foreach ($path as $key => $value) {
      $this->js_file[$key] = file_get_contents($value);
    }
    return
        // json_decode(
          // json_encode(
            $this->clean($this->js_file[0]
          // )
        // ),
        // true
    ); //remove bad char from encoding
  }

  /**
   * just clean the current file
   * @params string
   * @return string
   */
  function clean($str){
    $str_array = explode("const $this->nome_dizionario =", $str); //remove const var =
    $str_array = explode("export { $this->nome_dizionario }", $str_array[1]); //remove const var =
    return $str_array[0];
  }
  //test
}

