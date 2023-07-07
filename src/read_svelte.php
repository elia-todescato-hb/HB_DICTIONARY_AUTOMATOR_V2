<?php

class read_svelte
{
  public $svelte_file;
  public $dic = "frontend"; //change this variable to change the reader of the dictionary folder
  public $nome_dizionario = "dizionario_";
  public function __construct()
  {
    $this->svelte_file = array();
  }


  /**
   * Get the path of every dictionary file
   * @param none
   * @return array
   */
  function getPath()
  {
    //enter in the dir_path and scan all the files that are in the folder
    $folder_scan = new scan_dir();
    $array_of_dir = $folder_scan->scan($this->dic);

    info("<h1>Searching for svelte file</h1>");
    info("Scanning...");

    foreach ($array_of_dir as $key => $dir_path) {
      $folder = pathinfo($dir_path);
      if (key_exists('extension', $folder)) {
        if ($folder['extension'] == "svelte") {
          array_push($this->svelte_file, "{$folder['dirname']}/{$folder['basename']}");
        }
      }
    }
    return ($this->svelte_file);
  }
  /**
   * Read the dictionary file
   * @param none
   * @return array
   */
  function read()
  {
    $path = $this->getPath(); //get the path of every dictionary file
    foreach ($path as $key => $value) {
      $this->svelte_file[$key] = file_get_contents($value);
    }
    return $this->clean($this->svelte_file); //remove bad char from encoding
  }

  /**
   * just clean the current file
   * @param string
   * @return string
   */
  function clean($str)
  {

    $str_array = array();
    $return_str = array();

    foreach ($str as $raw_dic) {
      $str_array = explode('<script context="module"', $raw_dic); //remove const var =
      $re = '/^(.*?)\{/ms';

      if ($str_array != null) {
        preg_match($re, $str_array[1], $matches);

        $re = '/\{(?:[^{}]+|\{(?:[^{}]+|\{(?:[^{}]+|\{[^{}]*\})*\})*\})*\}/m';
        preg_match($re, $str_array[1], $matches);

        $json = $matches[0];
        if ($matches[0] !== null) {
          array_push($return_str, $matches[0]); //TODO: da cambiare con indice 0
        }
      }
      info("finito di pulire..");
    }

    return ($return_str);
  }
}
