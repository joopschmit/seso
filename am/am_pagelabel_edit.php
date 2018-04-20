<?php
  session_start();
  $id=$_GET['id'];
	$language=$_SESSION['language'];
	
	include('../inc/config_languages.inc');
  include("../inc/show.inc");
	include('../inc/show_input.inc');
  include('../classes/class_labels.php');
	include('../classes/class_pages.php');
	
  $pages=new seso_pages($language);
  $labels=new seso_labels("am_pagelabel_edit",$language);
	
  $_SESSION['set']['am']['pages']['pageedit']=$id;

	echo "<script><!--
				function save_pagetext(id,field)
				{ v=field.name;
          w=field.value;
					$.post('am/am_page_save.php',{kind:0,id:id,v:v,w:w},
					  function(antw)
						{ if (antw=='-1' || antw=='-2') 
						  { alert('".$labels->getlabel('nameexists')."');}
						}
					); 					
				}
	//--></script>";
  
	echo "<div class='container'>";
  echo "<div class='mainmenutile'>";
  	
  if ($id>0)
	{ $standardsetting=array();
		$action="onchange='save_pagetext(".$id.",this);'";
		$pagelabel=$pages->getpagelabel($id);
		if ($pagelabel[0]['name']=='title') 
		{ $label=$labels->getlabel('pagedata').": ".$pagelabel[$language]['page'];} 
	  else 
		{ $label=$labels->getlabel('pagetext').": ".$pagelabel[$language]['name'];}
	  sh_title($labels->getlabel('page').": ".$pagelabel[0]['page']);
		echo "<div class='datablock'>";	
	  sh_grouptitle($label);
		foreach($pagelabel as $language=>$row)
		{	echo "<br>";
		  shi_textbox(strtoupper($talen[$language][$language]),'text_'.$language,$row['text'],1,$action);
			if ($row['name']=='title')
			{ shi_textbox($labels->getlabel("info"),'info_'.$language,$row['info'],3,$action);
			  shi_textbox($labels->getlabel("help"),'help_'.$language,$row['help'],3,$action);
			}
			echo "<br>";
		}
	}
	else
	{ sh_grouptitle($labels->$labels->getlabel('nopagelabel')); 
  }
  echo "</div><br></div></div>";
?>