<?php
	session_start();
  ini_set('error_reporting', E_ALL);
	include("inc/datefunctions.inc");
  include('classes/class_visitors.php');
	$vs=new seso_visitors();
	if ($vs->check_visitor(session_id(),"","")>0)
	{ header('location:home.php');
    exit;
	}	
	$vs->saverequest(session_id(),$_SERVER,actualdatecode());
	$fase=-1;
 	if (isset($_GET['lang'])) 
	{ $language=$_GET['lang'];} 
	else 
	{ if (isset($_COOKIE['seso_language'])) 
	  { $language=$_COOKIE['seso_language'];}
	  else 
		{ $language=0;}
	}	
	$_SESSION['language']=$language;
  setcookie("seso_language",$language,time()+60*60*24*30); //30 days cookie

	$pad="";
	if ($_SERVER['SERVER_NAME']!='localhost')
	{	if ($_SERVER['HTTPS']=='on') { $pad='https://';} else { $pad='http://';}
		$pad.=$_SERVER['HTTP_HOST'];
	}
	else { $pad="http://".$_SERVER['HTTP_HOST'];}
	$uri=explode("/",$_SERVER['REQUEST_URI']);
	$pad.="/".$uri[1]."/";

	include('inc/show.inc');
	include("classes/class_labels.php");
	$labels=new seso_labels('mainentrance',$language);

	echo "<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01//EN' 'http://www.w3.org/TR/html4/strict.dtd'>\n";
	echo "<html>\n";
	echo "<head>\n";
	echo "<title>Sentro Solari</title>";

	echo "\n<meta http-equiv='content-type' content='text/html; charset=cp1252'>";
	echo "\n<meta name='author' content='Business Relation Manager'>";
	echo "\n<meta name='copyright' content='(c) 2018 - Moval'>";
	echo "\n<meta name='format-detection' content='telephone=yes'>";
	echo "\n<meta name='apple-mobile-web-app-capable' content='yes'>";
	echo "\n<meta name='mobile-web-app-capable' content='yes'>";
	echo "\n<meta name = 'viewport' content = 'user-scalable=yes,initial-scale=1, width=device-width'>";
	echo "\n<meta names='apple-mobile-web-app-status-bar-style' content='black-translucent' />";
	echo "\n<meta names='mobile-web-app-status-bar-style' content='black-translucent' />";
	echo "\n<link href='".$pad."favicon.ico' rel='shortcut icon' type='image/x-icon' />";
	echo "\n<link href='".$pad."ssicon120x120.png' rel='apple-touch-icon' />";
	echo "\n<link href='".$pad."ssicon152x152.png' rel='apple-touch-icon' sizes='152x152' />";
	echo "\n<link href='".$pad."ssicon167x167.png' rel='apple-touch-icon' sizes='167x167' />";
	echo "\n<link href='".$pad."ssicon180x180.png' rel='apple-touch-icon' sizes='180x180' />";
	echo "\n<link href='".$pad."ssicon128x128.png' rel='icon' sizes='192x192' />";
	echo "\n<link href='".$pad."ssicon192x192.png' rel='icon' sizes='128x128' />";	
	echo "\n<link rel='icon' href='".$pad."ssicon.png'>";
	echo "\n<link href='".$pad."style/style.css?tm=".time()."' rel='stylesheet' type='text/css'>";

	echo "\n<script type='text/javascript' src='inc/jquery.js?x=1'></script>";
	echo "<script>
		function set_language(lang)
		{ top.location='index.php?lang='+lang;
		}	
		function signup()
		{ email=$('#email').val();
			pw=$('#pw').val();
			$.post('request.php',{email:email,pw:pw},function(check) 
				{ if (check>0) 
					{ top.location='home.php';} 
					else
					{ if (check==-1) { alert('Uw verzoek is verzonden. U ontvangt zo snel een email met toegangsgegevens.');}
						else { alert('Met deze gegevens heeft u geen toegang.');}
					}
				}
			);
		}	
  </script>"; 
	echo "\n</head>\n\n";

	echo "<body class='main' id='main' onload='setTimeout(function() { top.scrollTo(0, 1) }, 100);'>";
	echo "<style>
	div.info     {padding:18px;color:#003F7F;vertical-align:top;text-align:left;max-width:500px;font-size:12pt;}
	div.ulabel   {padding: 5px;color:#003F7F;vertical-align:top;text-align:left;width:180px;}
	div.udata    {padding: 5px;color:#003F7F;vertical-align:top;text-align:left;width:200px;}
	div.ubutton  {padding:5px;margin-left:5px;color:#003F7F;text-align:center;width:120px;height:30px;background-color:#003F7F;}
	a.button     {color:white;}
	</style>";	
  
	//start mainhead
	echo "<div class='mainhead'>";
	echo "<div id='maincockpit' class='maincockpit'>";
	echo "<div id='logo' class='logo'><img class='logo' src='img/seso_menulogo.gif'></div>";
	echo "<div id='languages' class='languages'>";
	foreach($labels->languages as $nr=>$lang)
  { echo "<a href='#' onclick='set_language(".$nr.");'><img src='img/language".$nr.".png' class='languages'></a>&nbsp";}
	echo "</div>"; 
	echo "</div>";
	
	echo "<div class='maintitle' id='maintitle'>";
	echo "<table>";
	echo "<tr><td><div class='pagetitle' id='pagetitle'>";
	echo $labels->getlabel("websitetitle"); 
	echo "</div></td>";
	echo "</tr></table>"; 
	echo "</div>";
	echo "</div>";
  //end mainhead
	
	echo "<div id='mainbasis' class='mainbasis'>";

	echo "<div class='mainmenu'>";
  echo "<div class='mainmenutile'>";
			
	sh_grouptitle($labels->getlabel('underconstruction'));
	echo "<div class='info'>".nl2br($labels->getlabel('request'))."</div>";
	echo "<div style='padding-left:12px; width:250px;' >";
  $username='';
	echo "<div class='ulabel'>".$labels->getlabel('emailaddress')."</div>";
	echo "<div class='udata'><input class='signin'  autocomplete='on' type='text' value='".$username."' name='email' id='email' size='50' maxlength='50'></div>";
	echo "<div class='ulabel'>".$labels->getlabel('code')."</div>";
	echo "<div class='udata'><input class='signin' autocomplete='on' value='abcdefg' type='password' name='pw' id='pw' size='50' maxlength='50'></div>";
	echo "<br><br>";
	echo "<div class='ubutton'><a href='#' onclick='signup();' class='button'>".$labels->getlabel('signin')."</a></div>";
	
	echo "<br></div>"; //mainmenutile
  
	echo "</div>"; //container

  echo "</div>"; //mainbasis  

	echo "</body></html>";

?>