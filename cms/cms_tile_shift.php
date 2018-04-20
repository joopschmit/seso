<?php
  session_start();
	$tile_id=$_POST['id'];
	$to_parent=$_POST['to'];
	$language=$_SESSION['language'];
  include('../classes/class_pages.php');
  $pages=new seso_pages($language);
  $pages->tileshift($tile_id,$to_parent);
?>