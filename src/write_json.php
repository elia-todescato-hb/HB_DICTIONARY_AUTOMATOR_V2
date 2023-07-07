<?php
//test


//TODO: da fare tab per evitare di far impazzire gabriel
chdir("/Users/etodescato/Documents/CHAT/"); //TODO: dinamico

class write_json
{
  public static function write_json_to_file()
  {

    print_r($_POST, 1);
    $file_name = "dictionary.json";
    $data = json_decode($_POST['dictionary']);
    $data = json_encode($data, JSON_PRETTY_PRINT);
    $fp = fopen("lib/$file_name", 'a');

    fwrite($fp, "\n\n\n" . $data);
    fclose($fp);
  }
}
$write_json = new write_json;
$write_json->write_json_to_file();
