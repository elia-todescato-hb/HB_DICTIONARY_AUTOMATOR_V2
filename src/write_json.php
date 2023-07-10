<?php
//test

function decodeUtf8($string)
{
  return preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/', function ($matches) {
    return mb_convert_encoding(pack('H*', $matches[1]), 'UTF-8', 'UCS-2BE');
  }, $string);
}

//TODO: da fare tab per evitare di far impazzire gabriel
chdir("/Users/" . get_current_user() . "/Documents/CHAT/"); //TODO: dinamico
class write_json
{
  public static function write_json_to_file()
  {

    $file_name = "dictionary.json";
    $data = !empty($_POST['dictionary']) ? $_POST['dictionary'] : array();
    $path = $_POST['path'];
    $data = ($data == null) ? array() : $data;

    if (!is_array($data) && !array($_POST['path'])) {
      $data = json_decode($_POST['dictionary'], JSON_UNESCAPED_UNICODE);
      $path = json_decode($_POST['path'], JSON_UNESCAPED_UNICODE);
    } else if ($data == array()) {
      $data = "[ERROR] FILE IN ERRRORE DI LETTURA: " . $path . "\n";
      file_put_contents($data, FILE_APPEND);
    }
    $data = json_encode($data, JSON_PRETTY_PRINT, JSON_UNESCAPED_UNICODE);
    $data = $path . "\n" . decodeUtf8($data);
    file_put_contents("lib/$file_name", "\n\n\n" . $data, FILE_APPEND);
  }
}
$write_json = new write_json;
$write_json->write_json_to_file();
