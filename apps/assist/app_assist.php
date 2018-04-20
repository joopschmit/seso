<?php
  session_start();
	$language=$_SESSION['language'];
	include('../../inc/show.inc');
  include('../../classes/class_labels.php');
	include('../../classes/class_assist.php');
	$page='app_assist';
	
  $labels=new seso_labels("app_assist",$language);
	$assist=new seso_assist($page,$language);
	
  echo "<div class='container'>";
	sh_grouptitle($labels->getlabel('assist')); 
 
  echo "<br></div>";
?>