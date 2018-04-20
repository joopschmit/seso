<?php
  session_start();
  $id=$_GET['id'];
	$language=$_SESSION['language'];
	
  include("../inc/show.inc");
	include('../inc/show_input.inc');
  include('../classes/class_labels.php');
	include('../classes/class_pages.php');
	
  $pages=new seso_pages($language);
  $labels = new seso_labels("am_pagelabel_add",$language,1);

	$page=$pages->getpage($id,'*');

	echo "<script> 
	function save_page(field)
	{ v=field.name;
	  w=field.value; 
		$.post('am/am_page_save.php',{soort:0,id:".$id.",v:v,w:w},
			function(ansr) { if (ansr>0) { goto(1,'am/am_pagelabel_edit.php','am_pagelabel_edit',ansr,0);
		}});
	}
  </script>"; 
	
	$action="onchange='save_page(this);'"; 

	echo "<div class='container'>";
  echo "<div class='mainmenutile'>";
  echo "<div class='datablock'>";	

	sh_grouptitle($labels->getlabel('newpagelabel'));
	sh_showfield(1,$labels->getlabel('page'),$page['title']['page']);
	shi_inputfield($labels->getlabel('pagelabelname'),'newpagelabel','',254,$action);

	sh_grouptitle($labels->getlabel('actuallabels'));
  echo "<div>";
	$nl="";
	foreach($page as $label)
	{ if ($label['name']!='title') { echo $nl.$label['name']; $nl="<br>";}
	}
  echo "</div><br></div></div>";
?>  
