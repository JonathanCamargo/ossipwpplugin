<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<h1>Ossip Restful plugin!</h1>
<h3>Jonathan Camargo <a href='mailto:jon-cama@gatech.edu'>jon-cama@gatech.edu</a></h3>

<!-- load all the current tables -->
<?php
echo "<h3>Current tables:</h3>";
global $wpdb;
$query='SELECT  name FROM ' . $wpdb->prefix . OssipTable::$configtable;
$results=$wpdb->get_results($query,OBJECT);
foreach ($results as $result){
	echo $result->name.'<br>';	
}

?>

<!-- add a new table -->
 <div id="addbtn">
 <button onclick="ShowDiv()">Add +</button>
 </div>
 <script>
	function ShowDiv() {
	    var x = document.getElementById("myDIV");
	    x.style.display = "block";
	    var x = document.getElementById("addbtn");
	    x.style.display = "none";

	} 
</script> 
<div hidden id="myDIV" >
 <form action=<?php echo get_admin_url(). 'admin-post.php'?> method='post'>
  Table name:<br>
  <input type="text" name="name" value="someTable">
  <br>
  Fields:<br>
  <input type="text" name="fields" value="field1,field2">
  <br>
  Field types:<br>
  <input type="text" name="types" value="numeric,text">
  <input type='hidden' name='action' value='submit-form'>
  <br><br>
  <?php submit_button($text="Create Table"); ?>
</form> 

</div>
  
