<?php
  session_start();
	$language=$_SESSION['language'];
	include('../../inc/show.inc');
  include('../../classes/class_labels.php');
	include('../../classes/class_work.php');
	$page='app_work';
	
  $labels=new seso_labels("app_work",$language);
	$work=new seso_work($page,$language);
	
  echo "<div class='container'>";
	sh_grouptitle($labels->getlabel('work')); 
 
  echo "<br></div>";
?>