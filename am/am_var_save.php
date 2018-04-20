<?php
  session_start();
//print_r($_POST);
  $id=$_POST['id'];

  $fieldnames=$_POST['f'];
  $value=$_POST['v'];
	$language=$_SESSION['language'];
	include('../classes/class_pages.php');

  $pages=new seso_pages($language);
	$test=0;
	$fn=explode("_",$fieldnames);
	$field=$fn[0];
	if (isset($fn[1])) { $language=$fn[1];}

  if ($id==-1)
	{ $newid=$pages->addvar($value);
    echo $newid;
  }
  else
	{ $pages->varsave($id,$field,$value,$language);}
?>