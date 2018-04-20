<?php
  session_start();
  $id=$_GET['id'];
	$language=$_SESSION['language'];
	
  include("../inc/show.inc");
	include('../inc/show_input.inc');
  include('../classes/class_labels.php');
	include('../classes/class_pages.php');
	
  $pages=new seso_pages($language);
  $labels = new seso_labels("am_page_add",$language,1);
	
	echo "<script>
	function save_page(field)
	{ v=field.name;
	  w=field.value;
		$.post('am/am_page_save.php',{id:-1,v:v,w:w},
			function(antw) 
			{ if (antw>0) { goto(1,'am/am_page_sheet.php','am_page_sheet',antw,0,0); }
			  if (antw==-10) { alert('".$labels->getlabel('pageexists')."');}
			  if (antw==-20) { alert('".$labels->getlabel('pagelabelexists')."');}
		  }
		);
	}
  </script>";
	
	$action="onchange='save_page(this);'";

	echo "<div class='container'>";
  echo "<div class='mainmenutile'>";
	sh_title($labels->getlabel('newpage')); 
  echo "<div class='datablock'>";	
	
	
	shi_inputfield($labels->getlabel('pagename'),'newpage','',254,$action); 

  echo "</div><br></div></div>";
?>  
