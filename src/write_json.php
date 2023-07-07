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

    print_r($_POST['dictionary']);
    $file_name = "dictionary.json";
    $data = $_POST['dictionary'];
    if (!is_array($_POST['dictionary'])) {
      $data = json_decode($_POST['dictionary'], JSON_UNESCAPED_UNICODE);
    }
    $data = json_encode($data, JSON_PRETTY_PRINT, JSON_UNESCAPED_UNICODE);
    $data = decodeUtf8($data);
    file_put_contents("lib/$file_name", "\n\n\n" . $data, FILE_APPEND);
  }
}
$write_json = new write_json;
$write_json->write_json_to_file();
