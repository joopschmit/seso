<?php
  session_start();
	$language=$_SESSION['language'];
	include('../../inc/show.inc');
  include('../../classes/class_labels.php');
	include('../../classes/class_persons.php');
	$page='app_persons';
	
  $labels=new seso_labels("app_persons",$language);
	$persons=new seso_persons($page,$language);
	
  echo "<div class='container'>";
	sh_grouptitle($labels->getlabel('persons')); 
 
  echo "<br></div>";
?>