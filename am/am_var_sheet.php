<?php
  session_start();
  $id=$_GET['id'];
	$language=$_SESSION['language'];
	include('../inc/config_languages.inc');
  include("../inc/show.inc");
  include('../classes/class_labels.php');
	include('../classes/class_pages.php');
	
  $pages=new seso_pages($language);
  $labels=new seso_labels("am_vargroup_sheet",$language);

	echo "<script>
	function slabel(id)
	{ goto(1,'am/am_var_edit.php','am_var_edit',id,0);
	}
	</script>";

	if ($id==0) 
	{ $id=$_SESSION['set']['am']['vargroup']['pageid'];}
	else
	{ $_SESSION['set']['am']['vargroup']['pageid']=$id;}
  
	//ophalen pagegegevens
  $vargroup=$pages->getvargroep($id,"*");
	echo "<div class='container'>";
  echo "<div class='mainmenutile'>";
  if (count($page)>0)
  { $_SESSION['set']['am']['vargroup']=$vargroup['title']['vargroup'];
		$varid=$vargroup['title']['pageid'];
		sh_grouptitle($labels->getlabel('vargroep').": ".$vargroup['title']['page']);
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
		foreach($vargroup as $name=>$row)
		{ if ($name!='title')
		  { if ($row['text']=="") {$row['text']="empty)";}
  			echo "<tr>";
		  	echo "<td><a href='#' onclick='slabel(".$row['id'].");'>".$row['name']."</td>"; 
  			echo "<td>".nl2br($row['text'])."</td>";
  			echo "</tr>"; 
		  } 
		}
		echo "</table>";
	}
	else
	{ sh_grouptitle($labels->getlabel('novargroup'));
  }
	echo "<br></div></div>";

?>