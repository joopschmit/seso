<?php
	session_start();
	$helpID=$_GET['helpID'];
	$language=$_GET['language']; 
	include('../inc/dbhulp.inc');
	include('../inc/datefunctions.inc');
	include('../inc/show.inc');
	include("../classes/class_labels.php");
	$labels=new seso_labels('help',$language);

	if ($_SERVER['HTTPS']=='on') { $pad='https://';} else { $pad='http://';}
	$pad.=$_SERVER['HTTP_HOST'];
	$uri=explode("/",$_SERVER['REQUEST_URI']);
	$pad.="/".$uri[1]."/";

  function closebutton($label)
  {	echo "<div class='button_active'>>";
		echo "<A class='submenu' HREF='Javascript: parent.close()'> ".$label."</A>";
		echo "</div>";
  }

  $script="<script>window.focus()</script>";
	echo "<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01//EN' 'http://www.w3.org/TR/html4/strict.dtd'>\n";
	echo "<html>\n";
	echo "<head>\n";
	echo "<title>Sentro Solari</title>\n";
	echo "<meta http-equiv='content-type' content='text/html; charset=iso-8859-1'>\n";
	echo "<link rel='shortcut icon' href='".$pad."favicon.ico' type='image/x-icon' />";
	echo "\n<meta name='author' content='Business Relation Manager'>";
	echo "\n<meta name='copyright' content='(c) 2010 - Moving Values'>";
	echo "\n<meta name='format-detection' content='telephone=yes'>";
	echo "\n<meta name='apple-mobile-web-app-capable' content='yes'>";
	echo "\n<meta name='mobile-web-app-capable' content='yes'>";
	echo "\n<meta name = 'viewport' content = 'user-scalable=yes,initial-scale=1, width=device-width'>";
	echo "\n<meta names='apple-mobile-web-app-status-bar-style' content='black-translucent' />";
	echo "\n<meta names='mobile-web-app-status-bar-style' content='black-translucent' />";
	echo "<link rel='icon' href='".$pad."ssicon.png'>";
	echo "<link rel='apple-touch-icon' href='".$pad."ssicon.png'>";
	echo "<link rel='apple-touch-icon-precomposed' href='".$pad."ssicon.png'>";
	echo "\n<link href='".$pad."style/style.css?tm=".time()."' rel='stylesheet' type='text/css'>";
	echo "\n<script type='text/javascript' src='../inc/jquery.js'></script>";
	echo "\n<script type='text/javascript' src='../inc/seso.js'></script>";
	echo "\n</head>\n\n";	

	$helpinfo=$labels->gethelpinfo($helpID,$language);
	
	echo "<body>";
  echo "<div class='titlerow'><div class='pagetitle'>".$labels->getlabel('title').": ".$helpinfo['text']."</div></div>";
	echo "<div class='container'>";
	echo "<div class='datablock' style='padding-left:5px; color:black;'>";
	echo nl2br($helpinfo['help']);
	echo "<br><br>"; 
  closebutton($labels->getlabel('close'));
  echo "</div></div>";

  echo "</body>";
  echo "</html>";
?>
