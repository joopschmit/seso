<?php
	session_start();
	if(isset($_GET['fase'])) { $fase=$_GET['fase'];} else { $fase=0;}
	
	$language=$_SESSION['language'];
	include('../inc/datefunctions.inc');
	include('../inc/show.inc');
	include('../classes/class_labels.php');
	include('../classes/class_visitors.php');

	echo "<script>
  function visitors(gr)
	{ goto(1,'vs/vs_visitors.php','vs_visitors',gr,0);}
  function usergroup(gr)
	{ goto(1,'us/us_users.php','us_users',gr,0);}
  function useredit(gr,uid)
	{ goto(1,'us/us_user_edit.php','us_user_edit',uid+'&usergroup='+gr,0);}
	</script>";
	
	$labels = new seso_labels("am_menu",$language);
	$vs=new seso_visitors();
	$authorized=$vs->getauthorized(session_id());
	if ($authorized<3)
	{ //aanmelden
		echo "<style>
		div.ulabel {
			padding:5px;
			color: white;
			vertical-align:top;
			text-align:left;
			width:80px;
		}
		div.udata {
			color: gray;
			padding:5px;
			width:120px;
			vertical-align:top;
			text-align:left;
		}
		input.signin {
			width:120px;
			color:gray;
			padding:5px;
			height:30px;
		}
		div.ubutton {
			width:120px;
			height:30px;
			padding:5px;
			margin-left:5px;
			color:white;
			background-color:#708FA5;
			text-align:center;
		}
		a.button {
			color:white;
		}
		</style>";
		$user="";
		echo "<div style='padding-left:12px; padding-top:20px; margin:0px; display:block;'>";
		if ($fase==0)
		{ $username='joop@moval.nl';
			echo "<div class='ulabel'>".$labels->getlabel('name')."</div>";
			echo "<div class='udata'><input class='signin'  autocomplete='on' type='text' value='".$username."' name='name' id='nm' size='50' onkeyup='signin(".$fase.",event.keyCode);' maxlength='50' value='".$user."'></div>";
		}
		if ($fase==1)
		{	echo "<div class='ulabel'>".$labels->getlabel('code')."</div>";
			echo "<div class='udata'><input class='signin' autocomplete='on' type='password' name='pw' id='pw' size='50' onkeyup='signin(".$fase.",event.keyCode);' maxlength='50'></div>";
		}
		echo "<br><br>";
		echo "<div class='ubutton'><a href='#' onclick='signin(".$fase.",13);' class='button'>".$labels->getlabel('signin')."</a></div>";
	}	
	else
	{	
	  $usergroup=$_SESSION['user']['usergroup'];
    include('../classes/class_users.php');
	  $users=new seso_users($language);
		
		echo "<div class='container'>";

		echo "<div class='datablock'>";
		
		sh_grouptitle($labels->getlabel('administration')); 
  
	
		if ($usergroup>3)
		{ sh_grouptitle($labels->getlabel('users'));
		  echo "<ul class='menu'>";
      for ($t1=0; $t1<=$usergroup; $t1++)
		  { echo "<li><a href='#' onclick='usergroup(".$t1.");'>".$users->groups[$t1]."</a></li>";}
		  echo "<li><a href='#' onclick='visitors(0);'>".$labels->getlabel('public')."</a></li>";
		  echo "<li><a href='#' onclick='visitors(1);'>".$labels->getlabel('relations')."</a></li>";
			echo "</ul>";
		  echo "<br>";  
    }
		else
		{ sh_grouptitle($labels->getlabel('yourprofile'));
		  echo "<ul class='menu'>";
      echo "<li><a href='#' onclick='useredit(".$usergroup.",\"".$_SESSION['user']['uid']."\");'>".$_SESSION['user']['fullname']."</a></li>";
		  echo "</ul>";
		  echo "<br>";  
    }
			
		if ($usergroup>2)
		{ sh_grouptitle("CMS - ".$labels->getlabel('content')); 
			echo "<ul class='menu'>";
			echo "<li><a href='#' onclick='goto(1,\"cm/cm_homepage.php\",\"cm_homepage\",0,0);'>".$labels->getlabel('homepage')."</a></li>";
			echo "</ul>";
			echo "<br>"; 
		}		
		if ($usergroup>3)
		{ sh_grouptitle($labels->getlabel('variables'));
			echo "<ul class='menu'>";
			echo "<li><a href='#' onclick='goto(1,\"am/am_pages.php\",\"am_pages\",0,0);'>".$labels->getlabel('pages')."</a></li>";
			echo "</ul>";
			echo "</div>";  
			echo "<br></div>";
    }			
	}
?>