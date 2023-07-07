<?php


// class read_svelte
// {
//   public $svelte_file;
//   public $dic = "frontend"; //change this variable to change the reader of the dictionary folder
//   public $nome_dizionario = "dizionario_gestione_strutture"; //TODO: da cambiare come argomento di input
//   public function __construct()
//   {
//     $this->svelte_file = array();
//   }


//   /**
//    * Get the path of every dictionary file
//    * @params none
//    * @return array
//    */
//   function getPath(){
//     $dir_path = $this->dic;

//     //enter in the dir_path and scan all the files that are in the folder
//     $dir_handle = opendir($dir_path); //open the current directory where the dictionary files are stored
//     while (false!== ($file = readdir($dir_handle))) { //if directory exists and php can read it
//       if ($file!= "." && $file!= "..") { //exclude parent directory
//         $file_info = pathinfo($file);
//         if ($file_info['extension'] == "svelte") {
//           $file_path = $dir_path. "/". $file; //get file path
//           if (is_dir($file_path)) {
//             echo $this->getPath($file_path); //recursive research
//           } else {
//             //push filepath in an array
//             array_push($this->svelte_file, $file_path);
//           }
//         }
//       }
//     }
//     closedir($dir_handle);
//     return $this->svelte_file; //return an array
//   }

//   /**
//    * Read the dictionary file
//    * @params none
//   *
//    */
//   function read(){
//     $path = $this->getPath(); //get the path of every dictionary file
//     foreach ($path as $key => $value) {
//       $this->svelte_file[$key] = file_get_contents($value);
//     }
//     return
//       $this->clean($this->svelte_file[0]
//     ); //remove bad char from encoding
//   }

//   /**
//    * just clean the current file
//    * @params string
//    * @return string
//    */
//   function clean($str){
//     $str_array = explode("const $this->nome_dizionario =", $str); //remove const var =
//     $str_array = explode("</script>", $str_array[1]); //remove const var =
//     // preprint($str_array,1);
//     return $str_array[0];
//   }
//   //test
// }

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
    foreach ($array_of_dir as $key => $dir_path) { //ciuclo tutti i percorsi
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
    // info("sto pulendo:" . count($this->svelte_file) . " dizionari");
    // info("prima di pulire..");
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
    $fp = fopen("log.txt", 'w');
    foreach ($return_str as $key => $value) {
      # code...
      fwrite($fp, $value);
    }
    fclose($fp);

    return ($return_str);
  }
}
