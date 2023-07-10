<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>

<?php
error_reporting(E_ERROR | E_PARSE);
include "tools/log/log.php";
include "src/read_js.php";
include "tools/utf8_encoding/utf8.php";

echo 'Current script owner: ' . get_current_user();


$dictionary = new read_js();
$dictionary = $dictionary->read();

$svelte = new read_svelte();
$svelte = $svelte->read();
?>
<span>
  <code id="BoxErrori">

  </code>
</span>
<?php
foreach ($dictionary as $key => $value) {
  echo "
  <script>
  //ajax for js file
  var dictionary_svelte = $value[0]
  var path = \"$value[1]\"
  
  dictionary = JSON.stringify(dictionary_svelte)
  //make ajax call
  $.ajax({
    type: \"POST\",
    url: \"src/write_json.php\",
    dataType: 'json',
    data: {
      dictionary: dictionary_svelte,
      path: path
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
  //ajax for svelte file
  try {
    var dictionary_svelte = $value[0]
  } catch (error) {
    var dictionary_svelte = {}
    var box_errori = document.getElementById('BoxErrori')
    document.createElement('br');
    box_errori.append('ERROR: errore con: $value[1] ');

  }
  console.log(dictionary_svelte)
  var path = \"$value[1]\"

  dictionary = JSON.stringify(dictionary_svelte)
  //make ajax call
  $.ajax({
    type: \"POST\",
    url: \"src/write_json.php\",
    dataType: 'json',
    data: {
      dictionary: dictionary_svelte,
      path: path
    },
    success: function(data) {
      console.log(data);
    }
  });
  </script>
  ";
}
?>

<script>
</script>


<h1>
  ✅ Dictionary generated
</h1>