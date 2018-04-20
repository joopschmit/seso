<?php
	session_start();
	if(isset($_GET['fase'])) { $fase=$_GET['fase'];} else { $fase=0;}
	$language=$_SESSION['language'];
	include('inc/show.inc');
	include('classes/class_labels.php');
	include('classes/class_users.php');

	$labels = new seso_labels("submenu_login",$language);
	$users=new seso_users($language);
	$access=0;
	if (isset($_SESSION['user']['uid']))
	{ $access=$users->checkaccess($_SESSION['user']['uid'],session_id());}
	if ($access==0)
	{ //aanmelden
		echo "<style>
		div.info     {padding:18px;color:#003F7F;vertical-align:top;text-align:left;max-width:500px;font-size:12pt;}
		div.ulabel   {padding: 5px;color:#003F7F;vertical-align:top;text-align:left;width:180px;}
		div.udata    {padding: 5px;color:#003F7F;vertical-align:top;text-align:left;width:200px;}
		div.ubutton  {padding:5px;margin-left:5px;color:#003F7F;text-align:center;width:120px;height:30px;background-color:#003F7F;}
		a.button     {color:white;}
		</style>";	
		$user="";
		echo "<div class='container' style='padding:5px;'>";
		echo "<div class='mainmenutile'>";
		echo "<div class='datablock'>";	
$useremail='joop@moval.nl';
$password='js190449';
		if ($fase==0)
		{ 
			echo "<div class='ulabel'>".$labels->getlabel('name')."</div>";
			echo "<div class='udata'><input class='signin'  autocomplete='on' type='text' name='name' id='nm' size='50' onkeyup='signin(".$fase.",event.keyCode);' maxlength='50' value='".$useremail."' ></div>";
		}
		if ($fase==1)
		{	echo "<div class='ulabel'>".$labels->getlabel('code')."</div>";
			echo "<div class='udata'><input class='signin' autocomplete='off' type='password' name='pw' id='pw' size='50' onkeyup='signin(".$fase.",event.keyCode);' maxlength='50' value='".$password."' ></div>";
		}
		echo "<br><br>";
		echo "<div class='ubutton'><a href='#' onclick='signin(".$fase.",13,\"".$labels->getlabel('noentree')."\");' class='button'>".$labels->getlabel('signin')."</a></div>";
		echo "</div></div><br></div>";
	}	
	else
	{	echo "<script>goto(1,'submenu.php','submenu',0,0)</script>";
	}
?>