<?php
if (file_exists("../../inc/dbhulp.inc"))
{ require_once("../../inc/dbhulp.inc");}
else
{ if (file_exists("../inc/dbhulp.inc"))
  { require_once("../inc/dbhulp.inc");}
  else
  { require_once("inc/dbhulp.inc");}
}
class seso_labels {
  public  $labelseries=array();
	private $varrows=array();
	private $varseries=array();
	public $languages=array();
	private $tileseries=array();
	private $columns=array();
	private $q=0;
	private $language=0;
	private $b_fields="";
	private $b_field=array();
	private $b_size=array();
	private $page="";
  private $pagedata=array();
	private $imgext=array('.gif','.jpg','.png'); 
	
  public function __construct($page,$language)
  { $this->q=new db_joop();
		$this->language=$language;
		$this->languages=$this->q->get_languages($language);
		if ($page!="") 
		{ $this->_initlabels($page);
	    $this->page=$page;
		}
	}
	
	/////////////////////// PRIVATE SECTION //////////////////////////
  private function _access($id)
	{ $sql="select value from seso_labels where id=".$id." and language=0";
	  $this->q->query($sql);
		$row=$this->q->next_record();
		return $row['value'];
  }
  private function _initlabels($page)
	{ $where="";
		$sql="select * from seso_labels where kind =0 and page='".$page."' and language=".$this->language." order by name";
		$this->q->query($sql);
		$RL=array();
		$name="";
		while ($rec=$this->q->next_record())
		{ if ($name!=$rec['name'])
			{ $RL[$rec['name']]['name']=$rec['name'];
				$RL[$rec['name']]['text']=$rec['text'];
				if ($rec['name']=='title')
				{ $RL['title']['info']=$rec['info'];
					$RL['title']['help']=$rec['help'];
				  if ($rec['help']!="")	{	$RL['title']['helpID']=$rec['id'];}
				}
        else
        { $RL[$rec['name']]['info']="";
          $RL[$rec['name']]['help']="";
          $RL[$rec['name']]['helpID']=0;
        }					
				$name=$rec['name'];
			}
		}
		$this->labelseries=$RL;
	}
	private function _getvariabele($name,$language)
	{ $sql="select text from seso_labels where name='".$name."' and language=".$language;
    $this->q->query($sql);
    $row=$this->q->next_record();
    return $row['text'];
	}	
	private function _getlabel($title,$uc=0)
  {	$TS="[".$title."]";
		if (isset($this->labelseries[$title]))
		{ $TS=$this->labelseries[$title]['text'];
		  if ($TS=="") {$TS="[".$title."]";}
		}
  	if ($uc==0) { return $TS;}
   	if ($uc==1) { return ucfirst($TS);}
   	if ($uc==2) { return strtoupper($TS);}
  }
	private function _gethelpID($title)
  {	$TS="";
		if (isset($this->labelseries['title']['helpID'])) { $TS=$this->labelseries['title']['helpID'];}
		return $TS;
  }	
	private function _helpinfo($id,$language)
	{ $sql="select text,help from seso_labels where id=".$id." and kind=0 and language=".$language;
	  $this->q->query($sql);
    return $this->q->next_record();
  }
  private function _gethelptext($title)
  { $TS=$this->labelseries[$title]['help'];
		if ($TS!="") { return nl2br($TS);} else { return 0;} 
  }
  private function _getinfo()
  { $TS="";
		if (is_array($this->labelseries)) { $TS=$this->labelseries['titel']['info'];}
		return $TS;
  }
  //subpages and tiles
	private function _gettilepath($id)
	{ $tilepath="";
	  $parts=array();
		$parent=-1;
		$n=0;
		while ($parent>0 || $parent==-1)
		{ $sql="select id,page,parent,text from seso_labels 
	          where id=".$id." and language=".$this->language." ";
			$this->q->query($sql);
      $row=$this->q->next_record();
      $parts[$n]=$row['id'].":".$row['text'];
      $parent=$row['parent'];
      $id=$row['parent'];
			$n++;
      if ($row['parent']==0) { $parts[$n]="0:Home"; $n++;}
	  }
    if(count($parts)>0)
    { krsort($parts);}
  	$ts="";
		$kar="";
	  foreach($parts as $nr=>$var) 
	  { $geg=explode(":",$var); $tilepath[$geg[0]]=$geg[1];
		  $ts.=$kar."<a class=path href=# onclick='gotopage(".$geg[0].");'>".$geg[1]."</a>";
			$kar="/";
		}
    return $ts;
	}	
	private function _gettileset($parent)
	{ $tset=array();
	  $sql="select id,position from seso_labels where kind=3 and parent=".$parent." and language=0 order by position,id";
	  $this->q->query($sql);
		$n=0;
		while ($row=$this->q->next_record()) 
		{ $tset[$n]=$row['id']; $n++;} 
		return $tset;
	}	
  private function _initpagedata($id)
  { $this->pagedata=array();
	  $sql="select id,page,name,text,position from seso_labels where parent=".$id." and name='tiletitle' and language=".$this->language." order by position";
	  $this->q->query($sql);
    while($row=$this->q->next_record()) 
		{ $this->pagedata['page']=$row['text'];
	    $this->pagedata['tiles'][$row['position']]=$row;
		}
  }   
	private function _tiledata($id,$uc=0)
  {	$tiledata=array();
		$sql="select text,position,value,parent from seso_labels where kind=3 and id=".$id." and name='tiletitle' and language=".$this->language;
	  $this->q->query($sql);
		if ($this->q->rowcount>0)
		{ $tiledata=$this->q->next_record();
	    $TS=$tiledata['text'];
		  if ($TS!="")
			$TS=str_replace(chr(34),"-",$TS);
			$TS=str_replace(chr(39),"-",$TS);
			if ($uc==1) { $TS=ucfirst($TS);}
			if ($uc==2) { $TS=strtoupper($TS);}
			$tiledata['text']=utf8_decode($TS);
		}
	  return $tiledata;		
  }
  private function _tiletexts($id)
	{ $texts=array();
	  $sql="select position,text,info from seso_labels where kind=4 and parent=".$id."  and language=".$this->language." order by position";
	  $this->q->query($sql);
  	while ($row=$this->q->next_record()) 
		{ $row['text']=utf8_decode($row['text']); 
	    $row['info']=utf8_decode($row['info']);
			$texts[$row['position']]=$row;
		}
		return $texts;
	}
	private function _tileimg($id)
	{ $test=0;
		$t1=0; 
		$aantalext=count($this->imgext);
		$imgpath=$this->_getvariabele('imgpath',$this->language);
		$fname="tileimg_".$id;
		while ($t1<$aantalext and $test==0)
		{ $filename=$imgpath.$fname.$this->imgext[$t1];
			if (file_exists("../".$filename) || file_exists($filename)) {  return $imgpath.=basename($filename); $test=1;}
			$t1++;
		}
		if ($test==0) {return "";}
	}
  private function _tilevideos($id)
	{ $videos=array();
	  $sql="select position,value,text,info from seso_labels where kind=4 and id=".$id." and name='tilevideo' and language=".$this->language;
	  $this->q->query($sql);
    while($row=$this->q->next_record()) { $videos[$row['value']]=$row;}
		return $videos;
	}
	private function tiletextformat($text)
	{ $length=strlen($text);
	  $ftext="";
  	$p0=0;
		while (strpos($text,'[hyperlink',$p0)>0)
    { $p1=strpos($text,'[hyperlink'); 
	    $p2=strpos($text,']',$p0);
      $hlink=substr($text,$p1+1,$p2-$p1-1);
			$hg=explode(";",$hlink);
			$ftext.=substr($text,$p0,$p1-$p0);
			$ftext.="<a href='".$hg[2]."' target='_new'>".$hg[1]."</a>";
			$p0=$p2+1;
		} 
		if ($p0<$length) { $ftext.=substr($text,$p0);}
		return $ftext;
	}	
	public function _gettile($id)
  { $tile=array();
	  $tile['tiledata']=$this->_tiledata($id);
	  $tile['texts']=$this->_tiletexts($id);
	  $tile['img']=$this->_tileimg($id);
    $tile['videos']=$this->_tilevideos($id);
    return $tile;	
  }	
  private function _getnewsitems()
	{ $nlist=array();
	  $sql="select nid,nitem,substring(ntext,1,200) as ntext,nimage,ndate from seso_news where nusergroup>=0 
		      and language=".$this->language." and narchive=0 order by ndate desc";
	  $this->q->query($sql);
		while ($row=$this->q->next_record()) {$nlist[$row['nid']]=$row;}
		return $nlist; 
	}
	private function _tilecontent($id)
	{ $tile=$this->_gettile($id,1);
	  $ts="<div class='tile'>";
    $link=""; $linkend="";$actionimg="";
		if ($tile['tiledata']['value']>0)
		{ $actionimg="<img src='img/play.png'>&nbsp;";
			$link="<a href=# onclick='gotopage(".$id.");'>"; 
			$linkend="</a>";
		}
		$ts.="<div class='tiletitle'>".$actionimg.$link.$tile['tiledata']['text'].$linkend."</div>";
		if ($tile['img']!="") { $ts.=$link."<img src='".$tile['img']."' id='tilebutton".$id."' class='tileimg'>".$linkend;}
		foreach($tile['texts'] as $position=>$row) 
		{ if ($row['text']!="") 
			{ $ts.="<div class='tilesubtitle'>".$row['text']."</div>";}
			if ($row['info']!="")
			{ $info=$this->tiletextformat($row['info']);
				$ts.="<div class='tiletext'>".nl2br($info)."</div>";
			}
		}	
		if (count($tile['videos'])>0)
		{ $img="<img class='videoplay' src='img/play.png'>";
			$ts.="<div class='videos'>";
			foreach($tile['videos'] as $sub=>$row) 
			{	if ($row['text']!="")
				{ $ts.="<div>".$img." <a href='#' onclick='toonvideo(".$id.",\"tilevideo".$nr."\");'>".$row['info']."</a></div>";}
			}
			$ts.="</div>";
		}
		$ts.="</div>"; 
		return $ts; 
	} 
	private function _initcolomns($page)
	{ $this->columns=array();
		$sql="select distinct name,position from seso_labels 
					where page='".$page."' and language=0 and position>0 order by position"; 
		$this->q->query($sql);
		while ($row=$this->q->next_record())
		{ $this->columns[$row['name']]=$row['position'];}
	}
	///////////////////// PUBLIC SECTION /////////////////////////
	public function access($id)
	{ return $this->_access($id);
	}	
  public function getvariabele($name,$language)
	{ return $this->_getvariabele($name,$language);
	}
	public function inittiles()
	{ $this->_inittiles();
	}
	public function getlabel($title,$uc=0) 
  {	return $this->_getlabel($title,$uc);
	}
	public function gethelpID($title)
  {	return $this->_gethelpID($title);
	}
  public function gethelpinfo($id,$language)
  { return $this->_helpinfo($id,$language);
	}
  public function gethelptext($title)
  { return $this->_helptext($title);
	}
  public function getinfo()
  { return $this->_getinfo();
  }
	public function gettilepath($id)
	{ return $this->_gettilepath($id);
  }	
  public function gettileset($parent)
  { return $this->_gettileset($parent);
	}
	public function getpagedata($id)
	{ $this->_initpagedata($id);
	  return $this->pagedata;
	}	
	public function gettiledata($id)
  { return $this->_tiledata($id);
	}
	public function gettiletexts($id)
  { return $this->_tiletexts($id);
	}
	public function gettileimg($id)
	{ return $this->_tileimg($id);
  }
	public function gettilevideos($id)
	{ return $this->_tilevideos($id);
  }
	public function getnewsitems()
	{ return $this->_getnewsitems();
	}	 
	public function gettilecontent($id)
	{ return $this->_tilecontent($id);
	}
	public function gettile($id)
	{ return $this->_gettile($id);
	}
}
?>