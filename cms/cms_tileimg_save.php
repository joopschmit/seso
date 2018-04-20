<?php
  session_start();
	$id=$_GET['id'];
  if (isset($_FILES['tileimage']['name']))
	{	$exts=array(".jpg",".png","gif");
		$fp="img/tileimg_".$id;
		foreach($exts as $extensie)
		{ if (file_exists($fp.$extensie))
			{ unlink($fp.$extensie);} 
		}
    $ext = strrchr($_FILES['tileimage']['name'], '.');

    $tileimage="tileimg_".$id.$ext;
		$uploadFile = $fp.$ext;
		$test=move_uploaded_file($_FILES['tileimage']['tmp_name'], $uploadFile);
		echo "<img src='".$fp.$ext."'>";
  }
	echo "<script>top.location='cms_tileimg_edit.php?id=".$id."';</script>";
?>