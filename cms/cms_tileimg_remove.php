<?php
  session_start();
	$id=$_GET['id'];
	
	$exts=array(".jpg",".png","gif");
	$fp="img/tileimg_".$id;
	foreach($exts as $extensie)
	{ if (file_exists($fp.$extensie))
		{ unlink($fp.$extensie);} 
	}
?>	