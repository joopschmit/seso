 <?php
  session_start();
	$id=$_POST['id'];
	$language=$_SESSION['language'];
  
	include('../classes/class_pages.php');
  $pages=new seso_pages($language);
  
  $newid=$pages->addsubpage($id);
	echo $newid;
?>