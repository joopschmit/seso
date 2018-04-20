<?php
  session_start();
	$language=$_SESSION['language'];
	include('../../inc/show.inc');
  include('../../classes/class_labels.php');
	include('../../classes/class_match.php');
	$page='app_match';
	
  $labels=new seso_labels("app_match",$language);
	$match=new seso_match($page,$language);
	
  echo "<div class='container'>";
	sh_grouptitle($labels->getlabel('match')); 
 
  echo "<br></div>";
?>