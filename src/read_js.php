<?php
ini_set('memory_limit', '2G');

include "tools/preprint.php";
include "read_svelte.php";
include "scan_dir.php";

preprint("<h1>Checking if all include are correct... 🔍</h1>");
if (file_exists("scan_dir.php") && file_exists("tools/preprint.php") && file_exists("tools/preprint.php")) {
  preprint("<h2>✅ All includes are correct</h2>");
}
class read_js
{
  public $js_file;
  public $dic = "frontend"; //change this variable to change the reader of the dictionary folder
  public $nome_dizionario = "dizionario_";
  public function __construct()
  {
    $this->js_file = array();
  }


  /**
   * Get the path of every dictionary file
   * @params none
   * @return array
   */
  function getPath()
  {
    //enter in the dir_path and scan all the files that are in the folder
    $folder_scan = new scan_dir();
    $array_of_dir = $folder_scan->scan($this->dic);

    info("Scanning...");
    foreach ($array_of_dir as $key => $dir_path) {
      $folder = pathinfo($dir_path);
      if (key_exists('extension', $folder)) {
        if ($folder['extension'] == "js" && str_contains($folder['basename'], "dizionario")) {
          array_push($this->js_file, "{$folder['dirname']}/{$folder['basename']}");
        }
      }
    }
    return ($this->js_file);
  }
  /**
   * Read the dictionary file
   * @param none
   * @return string
   */
  function read()
  {
    $path = $this->getPath(); //get the path of every dictionary file
    info("leggendo queste path: ");

    foreach ($path as $key => $value) {
      info("eseguendo ... " . $value);
      $this->js_file[$key] = file_get_contents($value);
      info("findendo ... " . $value);
    }
    info("sto pulendo:" . count($this->js_file) . " dizionari");
    info("prima di pulire..");
    return $this->clean($this->js_file); //remove bad char from encoding
  }

  /**
   * just clean the current file
   * @params string
   * @return string
   */
  function clean($str)
  {
    $str_array = array();
    $return_str = array();
    foreach ($str as $raw_dic) {
      $str_array = explode("const $this->nome_dizionario", $raw_dic);
      $re = '/^(.*?)\{/ms';
      preg_match($re, $raw_dic, $matches);
      if ($matches[1] !== null) {
        $str_array = explode($matches[1], $raw_dic);
        $str_array = explode("export", $str_array[1]);
        $nome_dizionario = $str_array[1];
        info("sto pulendo {$nome_dizionario}");
        // info($str_array[0]);
        // preprint($str_array);
        array_push($return_str, $str_array[0]);
      }
    }
    info("finito di pulire..");
    return ($return_str);
  }
}
