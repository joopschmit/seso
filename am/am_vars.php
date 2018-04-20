<?php
  session_start();
	$language=$_SESSION['language'];
	include('../inc/show.inc');
  include('../classes/class_labels.php');
	include('../classes/class_pages.php');
	
	echo "<script>
	function gotopage(id)
	{ goto(1,'am/am_var_edit.php','am_var_edit',id,0);
	}
	</script>";
	
  $pages=new seso_pages($language);
  $labels=new seso_labels("am_vars",$language);

  $varlist=$pages->getvars(); 

	echo "<div class='container'>";
  echo "<div class='mainmenutile'>";
	sh_title($labels->getlabel('vars')); 
  echo "<div class='datablock'>";	
  echo "<table style='width:auto;'>";
  echo "<tr><th>".$labels->getlabel('var')."</th>";
  echo "<th>".$labels->getlabel('value')."</th>";
  echo "<th>".$labels->getlabel('description')."</th></tr>";
  $sch="";
  foreach($varlist as $id=>$var)
  { echo "<tr>";
    echo "<td><a href='#' onclick='gotopage(".$id.")'>".$var['name']."</a></td>";
		echo "<td>".$var['text']."</td>";
		echo "<td>".$var['info']."</td>";
		echo "</tr>";
 	}
  echo "</table>";
  echo "</div><br></div></div>";
?>