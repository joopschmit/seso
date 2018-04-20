<?php
  session_start();
	$id=$_POST['id'];
	$v=$_POST['v'];
	$language=$_SESSION['language'];
  if ($v==-1 || $v==1)
	{ 
    include('../classes/class_pages.php');
    $pages=new seso_pages($language);
    $pages->shifttilesub($id,$v);
  }
?>