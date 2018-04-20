<?php
  session_start();
	$language=$_SESSION['language'];
	include('../inc/show.inc');
  include('../classes/class_labels.php');
	include('../classes/class_visitors.php');
	include('../classes/class_info.php');
	$parent=$_GET['id'];
  $labels=new seso_labels("app_info",$language);
	$info=new seso_info($language);

	if($labels->access($parent)>0)
	{	$columns=2;
		$br=strval(100/$columns)."%";
		$tileset=$labels->gettileset($parent);
		$tilecount=count($tileset);
		echo "<div id='mainbasis' class='mainbasis'>";
		echo "<div class='container'>";
		if ($tilecount>0)
		{	if ($tilecount==1) { $br="100%";}
	    echo "<div class='mtile0' style='width:".$br.";'>";
		  for ($k=1; $k<=$tilecount; $k+=2)
	    { echo "<div class='mainmenutile' id='mainmenutile".$k."'>";
			  echo $labels->gettilecontent($tileset[$k-1]);
		    echo "</div>";
      }
      echo "</div>";			
      if ($tilecount>1)
			{ echo "<div class='mtile1' style='width:".$br.";'>";
		    for ($k=2; $k<=$tilecount; $k+=2)
	      { echo "<div class='mainmenutile' id='mainmenutile".$k."'>";
			    echo $labels->gettilecontent($tileset[$k-1]);
		      echo "</div>";
        }
			  echo "</div>";
			}	
		}
    echo "</div></div>";				
  }
  else
  { echo "<div>Geen toegang / No entree</div>"; 
	}
	echo "<br></div>";
?>