<?php
  session_start();
	$language=$_SESSION['language'];
	include('../../inc/show.inc');
  include('../../classes/class_labels.php');
	include('../../classes/class_info.php');
	$page='app_news';
	
  $labels=new seso_labels("app_news",$language);
	$info=new seso_info($page,$language);
	
  echo "<div class='container'>";
	sh_grouptitle($labels->getlabel('news')); 
 
  echo "<br></div>";
?>