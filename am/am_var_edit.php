 <?php
  session_start();
	$id=$_GET['id'];
	$language=$_SESSION['language'];
  
	include('../inc/show.inc');
	include('../inc/show_input.inc');
  include('../classes/class_labels.php');
	include('../classes/class_pages.php');
  echo "<script>
	  id=".$id.";
		function save_var(field,language)
		{ f=field.name;
		  v=field.value;
			$.post('am/am_var_save.php',{id:id,language:language,f:f,v:v});
		}
	</script>";
	
  $pages=new seso_pages($language);
  $labels=new seso_labels("am_var_edit",$language);
	
  $var=$pages->getvar($id);
  $action="onchange='save_var(this);'";
  echo "<div class='container'>";
  echo "<div class='mainmenutile'>";
  sh_title($labels->getlabel('variable')." - ".$var[$language]['name']); 
  echo "<div class='datablock'>";	

 //VAR VALUE
	foreach($var as $lang=>$value)
	{ echo "<br>";
		shi_inputfield($labels->getlabel('value')." ".$pages->languages[$lang],"text_".$lang,$value['text'],254,$action);
		shi_inputtext($labels->getlabel('info')." ".$pages->languages[$lang],"info_".$lang,$value['info'],2,$action);
    echo "<br>";
	}	

	echo "</div><br></div></div>";
	
?>