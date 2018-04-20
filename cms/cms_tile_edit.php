 <?php
  session_start();
	$id=$_GET['id'];
	$language=$_SESSION['language'];
  
	include('../inc/show.inc');
	include('../inc/show_input.inc');
  include('../classes/class_labels.php');
	include('../classes/class_pages.php');
	include('../classes/class_users.php');
  $pages=new seso_pages($language);
  $labels=new seso_labels("cms_tile_edit",$language);
	$users=new seso_users($language);
	$actualtile=$pages->gettilerecord($id,'*');
  $tilepath=$pages->gettilepath($id);	
  echo "<script>
		id='".$id."';
		function save_tile(field)
		{ f=field.name;
		  v=field.value;
			$.post('cms/cms_tile_save.php',{id:id,f:f,v:v},
			  function() { $('#mainbasis').load('cms/cms_tile_edit.php?id='+id);}
			);	
		}
	</script>";

	$tilenumber=$actualtile[0]['position']+1;
  $action="onchange='save_tile(this);'";
  echo "<div class='container' style='padding:5px;'>";
  echo "<div class='mainmenutile'>";
	sh_title($labels->getlabel('tiles').": ".$tilepath);
  echo "<div class='datablock'>";	

  //TILE
  $tiles=$pages->gettiletree(-1);
  $options=array();
	$pn=0;
	$value=0;
	foreach($tiles as $nr=>$tile) 
	{ $ts=str_repeat("- ",$tile['level']);
	  if ($tile['id']!=$actualtile[0]['id']) { $options[$tile['id']]=$ts.$tile['text'];}
	  if ($tile['parent']==$actualtile[0]['parent']) { $pn++;}
		$value=$tile['value'];
	}	
	sh_grouptitle($labels->getlabel('tile')." ".$tilenumber."/".$pn.": ".$actualtile[0]['id']." ".$actualtile[$language]['text']); 
  shi_options_id($labels->getlabel('parent'),'tileparent',$actualtile[0]['parent'],$options,$action);	
  if ($pn>1)
	{ shi_selectpositions($labels->getlabel('groupposition'),'tileposition',$actualtile[0]['position']+1,1,$pn,$action);
    echo "<br>";
	} 

	if ($_SESSION['user']['uid']>0)
	{	$user=$users->getuser($_SESSION['user']['uid'],"*"); 
		if ($user['usergroup']>2)
		{	$options=$users->members;
		  $opt=array();
		  for ($t1=0; $t1<=$user['usergroup']; $t1++) { $opt[$t1]=$options[$t1];}
		  shi_options_id($labels->getlabel('usergroup'),'value',$value,$opt,$action);
		}
  }

  echo "</div><br></div></div>";
	
?>