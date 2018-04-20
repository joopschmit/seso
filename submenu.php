<?php
	session_start();	
	$language=$_SESSION['language'];
	include('inc/show.inc');
	include('classes/class_labels.php');
	include('classes/class_users.php');

	echo "<script>
  function visitors(gr)
	{ goto(1,'vs/vs_visitors.php','vs_visitors',gr,0);}
  function newsitems(gr)
	{ goto(1,'news/news_items.php','news_items',gr,0);}
  function usergroup(gr)
	{ goto(1,'us/us_users.php','us_users',gr,0);}
  function useredit(gr,uid)
	{ goto(1,'us/us_user_edit.php','us_user_edit',uid+'&usergroup='+gr,0);}
	</script>";
  
	$users=new seso_users($language);
	$labels = new seso_labels("submenu",$language);

	$user=$_SESSION['user'];
	if ($user['usergroup']==$user['authorized'])
	{ echo "<div class='container'>";
	  echo "<div class='mainmenutile'>";
    sh_title($labels->getlabel('administration')." - ".$users->members[$user['usergroup']]); 
		echo "<div class='datablock'>";
    if ($user['usergroup']>2)
		{ //admin menu
			
			sh_grouptitle($labels->getlabel('newsbulletin'));
			echo "<ul class='menu'>";
			echo "<li><a href='#' onclick='newsitems(".$user['usergroup'].");'>".$labels->getlabel('newsitems')."</a></li>";
			echo "</ul>";
			echo "<br>";  

			if ($user['usergroup']>3)
			{ 
		    sh_grouptitle($labels->getlabel('visitors'));
				echo "<ul class='menu'>";
				echo "<li><a href='#' onclick='visitors(0);'>".$labels->getlabel('publicvisitors')."</a></li>";
				echo "<li><a href='#' onclick='visitors(1);'>".$labels->getlabel('knownvisitors')."</a></li>";
				echo "</ul>";
				echo "<br>";  
				
				sh_grouptitle($labels->getlabel('users'));
				echo "<ul class='menu'>";
				for ($t1=0; $t1<=$user['usergroup']; $t1++)
				{ echo "<li><a href='#' onclick='usergroup(".$t1.");'>".$users->groups[$t1]."</a></li>";}
				echo "</ul>";
				echo "<br>";  
			}
			if ($user['usergroup']>2)
			{ sh_grouptitle("CMS - ".$labels->getlabel('content'));
				echo "<ul class='menu'>";
				echo "<li><a href='#' onclick='goto(1,\"cms/cms_homepage.php\",\"cms_homepage\",0,0);'>".$labels->getlabel('homepage')."</a></li>";
				echo "</ul>";
				echo "<br>"; 
			}		
			if ($user['usergroup']>4)
			{ sh_grouptitle($labels->getlabel('variables'));
				echo "<ul class='menu'>";
				echo "<li><a href='#' onclick='goto(1,\"am/am_pages.php\",\"am_pages\",0,0);'>".$labels->getlabel('pages')."</a></li>";
				echo "<li><a href='#' onclick='goto(1,\"am/am_vars.php\",\"am_vars\",0,0);'>".$labels->getlabel('settings')."</a></li>";
				echo "</ul>";
				echo "<br>";
			}			
    }
		if ($user['usergroup']<3)
		{ //user menu
			sh_grouptitle($labels->getlabel('yourprofile'));
			echo "<ul class='menu'>";
			echo "<li><a href='#' onclick='useredit(".$user['usergroup'].",\"".$user['uid']."\");'>".$user['fullname']."</a></li>";
			echo "</ul>";
			echo "<br>";  
		}
    echo "</div><br></div></div>"; 
	}
	else
	{ echo "<script>goto(1,'us/checkemail.php','checkemail',".$user['uid'].",0);</script>";}	

?>