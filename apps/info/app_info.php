<?php
  session_start();
	$language=$_SESSION['language'];
	include('../../inc/show.inc');
  include('../../classes/class_labels.php');
	include('../../classes/class_info.php');
	$parent=$_GET['id'];
	
  $labels=new seso_labels("app_info",$language);
  $pagetiles=$labels->getpagedata($parent);
	$tilecount=count($pagetiles['tiles']);
  $page=$pagetiles['page'];
	$info=new seso_info($page,$language);
	
	echo "<div class='container'>";
	if ($tilecount>0)
	{ $colums=2;
		if ($colums==1) { $cl='mtile0';} else { $cl='mtile1';}
		echo "<div class='".$cl."'>";
		for ($t1=0; $t1<$tilecount; $t1+=$colums)
		{ echo "<div class='mainmenutile' id='mainmenutile".$t1."'>";
			echo $labels->gettilecontent($page,$t1);
			echo "</div>";
		}
		echo "</div>";
		if ($colums==2)
		{	echo "<div class='mtile1'>";
			for ($t1=1; $t1<$tilecount; $t1+=2)
			{ echo "<div class='mainmenutile' id='mainmenutile".$t1."'>";
				echo $labels->gettilecontent($page,$t1);
				echo "</div>";
			}
		}
	}
	else
	{ echo "<div>".$labels->getlabel('emptypage')."</div>";
  }
  echo "<br></div>";
?>