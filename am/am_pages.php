<?php
  session_start();
	$language=$_SESSION['language'];
	include('../inc/show.inc');
  include('../classes/class_labels.php');
	include('../classes/class_pages.php');
	
	echo "<script>
	function gotopage(id)
	{ goto(1,'am/am_page_sheet.php','am_page_sheet',id,0);
	}
	</script>";
	
  $pages=new seso_pages($language);
  $labels=new seso_labels("am_pages",$language);
	
  $plist=$pages->getpages();
	
	echo "<div class='container'>";
  echo "<div class='mainmenutile'>";
	sh_title($labels->getlabel('pages')); 
  echo "<div class='datablock'>";	
  echo "<table style='width:auto;'>";
  echo "<tr><th>".$labels->getlabel('page')."</th>";
  echo "<th>".$labels->getlabel('pagetitle')."</th></tr>";
  $sch="";
  foreach($plist as $id=>$row)
  { if ($sch!=$row['page']) 
	  { echo "<tr>";
      echo "<td><a href='#' onclick='gotopage(".$id.")'>".$row['page']."</a></td>";
      echo "<td>".$row['text']."</td>";
      echo "</tr>";
      $sch=$row['page'];
    }
 	}
  echo "</table>";
  echo "</div><br></div></div>";
?>