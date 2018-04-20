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
			if (f.substring(0,10)=='tilevideo_') { v=checkurl(v); $('#'+f).val(v);}
			$.post('cms/cms_tile_save.php',{id:id,f:f,v:v});
		}
		function shift_tile(field)
		{ f=field.name;
		  v=field.value;
			$.post('cms/cms_tilevideo_shift.php',{id:id,v:v},
			  function() { $('#mainbasis').load('cms/cms_tilevideo_edit.php?id='+id);}
			);
		}
	</script>";
	
  $pages=new seso_pages($language);
  $labels=new seso_labels("cms_tilevideo_edit",$language);
  $tilevideo=$pages->gettilerecord($id,"*");	
	$parenttile=$pages->gettilerecord($tilevideo[0]['parent'],"language,text");
	$videonumber=$tilevideo[0]['position']+1;
  $action="onchange='save_tile(this);'";
  $shiftaction="onchange='shift_tile(this);'";
  echo "<div class='container'>";
  echo "<div class='mainmenutile'>";
	$tilepath=$pages->gettilepath($id);
	sh_title($labels->getlabel('tiles').": ".$tilepath);
  echo "<div class='datablock'>";	

  //TILEVIDEO
	sh_grouptitle($labels->getlabel('tile')." - ".$parenttile[$language]['text'].": ".$labels->getlabel('tilevideo')." ".$videonumber); 
  $shiftoptions=array(-1=>$labels->getlabel('up'),0=>$labels->getlabel('no'),1=>$labels->getlabel('down'));
	shi_options_id($labels->getlabel('shift'),$videonumber,0,$shiftoptions,$shiftaction);
	foreach($tilevideo as $lang=>$tt)
	{ echo "<br>";
		shi_inputfield($labels->getlabel('URL')." ".$pages->languages[$lang],"tilevideo_".$lang,$tt['text'],120,$action);
		shi_inputtext($labels->getlabel("videotitel"),"tilevideoinfo_".$lang,$tt['info'],5,$action);
	}	
	echo "</div><br></div></div>";
?>