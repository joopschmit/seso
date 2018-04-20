<?php
	$sid=$_GET['sid'];
	$uid=$_GET['uid'];
	if ($_SERVER['HTTPS']=='on') { $pad='https://';} else { $pad='http://';}
	$pad.=$_SERVER['HTTP_HOST'];
	$uri=explode("/",$_SERVER['REQUEST_URI']);
	$pad.="/".$uri[1]."/";
	$language=0;
	include('classes/class_users.php');
	include('classes/class_labels.php');

  function closebutton($label)
  {	echo "<div class='button_active'>>";
		echo "<a class='submenu' HREF='Javascript: parent.close()'> ".$label."</a>";
		echo "</div>";
  }
	$users=new seso_users($language);
	if ($uid>0)  
	{ $user=$users->getuser($uid,'usergroup,language,authorized,sessionid');	
	  $language=$user['language'];
  	$labels = new seso_labels("checkresponse",$language);
	  if ($user['usergroup']!=$user['authorized'])
		{ if ($user['sessionid']==$sid)
			{ $users->saveuser($uid,array('authorized'=>$user['usergroup']));
	      $checktext=$labels->getlabel('checked');
			}
      else
      { $checktext=$labels->getlabel('oldcheck');}			
		}
    else
	  { $checktext=$labels->getlabel('alreadychecked');}
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

	echo "<body>";
	
  echo "<div id='maincockpit' class='maincockpit'>";
	echo "<div id='logo' class='logo'><img class='logo' src='img/seso_menulogo.gif'></div>";
	echo "</div>";
	echo "<div class='maintitle' id='maintitle' style='color:white;' >".$labels->getlabel("title")."</div>";
	
	echo "<div class='container'>";
	echo "<div class='datablock' style='padding-left:5px; color:black;'>";
	echo nl2br($checktext);
	echo "<br><br>"; 
  closebutton($labels->getlabel('close'));
  echo "</div></div>";

  echo "</body>";
  echo "</html>";
	