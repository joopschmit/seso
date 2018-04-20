 <?php
	session_start();
  ini_set('error_reporting', E_ALL);
	include("inc/datefunctions.inc");
  include('classes/class_visitors.php');
	include('classes/class_news.php');
	$language=$_SESSION['language'];
	$vs=new seso_visitors();
	$news=new seso_news($language);
	$check=$vs->getauthorized(session_id());
	if ($check>0)
  {	if (isset($_GET['lang'])) 
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
    
    function show_newstile($id)
		{	global $labels,$news;
		  $actionimg="<img src='img/play.png'>&nbsp;";
		  $link="<a href=# onclick='gotonews(0);'>"; 
			$linkend="</a>";
			$newsitems=$labels->getnewsitems();
			$tile=$labels->gettile($id);
			echo "<div class='tiletitle' style='display:block;'>".$actionimg.$link.$tile['tiledata']['text'].$linkend."</div>";
			echo "<div class='newsmenu'>";
			echo "<table>";
			foreach($newsitems as $nid=>$item)
			{ $link="<a href=# onclick='gotonews(".$nid.");'>"; 
			  $img="";
			  if ($item['nimage']!="") { $img="<img class='newsicon' src='news/img/".$item['nimage']."' />";}
			  echo "<tr>";
				echo "<td class='menu_newsicon'>".$img."</td>";
				echo "<td>";
				echo "<div class='menu_news'>".$link.$actionimg.$item['nitem'].$linkend;
				echo "<div class='menu_newstext'>".$item['ntext']."</div>";
				echo "<div class='menu_newsgradient'> </div>";
				echo "</div>";
				echo "</td>";
				echo "</tr>"; 
			}	
      echo"</table>";
			echo "</div>";
		}
		
		echo "<body class='main' id='main' onload='setTimeout(function() { top.scrollTo(0, 1) }, 100);'>";
		echo "<div id='signal' class='signal'></div>";
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
		echo "<td class='log'><div id='log' class='log'>";
		echo "<a href='#' onclick='goto(1,\"submenu_login.php\",\"submenu_login\",0,0);'>".$labels->getlabel('admin')."</a>";
		echo "</div></td>";
		echo "<td class='helpbutton'><div id='seso_help' class='helpbutton'></div></tr></table>";
		echo "</div>";
		echo "</div>";  
		//end mainhead

		$columns=2;
		$br=strval(100/$columns)."%";

		$tileset=$labels->gettileset(0);
		$tilecount=count($tileset);
    $newstile_id=$labels->getvariabele('newstile_id',0);
		echo "<div id='mainbasis' class='mainbasis'>";
		echo "<div class='container'>";
		if ($tilecount>0)
		{	if ($tilecount==1) { $br="100%";}
	    echo "<div class='mtile0' style='width:".$br.";'>";
		  for ($k=1; $k<=$tilecount; $k+=2)
	    { $tile_id=$tileset[$k-1];
		    echo "<div class='mainmenutile' id='mainmenutile".$k."'>";
			  if ($tile_id==$newstile_id)
				{ show_newstile($tile_id);}
        else
				{	echo $labels->gettilecontent($tileset[$k-1]);}
		    echo "</div>";
      }
      echo "</div>";			
      if ($tilecount>1)
			{ echo "<div class='mtile1' style='width:".$br.";'>";
		    for ($k=2; $k<=$tilecount; $k+=2)
	      { $tile_id=$tileset[$k-1];
		      echo "<div class='mainmenutile' id='mainmenutile".$k."'>";
					if ($tile_id==$newstile_id)
					{ show_newstile($tile_id);}
					else
			    { echo $labels->gettilecontent($tileset[$k-1]);}
		      echo "</div>";
        }
			  echo "</div>";
			}	
		}
    echo "</div></div>";		
  }
  else
  { echo "<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01//EN' 'http://www.w3.org/TR/html4/strict.dtd'>\n";
		echo "<html>\n";
		echo "<head>\n";
		echo "<title>Sentro Solari</title>\n";
    echo "<meta http-equiv='content-type' content='text/html; charset=iso-8859-1'>\n";
		echo "\n<meta name='author' content='Business Relation Manager'>";
		echo "\n<meta name='copyright' content='(c) 2018 - Moval'>";
    echo "<body>";
		echo "<div>Geen toegang / No entree</div>"; 
		echo "</body></html>";
	}
		
?>