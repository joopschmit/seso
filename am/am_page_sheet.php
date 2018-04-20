<?php
  session_start();
  $id=$_GET['id'];
	$language=$_SESSION['language'];
	include('../inc/config_languages.inc');
  include("../inc/show.inc");
  include('../classes/class_labels.php');
	include('../classes/class_pages.php');
	
  $pages=new seso_pages($language);
  $labels=new seso_labels("am_page_sheet",$language);

	echo "<script>
	function slabel(id)
	{ goto(1,'am/am_pagelabel_edit.php','am_pagelabel_edit',id,0);
	}
	</script>";

	if ($id==0) 
	{ $id=$_SESSION['set']['am']['pages']['pageid'];}
	else
	{ $_SESSION['set']['am']['pages']['pageid']=$id;}

//ophalen pagegegevens
  $page=$pages->getpage($id,"*");
	echo "<div class='container'>";
  echo "<div class='mainmenutile'>";
  if (count($page)>0)
  { $_SESSION['set']['am']['page']=$page['title']['page'];
		$pageid=$page['title']['pageid'];
		sh_title($labels->getlabel('page').": ".$page['title']['page']);
		echo "<div class='datablock'>";	
    echo "<table style='width:auto;'>";
    echo "<tr><th>".$labels->getlabel('systemnames')."</th>";
    echo "<th>".$labels->getlabel('settings')."</th></tr>";
		echo "<tr><td colspan=2 class='grouptitle'>".$labels->getlabel('pagedata')."</td></tr>";
		echo "<tr>";
		echo "<td><a href='#' onclick='slabel(".$page['title']['id'].");'>".$page['title']['name']."</td>";
		echo "<td>".nl2br($page['title']['text'])."</td>";
		echo "</tr>"; 
    if ($page['title']['info']!="") {echo "<tr><th>info</th><td>".nl2br($page['title']['info'])."</td></tr>";}
		if ($page['title']['help']!="") {echo "<tr><th>help</th><td>".nl2br($page['title']['help'])."</td></tr>";}
    echo "<tr><td colspan=2 class='grouptitle'>".$labels->getlabel('labels')."</td></tr>";
		foreach($page as $name=>$row)
		{ if ($name!='title')
		  { if ($row['text']=="") {$row['text']="empty)";}
  			echo "<tr>";
		  	echo "<td><a href='#' onclick='slabel(".$row['id'].");'>".$row['name']."</td>"; 
  			echo "<td>".nl2br($row['text'])."</td>";
  			echo "</tr>"; 
		  } 
		}
		echo "</table>";
		echo "</div>";
	}
	else
	{ sh_grouptitle($labels->getlabel('nopage'));
  }
	echo "</div><br></div>";

?>