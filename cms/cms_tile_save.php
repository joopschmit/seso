<?php
  session_start();
	$id=$_POST['id'];
	$f=$_POST['f'];
	$v=$_POST['v'];
	$language=$_SESSION['language'];

	include('../classes/class_pages.php');

  $pages=new seso_pages($language);
 
	$data=explode("_",$f);
  if ($f=='tileparent')
  { $pages->tileshift($id,$v);
	}
  if ($f=='tileposition')
  { $pos=$v-1;
    $pages->tileshift_position($id,$pos);
	}
  if ($f=='value')
  { $pages->savetilevalue($id,$v);
	}
	if ($f=='tilename')
	{ $name=str_replace(" ","_",$v);
    $pages->savetilename($id,$name);
	} 
	if (substr($f,0,10)=='tiletitle_')
	{ $lang=$data[1];
    $pages->savetiletitle($id,$v,$lang);
	} 
  if (substr($f,0,8)=='tiletext')
	{ if (substr($f,0,9)=='tiletext_') { $field='text';} else { $field='info';}
    $lang=$data[1];
    $pages->savetiletext($field,$id,$v,$lang);
	} 
  if (substr($f,0,9)=='tilevideo')
	{ if (substr($f,0,10)=='tilevideo_') { $field='text';} else { $field='info';}
    $lang=$data[1];
    $pages->savetilevideo($field,$id,$v,$lang);
	} 
		
?>