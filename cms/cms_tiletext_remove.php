 <?php
  session_start();
	$id=$_GET['id'];
 	$language=$_SESSION['language'];
	include('../classes/class_pages.php');
  $pages=new seso_pages($language);
  $tile=$pages->gettilerecord($id,"language,parent");
	$pages->removesubtext($id);
	
	echo "<script>goto(1,'cms/cms_tile_sheet.php','cms_tile_sheet',".$tile[0]['parent'].",0)</script>";
?>	