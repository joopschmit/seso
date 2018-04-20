<?php
  session_start();
  $id=$_GET['id'];
	$language=$_SESSION['language'];
	include('../classes/class_pages.php');
  $pages=new seso_pages($language);
	
	$pages->pageremove($id);
	echo "<script>goto(1,'am/am_pages.php','am_pages',0,0);</script>";
?>