<?php
  session_start();
  $id=$_GET['id'];
	$language=$_SESSION['language'];
	include('../classes/class_pages.php');
  $pages=new seso_pages($language);
	
	$pages->varremove(1,$id);
	echo "<script>goto(1,'am/am_vars.php','am_vars',0,0);</script>";
?>