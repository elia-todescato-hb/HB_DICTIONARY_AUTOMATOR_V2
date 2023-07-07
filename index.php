<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>

<?php
error_reporting(E_ERROR | E_PARSE);
include "tools/log/log.php";
include "src/read_js.php";
include "tools/utf8_encoding/utf8.php";
$dictionary = new read_js();
$dictionary = $dictionary->read();

$svelte = new read_svelte();
$svelte = $svelte->read();
?>

<?php
foreach ($dictionary as $key => $value) {
  echo "
<script>
  //ajax for js file
  var dictionary = $value
  console.log(dictionary);
  dictionary = JSON.stringify(dictionary)
  //make ajax call
  $.ajax({
    type: \"POST\",
    url: \"src/write_json.php\",
    dataType: 'json',
    data: {
      dictionary: dictionary
    },
    success: function(data) {
      console.log(data);
    }
  });
  </script>
  ";
}
?>

<?php
foreach ($svelte as $key => $value) {
  echo "
<script>
  //ajax for js file
  var dictionary_svelte = $value
  console.log(dictionary_svelte);
  dictionary = JSON.stringify(dictionary_svelte)
  //make ajax call
  $.ajax({
    type: \"POST\",
    url: \"src/write_json.php\",
    dataType: 'json',
    data: {
      dictionary: dictionary_svelte
    },
    success: function(data) {
      console.log(data);
    }
  });
  </script>
  ";
}
?>


<h1>
  ✅ Dictionary generated
</h1>