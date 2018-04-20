 <?php
  session_start();
	$id=$_GET['id'];
 	$language=$_SESSION['language'];
 
	include('../classes/class_pages.php');
  include('../classes/class_labels.php');
  $labels=new seso_labels("cms_tile_remove",$language);
  $pages=new seso_pages($language);
  $children=$pages->tileremove($id);
	
	if ($children!="")
	{ echo "<script>alert('".$labels->getlabel('irremovable').": '+'".$children."');</script>";}

  echo "<script>goto(1,'cms/cms_homepage.php','cms_homepage',0,0)</script>";
?>	