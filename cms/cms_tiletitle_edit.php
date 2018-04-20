 <?php
  session_start();
	$id=$_GET['id'];
	$language=$_SESSION['language'];

	include('../inc/show.inc');
	include('../inc/show_input.inc');
  include('../classes/class_labels.php');
	include('../classes/class_pages.php');

  echo "<script>
	  id='".$id."';
		function save_tile(field)
		{ f=field.name;
		  v=field.value;
			$.post('cms/cms_tile_save.php',{id:id,f:f,v:v});
		}
	</script>";
	
  $pages=new seso_pages($language);
  $labels=new seso_labels("cms_tiletitle_edit",$language);
  $tile=$pages->gettilerecord($id,'language,position,text');
 	$tilenumber=$tile[0]['position']+1;
  $action="onchange='save_tile(this);'"; 
   
  echo "<div class='container'>";
  echo "<div class='mainmenutile'>";
	$tilepath=$pages->gettilepath($id);
	sh_title($labels->getlabel('tiles').": ".$tilepath);
  echo "<div class='datablock'>";	

 
  //TILETITLE
	sh_grouptitle($labels->getlabel('tiletitle')." ".$tilenumber); 
  foreach($tile as $lang=>$tt)
	{ shi_inputfield($pages->languages[$lang],"tiletitle_".$lang,$tt['text'],120,$action);
  }
	echo "</table>";

	echo "</div><br></div></div>";
	
?>