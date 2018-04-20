<?php
  session_start();
//	$language=$_SESSION['language'];
//  include('../classes/class_users.php');

  $_SESSION['signin']=array();
	$_SESSION['user']="";
//  $us=new seso_users($language);
//  $us->userexit(session_id()); 
  echo "<script>top.location='home.php';</script>";
?>