<?php
  session_start();
  $id=$_POST['id'];
  $fieldname=$_POST['v'];
  $value=$_POST['w'];
	$language=$_SESSION['language'];
	include('../classes/class_pages.php');
  $pages=new seso_pages($language);
	$test=0;
	if ($fieldname=='newpage') { $test=$pages->pagecheck(0,$id,'page',$value);}
	if ($fieldname=='newpagelabel') { $test=$pages->pagecheck(0,$id,'name',$value);}
	
	if ($test==0 && $fieldname!="")
  { $id=$pages->pagesave(0,$id,$fieldname,$value);
    echo $id;
  }
  else
  { $ansr="";
    if ($test==1) { $ansr=-10;}
    if ($test==2) { $ansr=-11;}
    echo $ansr; 
  }
?>