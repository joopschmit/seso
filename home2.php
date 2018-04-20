 <?php
	session_start();
  ini_set('error_reporting', E_ALL);
	include("inc/datefunctions.inc");
  include('classes/class_visitors.php');
	$vs=new seso_visitors();
	$check=$vs->getauthorized(session_id());
	if ($check>0)
  { 
			/*
		if (count($_GET)>0 || count($_POST)>0) 
		{ if (strpos($pad,'rm_dev')>0) { $map='rm_dev';} else { $map="rm";}
			header("location:/".$map."/inc/seso_afsluiten.php");
			exit;
		} 
	  */ 
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

		if ($_SERVER['HTTPS']=='on') { $pad='https://';} else { $pad='http://';}
		$pad.=$_SERVER['HTTP_HOST'];
		$uri=explode("/",$_SERVER['REQUEST_URI']);
		$pad.="/".$uri[1]."/";
		
		include('inc/show.inc');
		include("classes/class_labels.php");
		$labels=new seso_labels('mainmenu',$language);
		
		echo "<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01//EN' 'http://www.w3.org/TR/html4/strict.dtd'>\n";
		echo "<html>\n";
		echo "<head>\n";
		echo "<title>Sentro Solari</title>\n";

		echo "<meta http-equiv='content-type' content='text/html; charset=iso-8859-1'>\n";
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
		echo "\n<script type='text/javascript' src='inc/jquery.js?x=1'></script>";
		echo "\n<script type='text/javascript' src='inc/seso.js?tm=".time()."'></script>";
		echo "\n</head>\n\n";

		echo "<body class='main' id='main' onload='setTimeout(function() { top.scrollTo(0, 1) }, 100);'>";
		echo "<div id='signal' class='signal'></div>";
		//start mainhead
		echo "<div class='mainhead'>";
		echo "<div id='maincockpit' class='maincockpit'>";
		echo "<div id='logo' class='logo'><img class='logo' src='img/seso_menulogo.gif'></div>";
		echo "<div id='languages' class='languages'>";
		echo "<a href='#' onclick='set_language(2);'><img src='img/seso_lang2.png' class='languages'></a><br>";
		echo "<a href='#' onclick='set_language(0);'><img src='img/seso_lang0.png' class='languages'></a><br>";
		echo "<a href='#' onclick='set_language(1);'><img src='img/seso_lang1.png' class='languages'></a><br>";
		echo "</div>";
		echo "</div>";
		echo "<div class='maintitle' id='maintitle'>";
		echo "<table class='mainmenu'>";
		echo "<tr><td><div class='pagetitle' id='pagetitle'>";
		echo $labels->getlabel("websitetitle"); 
		echo "</div></td>";
		echo "<td class='log'></div><div id='log' class='log'>";
		echo "<a class='login' href='#' onclick='goto(1,\"submenu_login.php\",\"submenu_login\",0,0);'>".$labels->getlabel('login')."</a>";
		echo "</div></td>";
		echo "<td class='helpbutton'><div id='seso_help' class='helpbutton'></div></tr></table>";
		echo "</div>";
		echo "</div>";  
		//end mainhead
		
		$tileset=$labels->gettileset(0);
		$tilecount=count($tileset);
		$columns=2;
		$cwidth="style='width:".strval(100/$columns)."%;'";
		echo "<div id='mainbasis' class='mainbasis'>";
		echo "<div class='container'>";
		echo "<table class='mainmenu'>";
		$n=0;
		foreach($tileset as $id=>$position)
		{ if ($n==0 || ($n/$columns==intval($n/$columns))) { echo "<tr>";}
		  echo "<td class='mainmenu' ".$cwidth."><div class='mainmenutile' id='mainmenutile".$position."'>";
			echo $labels->gettilecontent($id);
		  echo "</div></td>";
			$n++;
			if (($n/$columns)==intval($n/$columns)) { echo "</tr>";}
		}
		echo "</table></div></div>";
  }
  else
  { echo "<div>Geen toegang / No entree</div>"; 
	}
		
?>