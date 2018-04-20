<?php
 session_start();
	$language=$_SESSION['language'];
	include('../inc/show.inc');
  include('../classes/class_labels.php');
	include('../classes/class_pages.php');
	$id=0;
	
		function createimage($code)
		{ $n=strlen($code);
			$xm=24;$xc=$xm/2;
			$ym=24;$yc=$ym/2;
			$im = imagecreate($n*$xm,$ym);
			$bc = imagecolorallocate($im,218,232,242);
			$lc = imagecolorallocate($im, 0, 0, 0);
			$style = array($lc,$bc);
      imagesetstyle($im, $style);
			for($t1=0; $t1<$n; $t1++)
			{ $c=$code[$t1];
			  //0=empty
			  imagefill($im, 0, 0, $bc);
				$xpos=$t1*$xm;
				if ($c==1) //corner
				{ imageline($im,$xpos+$xc,0,$xpos+$xc,$yc,IMG_COLOR_STYLED);
					imageline($im,$xpos+$xc,$yc,$xpos+$xm,$yc,IMG_COLOR_STYLED);
				}
				if ($c==2) //split
				{ imageline($im,$xpos+$xc,0,$xpos+$xc,$ym,IMG_COLOR_STYLED);
					imageline($im,$xpos+$xc,$yc,$xpos+$xm,$yc,IMG_COLOR_STYLED);
				}
				if ($c==3) //line
				{ imageline($im,$xpos+$xc,0,$xpos+$xc,$ym,IMG_COLOR_STYLED);
				}
			}
			$fname="img/schema".$code.".png";
			imagepng($im,$fname);
			return "cms/".$fname."?t=".time();
		}
		function nextofsamelevel($level,$startpos,$series)
		{ $aanwezig=false;
      $group=true;		
			foreach ($series as $pos=>$row) 
			{ //if ($row['level']<$level) { $group=false;}
			  if ($group && $pos>$startpos && $row['level']==$level) { $aanwezig=true;}
			}
			return $aanwezig;
		}		

	echo "<style>
	ul {list-style:none;margin-left:45px;padding-top:20px;}
	li {text-align:left;margin-top:-12px;list-style-position:outside;}
	a  {position:relative;top:-5px;}	
  hr {	text-align:left; margin:0;position:relative; top:-6px; width:150px;height:10px;background-color:transparent;}	
	</style>";
	
	echo "<script>
	var idmem=-1;
	function allowDrop(ev) 
	{ ev.preventDefault();}
  function drag(ev) 
	{ ev.dataTransfer.setData('text', ev.target.id);}
  function drop(ev) 
	{ ev.preventDefault();
    var data = ev.dataTransfer.getData('text');
    if(data!=ev.target.id)
		{ $.post('cms/cms_tile_shift.php',{id:data,to:ev.target.id},
		   function(ansr) { $('#mainbasis').load('cms/cms_homepage.php');}
		  );
		}
    $('a').css('background-color','transparent');		
  }
	function mark(field,v)
	{ id=field.id; 
	  actie=v;
		if (actie==1) { $('#'+id).css('background-color','white'); idmem=id;}
	  if (actie==0) { $('#'+idmem).css('background-color','transparent');}
	}	

	</script>";
  
	$pages=new seso_pages($language);
  $labels=new seso_labels("cms_homepage",$language);
	
	$tiletree=$pages->gettiletree(-1);
	$nm=$labels->getlabel('tile')." ";
	  
	echo "<div class='container' style='padding:5px;'>";
  echo "<div class='mainmenutile'>";
	$title=$labels->getlabel('title');
	echo "<div class='grouptitle' id=0 ondragover='allowDrop(event); mark(this,1);' ondragleave='mark(this,0);' ondrop='drop(event)'>".ucfirst($title)."</div>";
	$lastlevel=0;
	echo "<ul>"; 
	$code=array();
	$formercode=array();
	foreach($tiletree as $nr=>$row)
  { $level=$row['level'];
	  for ($t1=0; $t1<$level; $t1++)
		{ if ($formercode[$t1]=="1") { $code[$t1]="0";}
	    if ($formercode[$t1]=="2") { $code[$t1]="3";}
		}	
		while ($level<$lastlevel) { unset($code[$lastlevel]); $lastlevel--;}
	  
		if (nextofsamelevel($level,$nr,$tiletree))
		{ $code[$level]="2";} 
	  else 
		{ $code[$level]="1"; } 
		
		$cr='leeg';
		if (count($code)>0)
		{ $cr=implode("",$code);
	    $m=strlen($cr)*24-36;
//			if ($level>0) { $cr=substr($cr,0,$level);}
      echo "<li style='margin-left:".$m."px; list-style-image: url(".createimage($cr).");';>";
	  } 
		else 
		{ echo "<li>";}
		echo "<a href='#' id='".$row['id']."' ondragover='allowDrop(event);mark(this,1);' ondragleave='mark(this,0);' draggable='true' ondragstart='drag(event)' ondrop='drop(event)' onclick='gototile(".$row['id'].")'>".$row['id']." ".$row['text']."</a>"; 
		
		echo "<hr id='".$row['id']."_prev' ondragover='allowDrop(event); mark(this,1);' ondragleave='mark(this,0);' ondrop='drop(event);'></hr>";
//		echo "<hr id='".$row['id']."_prev' ondragover='allowDrop(event); mark(this); $('#".$row['id']."_prev').css('background-color','yellow');' ondrop='drop(event)'></hr>";
    //echo "<hr>";
		$formercode=$code;
		$lastlevel=$row['level'];
	}
  echo "</ul>"; 
 	echo "<hr id='".$row['id']."_next' ondragover='allowDrop(event)' ondrop='drop(event)'>";
  echo "<br></div>"; 
	echo "</div>";
?>