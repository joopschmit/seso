<?php
  session_start();
  $id=$_GET['id'];
	$language=$_SESSION['language'];
	include('../classes/class_pages.php');
  $pages=new seso_pages($language);
  $pageid=$_SESSION['set']['am']['pages']['pageid'];	
  $labels = new seso_pages($language);

	$pages->pagelabelremove(0,$id);
  
	echo "<script>goto(1,'am/am_page_sheet.php','am_page_sheet',".$pageid.",0);</script>";
?>
