<?php

/**
* Process form to add a new Ossip table
 */

function processform(){
	echo "asdad";
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

$name=$fields=$types='';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = test_input($_POST["name"]);
  $fields = test_input($_POST["fields"]);
  $types = test_input($_POST["types"]);
}

echo "$name";
echo "jholasdkoaskd";

  ?>

