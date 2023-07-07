<?php

class read_svelte
{
  public $svelte_file;
  public $dic = "frontend"; //change this variable to change the reader of the dictionary folder
  public $nome_dizionario = "dizionario_"; //TODO: da cambiare come argomento di input
  public function __construct()
  {
    $this->svelte_file = array();
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
    info("<h1>Searching for svelte file</h1>");
    info("Scanning...");
    foreach ($array_of_dir as $key => $dir_path) { //ciclo tutti i percorsi
      $folder = pathinfo($dir_path);
      if (key_exists('extension', $folder)) { //allora stiamo valutando un file
        //open dir path that i have
        // info("la chiave con valore [extension] esiste e corrisponde al path: {$folder['dirname']}");
        if ($folder['extension'] == "svelte") {
          array_push($this->svelte_file, "{$folder['dirname']}/{$folder['basename']}");
        }
      }
    }
    return ($this->svelte_file);
  }
  /**
   * Read the dictionary file
   * @params none
   *
   */
  function read()
  {
    $path = $this->getPath(); //get the path of every dictionary file
    foreach ($path as $key => $value) {
      $this->svelte_file[$key] = file_get_contents($value); //TODO: cambia con value

    }
    return $this->clean($this->svelte_file); //remove bad char from encoding
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
      $str_array = explode('<script context="module"', $raw_dic); //remove const var =
      $re = '/^(.*?)\{/ms';

      if ($str_array != null) {
        preg_match($re, $str_array[1], $matches);
        // $str_array = explode($matches[1], $str_array[1]); //remove const var =

        $re = '/\{(?:[^{}]+|\{(?:[^{}]+|\{(?:[^{}]+|\{[^{}]*\})*\})*\})*\}/m';
        preg_match($re, $str_array[1], $matches);

        $json = $matches[0];
        if ($matches[0] !== null) {
          array_push($return_str, $matches[0]); //TODO: da cambiare con indice 0
        }
        //clean also the export js function
      }
      info("finito di pulire..");
    }

    return ($return_str);
  }
}
