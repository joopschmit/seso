<?php
  session_start();
  ini_set('error_reporting', E_ALL);
	$id=$_GET['id'];
	$language=$_SESSION['language'];

	include('../inc/show.inc');
	include('../inc/show_input.inc');
  include('../classes/class_labels.php');
	include('../classes/class_pages.php');

  $pages=new seso_pages($language);
  $labels=new seso_labels("cms_tileimg_edit",$language);
	$tile=$pages->gettilerecord($id,'language,position');
	$number=$tile[0]['position']+1;
  $tileimg=$pages->gettileimg($id);

	if ($_SERVER['HTTPS']=='on') { $pad='https://';} else { $pad='http://';}
	$pad.=$_SERVER['HTTP_HOST'];
	$uri=explode("/",$_SERVER['REQUEST_URI']);
	$pad.="/".$uri[1]."/";
		
	echo "<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01//EN' 'http://www.w3.org/TR/html4/strict.dtd'>\n";
	echo "<html>\n";
	echo "<head>\n";
	echo "<title>Sentro Solari</title>\n";

	echo "<meta http-equiv='content-type' content='text/html; charset=cp1252'>\n";
	echo "\n<meta name='author' content='Business Relation Manager'>";
	echo "\n<meta name='copyright' content='(c) 2018 - Moval'>";
	echo "\n<meta name='format-detection' content='telephone=yes'>";
	echo "\n<meta name='apple-mobile-web-app-capable' content='yes'>";
	echo "\n<meta name='mobile-web-app-capable' content='yes'>";
	echo "\n<meta name = 'viewport' content = 'user-scalable=yes,initial-scale=1, width=device-width'>";
	echo "\n<meta names='apple-mobile-web-app-status-bar-style' content='black-translucent' />";
	echo "\n<meta names='mobile-web-app-status-bar-style' content='black-translucent' />";
	echo "<link href='".$pad."favicon.ico' rel='shortcut icon' type='image/x-icon' />";
	echo "<link href='".$pad."ssicon120x120.png' rel='apple-touch-icon' />";
	echo "<link href='".$pad."ssicon152x152.png' rel='apple-touch-icon' sizes='152x152' />";
	echo "<link href='".$pad."ssicon167x167.png' rel='apple-touch-icon' sizes='167x167' />";
	echo "<link href='".$pad."ssicon180x180.png' rel='apple-touch-icon' sizes='180x180' />";
	echo "<link href='".$pad."ssicon128x128.png' rel='icon' sizes='192x192' />";
	echo "<link href='".$pad."ssicon192x192.png' rel='icon' sizes='128x128' />";	
	echo "<link rel='icon' href='".$pad."ssicon.png'>";
	echo "\n<link href='".$pad."style/style.css?tm=".time()."' rel='stylesheet' type='text/css'>";
	echo "\n<script type='text/javascript' src='../inc/jquery.js?x=1'></script>";
	echo "\n<script type='text/javascript' src='../inc/seso.js?tm=".time()."'></script>";
	echo "\n</head>\n\n";

	echo "<script type='text/javascript' language='javascript'>
		<!--
		function get_image(field)
		{ $('#imgform').submit();
	  }
		function removeimage()
		{ $.get('cms_tileimg_remove.php?id=".$id."',
		    function() { $('#image').html('');});
		}	
	 //-->
	</script>";
	
	echo "<body>";

	echo "<div class='mainhead'>";
	echo "<div id='maincockpit' class='maincockpit'>";
	echo "<div id='logo' class='logo'><img class='logo' src='../img/seso_menulogo.gif'></div>";
  echo "</div></div>";
	echo "<div class='container' style='height:calc(100% - 50px);'>";
  echo "<div class='mainmenutile'>";
	echo "<div class='datablock' style='padding-left:5px; color:black;'>";
	sh_title($labels->getlabel('title')." ".$number);
	echo "<form id='imgform' method='post' enctype='multipart/form-data'  action='cms_tileimg_save.php?id=".$id."'>";
	sh_grouptitle($labels->getlabel('image'));
	echo "<div id='image'>";
	if ($tileimg!="")
	{ echo "<img src='../".$tileimg."' style='height:180px;text-align:left;'>";}
	echo "</div>";
	echo "<div style='padding-left:7px; font-size:7pt; color:#808080;font-style:italic;'>size: jpg 120 x 120</div>";
	echo "<br>";
	echo "<input name='tileimage' type='file' onchange='get_image(this);' style='border:0; value='knop'>";
	echo "<div style='padding:7px;'>";
	echo "</div></form>";

	if ($tileimg!="")
  { echo "<div class='button_active'>>";
	  echo "<a class='submenu' href='#' onclick='removeimage();'> ".$labels->getlabel('removeimage')."</a>";
	  echo "</div>";
  }
	echo "<div class='button_active'>>";
	echo "<a class='submenu' href='Javascript: parent.close()'> ".$labels->getlabel('close')."</a>";
	echo "</div>";
	
	echo "</div></div></div></body></html>";
?>