<?php
  session_start();
  $id=$_GET['id'];
	$language=$_SESSION['language'];
	
  include("../inc/show.inc");
	include('../inc/show_input.inc');
  include('../classes/class_labels.php');
	include('../classes/class_pages.php');
	
  $pages=new seso_pages($language);
  $labels = new seso_labels("am_var_add",$language,1);
	
	echo "<script>
	function save_var(field)
	{ f=field.name;
	  v=field.value;
		$.post('am/am_var_save.php',{id:-1,f:f,v:v},
			function(antw) 
			{ if (antw>0) { goto(1,'am/am_vars.php','am_vars',0,0); }
			  if (antw==-10) { alert('".$labels->getlabel('vargroupexists')."');}
			  if (antw==-20) { alert('".$labels->getlabel('varexists')."');}
		  }
		);
	}
  </script>";
	
	$action="onchange='save_var(this);'";

	echo "<div class='container'>";
  echo "<div class='mainmenutile'>";
	
	sh_grouptitle($labels->getlabel('newvar')); 
	
	shi_inputfield($labels->getlabel('varname'),'newvar','',254,$action); 

  echo "<br></div></div>";
?>  
