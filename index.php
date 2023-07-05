<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>

<?php
include "src/read_js.php";
$dictionary = new read_js();
$dictionary = substr($dictionary->read(), 1, -1);
?>

<script>
  var dictionary = <?php echo"$dictionary"?>
  dictionary = JSON.stringify(dictionary)
  //make ajax call
  $.ajax({
    type: "POST",
    url: "src/write_json.php",
    dataType: 'json',
    data: {
      dictionary: dictionary
    },
    success: function(data) {
      console.log(data);
    }
  });
</script>


<h1>
✅ Dictionary generated
</h1>



