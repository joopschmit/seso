<?php
  session_start();
	$id=$_GET['id'];
	$language=$_SESSION['language'];

	include('../inc/show.inc');
  include('../classes/class_labels.php');
	include('../classes/class_pages.php');

  $pages=new seso_pages($language);
  $labels=new seso_labels("cms_tile_sheet",$language);
	
	echo "<script>
	function add_sub(kind)
	{ xpos=$('.container').scrollTop();
	  if (kind==3) { url='cms/cms_tile_add.php';}
		if (kind==4) { url='cms/cms_tiletext_add.php';}
	  if (kind==5) { url='cms/cms_tilevideo_add.php';}
	  $.post(url,{id:".$id."},
	    function() 
      { $('#mainbasis').load('cms/cms_tile_sheet.php?id=".$id."',function() { $('.container').scrollTop(xpos);});
			}
		);	
	}
  </script>";	

  $tile=$pages->gettile($id);
	$subtiles=$pages->getsubtiles($id);
  $pos=$tile[3][0]['position'];
	$tilenumber=$pos+1;	  
  echo "<div class='container' style='padding:5px;'>";
  echo "<div class='mainmenutile'>";
	$tilepath=$pages->gettilepath($id);
	sh_tiletitle($labels->getlabel('tiles').": ".$tilepath);
  echo "<div class='datablock'>";	
//SUB PAGES
	if (count($subtiles)>0)
	{ $textlabel=$labels->getlabel('text');
		sh_grouptitle($labels->getlabel('subpages'));
		echo "<table>";
		foreach($subtiles as $subid=>$row)
		{ $textnumber=$row['position']+1;
      echo "<tr><th class='label'>".$labels->getlabel('subpage')." ".$textnumber."</th>";
			echo "<td><a href='#' onclick='gototile(".$subid.");'>".$row['text']."</a></td></tr>";
		}
		echo "</table><br>"; 
  }
	sh_grouptitle_action($labels->getlabel('addsubpage'),"add_sub(3);"); 
  echo "</div><br></div>";
	
  echo "<div class='mainmenutile'>";
  echo "<div class='datablock'>";	
	sh_tiletitle($labels->getlabel('tilecontent'));

	sh_grouptitle($labels->getlabel('subtile')." ".$tilenumber.": ".$tile[3][$language]['text']); 
  echo "<div class='datablock'>";
	
//TILE POSITION
	sh_grouptitle_action($labels->getlabel('tileposition'),"goto(1,'cms/cms_tile_edit.php','cms_tile_edit',".$id.",0);"); 
	
//TITLE
	sh_grouptitle_action($labels->getlabel('tiletitle'),"goto(1,'cms/cms_tiletitle_edit.php','cms_tiletitle_edit',".$id.",0);"); 
	echo "<table>";
	foreach($tile[3] as $lang=>$row)
	{ echo "<tr><th>".$pages->languages[$lang]."</th><td>".$row['text']."</td></tr>";}	
  echo "</table>";
	
//TEXTS
	if (count($tile[4])>0)
	{ $textlabel=$labels->getlabel('text');
		echo "<table>";
		foreach($tile[4] as $textid=>$row)
		{ $textnumber=$row[0]['position']+1;
	    echo "<tr><td>";
			sh_grouptitle_action($textlabel." ".$textnumber,"goto(1,'cms/cms_tiletext_edit.php','cms_tiletext_edit','".$textid."&parent=".$id."',0);"); 
			echo "</td>";
			foreach($row as $lang=>$var)
			{ echo "<tr><th>".$pages->languages[$lang]."</th><td><b>".$var['text']."</b><br>".nl2br($var['info'])."</td></tr>";}
		}
		echo "</table><br>"; 
  }
	sh_grouptitle_action($labels->getlabel('addtext'),"add_sub(4)"); 

//IMG
	$img=$pages->gettileimg($id);
	sh_grouptitle_action($labels->getlabel('tileimg'),"window.open('cms/cms_tileimg_edit.php?id=".$id."','SeSo-Image','height=500,width=400,menubar=no,titlebar=no,toolbar=no')"); 
	echo "<table>";
	echo "<tr><th>".$labels->getlabel('tileimg')."</th>";
	if ($img!="") 
	{ $imgsize=getimagesize("../".$img);
	  echo "<td><img src='".$img."' class='tileimg' style='float:left;'></td></tr>"; 
    echo "<tr><th>".$labels->getlabel('imgsize')."</th>";
	  echo "<td>".$imgsize[0]." x ".$imgsize[1]."</td></tr>";
	}
	else
	{ echo "<td><div style='border:1px solid silver; border-style:dottet; width:100px; height:100px;'>&nbsp;</d></td></tr>";}
  echo "</table>";
//VIDEOS
	if (count($tile[5])>0)
	{	$videolabel=$labels->getlabel('video');
    echo "<table>";
	  foreach($tile[5] as $videoid=>$row)
		{ $videonumber=$row[0]['position']+1;
	    echo "<tr><td>";
			sh_grouptitle_action($videolabel." ".$videonumber,"goto(1,'cms/cms_tilevideo_edit.php','cms_tilevideo_edit','".$videoid."&parent=".$id."',0);"); 
			echo "</td>";
			foreach($row as $lang=>$var)
			{ echo "<tr><th>".$pages->languages[$lang]."</th>";
			  echo "<td><a href='#' onclick='toonvideo(\"".$var['text']."\",\"tilevideo".$videonumber."\");'>".$var['text']."</a><br>".nl2br($var['info'])."</td></tr>";
			}
		} 
  }	
	echo "</table><br>";
	sh_grouptitle_action($labels->getlabel('addvideo'),"add_sub(5)"); 

  echo "</div><br></div></div>";
?>