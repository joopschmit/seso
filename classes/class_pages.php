<?php
if (file_exists("../inc/dbhulp.inc"))
{ require_once("../inc/dbhulp.inc");}
else
{ require_once("inc/dbhulp.inc");}
/*
kind of codes, pages and labels:
0 screentexts
0 screentexts
1
2
3 tiles
4 tiletexts
5 tilevideos 
(tileimages come directly from teh server map: content/img)
*/ 
class seso_pages {
	private $q=0; 
	private $kind=0;
	private $language=0;
	public $languages=array();
	private $translations=array();
	private $pages=array();
	private $page=array();
	private $pagelabel=array();
	private $fields="pageid,id,kind,page,name,value,position,sub,language,text,info,help";
	private $imgext=array('.gif','.jpg','.png'); 
  private $tiletree=array();
  private $counter=0;
	private $level=0;
  

	public function __construct($language)
  { $this->language=$language;
	  $this->q=new db_joop();
		$this->languages=$this->q->get_languages($language);
//		if ($language==0) { $this->languages=array("Nederlands","Engels","Papiaments");}
//		if ($language==1) { $this->languages=array("Dutch","English","Papiamento");}
//		if ($language==2) { $this->languages=array("Hulandes","Ingles","Papiamento");}
	}
//private functions
	private function _getvariabele($name,$language)
	{ $sql="select text from seso_labels where name='".$name."' and language=".$language;
    $this->q->query($sql);
    $row=$this->q->next_record();
    return $row['text'];
	}	
	private function _pages_init()
	{ $this->pages=array();
	  $sql="select id,page,text from seso_labels 
          where name='title' and kind=".$this->kind." and language=0 order by page";
	  $this->q->query($sql);
		while ($row=$this->q->next_record()) { $this->pages[$row['id']]=$row;}
  }
  private function _checkurl($url)
	{ if (substr($url,0,7)!='http://' && substr($url,0,8)!='https://') {$url="http://".$url;}
	  return $url;
	}
	private function _page_init($id,$fields)
	{ $this->pagelabels=array();
		$sql="select page from seso_labels where kind=".$this->kind." and id=".$id." and language=0";
		$this->q->query($sql);
		if ($this->q->rowcount>0)
		{ $row=$this->q->next_record();
			$pagename=$row['page'];
			if ($fields=="*") { $fields=$this->fields;}
			$sql="select ".$fields." from seso_labels	
			      where page='".$pagename."' and language=".$this->language." and kind=".$this->kind." order by name";
			$this->q->query($sql);
			while ($row=$this->q->next_record()) { $this->page[$row['name']]=$row; }
    }
	}	
	private function _pagelabel_init($id)
	{ $this->pagelabel=array();
	  $sql="select ".$this->fields." from seso_labels where kind=".$this->kind." and id=".$id." order by language";
		$this->q->query($sql);
		while ($row=$this->q->next_record()) { $this->pagelabel[$row['language']]=$row;}
  }
	private function _page_save($kind,$id,$field,$value)
	{ if ($id==-1 || $field=='newpagelabel') //page toevoegen
	  { $newid=$this->q->new_id('seso_labels','id');
			$sql="insert into seso_labels (id,kind,page,name,text,language) ";
			foreach($this->languages as $languagenr=>$language)
			{ if ($id==-1) 
				{ $sql2=$sql." values(".$newid.",".$kind.",'".addslashes($value)."','title','".addslashes($value)."',".$languagenr.")";}
			  else
				{ $this->_pagelabel_init($id);
			    $sql2=$sql." values(".$newid.",".$kind.",'".$this->pagelabel[0]['page']."','".addslashes($value)."','".addslashes($value)."',".$languagenr.")";
			  }
		  	$this->q->exec_sql($sql2);
    	}
      return $newid;		
    }
		else
		{ $vg=explode("_",$field);
			if (count($vg)==2)
			{ $sql="update seso_labels set ".$vg[0]."='".addslashes($value)."' where id=".$id." and language=".$vg[1]; 
		    $this->q->exec_sql($sql);
			}	
		}
	}
	private function _pagelabel_remove($kind,$id)
	{ $test=0;
    $sql="delete from seso_labels where id=".$id;
		$this->q->exec_sql($sql);
	}	
	private function _page_remove($pageid)
	{ if ($pageid>0)
	  { $this->_initpagelabel($pageid);
	    $page=$this->pagelabel[0]['page'];
			if ($page!="")
			{	$sql="delete from seso_labels where kind=0 and page='".addslashes($page)."'";
		    $this->q->exec_sql($sql);
			}	
		}
	}	
  private function _page_check($kind,$id,$field,$value)
	{ $test=0;
	  $sql="select id from seso_labels where kind=".$kind." ".$field."='".$value."'";
	  if ($id>0 && $field=='name') 
		{ $this->_pagelabel_init($id);
		  $sql.=" and page='".$this->pagelabel[0]['page']."'";
		}
		$this->q->query($sql);		
    if ($this->q->rowcount>0)
		{ if ($field=='page') { $test=1;} else { $test=2;} }
    return $test;	
	}
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
	  }
    if(count($parts)>0)
    { krsort($parts);}
  	$ts="";
		$kar="";
	  foreach($parts as $nr=>$var) 
	  { $geg=explode(":",$var); $tilepath[$geg[0]]=$geg[1];
		  $ts.=$kar."<a href=# onclick='gototile(".$geg[0].");'>".$geg[1]."</a>";
			$kar="/";
		}
    return $ts;
	}	
	private function _getpagetitle($page)
	{ $pt=array();
	  $sql="select language,text from seso_labels where page='".$page."' and name='tiletitle' ";
		$this->q->query($sql);
    while($row=$this->q->next_record()) { $pt[$row['language']]=$row;}
		return $pt; 
	}
	private function _fill_tiletree($id)
	{ $p=new db_joop();
	  $sql="select id,page,parent,name,text,position,value from seso_labels where kind=3 and parent=".$id." and language=0 order by position";
	  $p->query($sql);
		$this->level++;
		while($row=$p->next_record())
		{ $this->counter++;
			$row['level']=$this->level;
	    $this->tiletree[$this->counter]=$row;
	 	  if ($this->level<10) { $this->_fill_tiletree($row['id']);}
		}
    $this->level--;		
    unset($p);
	}
	private function _gettiletree($id)
	{ $this->tiletree=array();
    $this->counter=0;
		$sql="select id,page,parent,name,text,position,value from seso_labels where kind=3  and language=0";
		if ($id==-1) { $sql.=" and parent=0";} else { $sql.=" and id=".$id;} 
	  $sql.=" order by position";
		$this->q->query($sql);
		while($row=$this->q->next_record())
		{ $this->level=0;
	    $row['level']=0;
	    $this->counter++;
			$this->tiletree[$this->counter]=$row;
		  $this->_fill_tiletree($row['id']);
		}	
  }		
	private function _gettiles($id)
	{ $tiles=array();
	  $sql="select id,position,name,text,parent from seso_labels where parent='".$id."' and kind=3  order by position";
		$this->q->query($sql);
    while($row=$this->q->next_record()) 
		{ $tiles[$row['position']]=$row;		
	  }
		return $tiles;
	}	
	private function _gettilerecord($id,$fields)
	{ $tr=array();
	  $sql="select ".$fields." from seso_labels where id=".$id; 
		$this->q->query($sql);
    while ($row=$this->q->next_record()) { $tr[$row['language']]=$row;}
		return $tr;
	}	
	private function _gettile($id)
	{ $tile=array(3=>array(),4=>array(),5=>array());
	  $sql="select * from seso_labels where id=".$id." and kind=3 order by position";  
		$this->q->query($sql);
    while($row=$this->q->next_record()) { $tile[3][$row['language']]=$row;}
		$sql="select * from seso_labels where parent=".$id." and kind in (4,5) order by position";  
		$this->q->query($sql);
    while($row=$this->q->next_record()) 
		{ $tile[$row['kind']][$row['id']][$row['language']]=$row;}
		return $tile;
	}	
	private function _tileremove($id)
	{ $children=array();
	  $sql="select id,text from seso_labels where kind=3 and parent=".$id." and language=".$this->language;
		$this->q->query($sql);
	  while ($row=$this->q->next_record()) { $children[$row['id']]=$row['text'];}
		if (count($children)==0)
		{ $sql="delete from seso_labels where id=".$id;
	    $this->q->exec_sql($sql);
		}
		else
		{ return implode(", ",$children);}	
	}
	private function _getsubcount($kind,$id)
	{ $sql="select id from seso_labels where kind=".$kind." and parent=".$id." and language=0";
	  $this->q->query($sql);
		return $this->q->rowcount;
	}	
	private function _getsubtiles($id)
	{ $subtiles=array();
	  $sql="select id,page,position,name,text from seso_labels where kind=3 and parent=".$id."  order by position";
		$this->q->query($sql);
    while($row=$this->q->next_record()) 
		{ $subtiles[$row['id']]=$row;}
		return $subtiles;
	}	
/*
	private function _gettiletitle($id)
	{ $tile=array();
	  $sql="select text,language from seso_labels where id=".$id."  and kind=3 ";
		$this->q->query($sql);
    while($row=$this->q->next_record()) { $tile[$row['language']]=$row;} 
		return $tile;
	}	
*/
	private function _gettileimg($id)
	{ $test=0;
		$t1=0; 
		$aantalext=count($this->imgext);
		$pad=$this->_getvariabele('imgpath',$this->language);
		$fname="tileimg_".$id;
		while ($t1<$aantalext and $test==0)
		{ $filename=$pad.$fname.$this->imgext[$t1];
			if (file_exists("../".$filename) || file_exists($filename)) {  return $pad.=basename($filename); $test=1;}
			$t1++;
		}
		if ($test==0) {return "";} 
	}
/*
	private function _gettiletext($id,$sub)
	{ $tiletext=array();
	  $sql="select sub,text,info,language from seso_labels where id='".$id."' and name='tiletext' and kind=3 and sub=".$sub." order by language";
		$this->q->query($sql);
    while($row=$this->q->next_record()) { $tiletext[$row['language']]=$row;} 
		return $tiletext;
	}	
	private function _gettilevideo($id,$sub)
	{ $tilevideo=array();
	  $sql="select sub,text,info,language from seso_labels where id='".$id."' and name='tilevideo' and kind=4 and sub=".$sub." order by language";
		$this->q->query($sql);
    while($row=$this->q->next_record()) { $tilevideo[$row['language']]=$row;} 
		return $tilevideo;
	}	
*/
	private function _savetilevalue($id,$value)
	{ $sql="update seso_labels set value='".addslashes($value)."' where id='".$id."'";    
 echo $sql; 	$this->q->exec_sql($sql);	
	}
	private function _savetilename($id,$value)
	{ $sql="update seso_labels set page='".addslashes($value)."' where id='".$id."'";    
  	$this->q->exec_sql($sql);	
	}
  private function _savetiletitle($id,$value,$language)
	{ $sql="update seso_labels set text='".addslashes($value)."' where id='".$id."' and kind=3 and language=".$language;    
  	$this->q->exec_sql($sql);	
	}
	private function _savetiletext($field,$id,$value,$language)
	{ $sql="update seso_labels set ".$field."='".addslashes($value)."' where id='".$id."' and kind=4 and language=".$language;    
    $this->q->exec_sql($sql);	
  }
	private function _savetilevideo($field,$id,$value,$language)
	{ if ($field=='text') { $value=$this->_checkurl($value);}
    $sql="update seso_labels set ".$field."='".addslashes($value)."' where id='".$id."' and kind=5 and language=".$language;    
    $this->q->exec_sql($sql);	
  }
	private function reset_positions($parent)
	{ $sql="select id,position from seso_labels where kind=3 and parent='".$parent."'  order by position,id";
	  $this->q->query($sql);
		$n=0; $reeks=array();
		while($row=$this->q->next_record()) { if ($row['position']!=$n) { $reeks[$row['id']]=$n;} $n++;}
		if (count($reeks)>0)
		{ foreach($reeks as $id=>$position) 
      { $sql="update seso_labels set position=".$n." where id=".$id;
        $this->q->exec_sql($sql);
      }
    }
		return $n;
  }
	private function _addsubpage($id)
	{ $nextposition=$this->reset_positions($id);
	  $newid=$this->q->new_id('seso_labels','id');
		foreach($this->languages as $langnr=>$language)
	  { $sql="insert into seso_labels (id,kind,name,position,language,text,parent)
		        values(".$newid.",3,'tiletitle',".$nextposition.",".$langnr.",'new_".$newid."',".$id.")";		
      $this->q->exec_sql($sql);
    }
    return $newid;
	}
  private function _addsub($kind,$id)
	{ $sql="select position from seso_labels where kind=".$kind." and parent=".$id." and language=0";
	  $this->q->query($sql);
		$pos=$this->q->rowcount;
		$newid=$this->q->new_id('seso_labels','id');
	  if ($kind==4) { $name='tiletext';}
	  if ($kind==5) { $name='tilevideo';}
		foreach($this->languages as $langnr=>$language)
		{ $sql="insert into seso_labels(id,kind,name,position,language,parent) 
		        values(".$newid.",".$kind.",'".$name."',".$pos.",".$langnr.",".$id.")";
  	  $this->q->exec_sql($sql);
    }
		return $newid; 
  }		
  private function _removesubtext($id)
	{ $sql="delete from seso_labels where id=".$id;
 	  $this->q->exec_sql($sql);
  }		
  private function _removesubvideo($id)
	{ $sql="delete from seso_labels where id=".$id;
 	  $this->q->exec_sql($sql);
  }		
	private function _shifttilesub($id,$shift)
	{ $reeks=array();
	  $tile=$this->_gettilerecord($id,'language,kind,parent,position');
		$actualpos=$tile[0]['position'];
		$positions=$this->_getsubcount($tile[0]['kind'],$tile[0]['parent']);
		if ($positions>0)
	  { $newpos=$actualpos+$shift;
	    if ($newpos<$positions && $newpos>=0)
		  { $sql="select id,position from seso_labels where kind=".$tile[0]['kind']." and parent=".$tile[0]['parent']." and language=0";
		    $this->q->query($sql);
				while($row=$this->q->next_record()) { $reeks[$row['position']]=$row['id'];}
		    //shift by switching two records
        $posmem=$reeks[$newpos]; 
				$reeks[$newpos]=$reeks[$actualpos]; 
				$reeks[$actualpos]=$posmem;
        foreach($reeks as $position=>$id) 
			  { $sql="update seso_labels set position=".$position." where id=".$id;
          $this->q->exec_sql($sql);
	  		}
			}	
    }
	}	
	private function _tileshift($tile_id,$to_parent)
	{ $target=explode("_",$to_parent);
	  if (count($target)==1) //to child
		{ $sql="select max(position) as pos from seso_labels where language=0 and kind=3 and parent=".$to_parent." group by parent";
		  $this->q->query($sql);
		  $row=$this->q->next_record();
			$newpos=$row['pos']+1;
		  $sql="update seso_labels set parent=".$to_parent.",position=".$newpos." where id=".$tile_id;
		  $this->q->exec_sql($sql);
    }
    else
		{ //get tile parent
	    $sql="select parent from seso_labels where id=".$tile_id;
			$this->q->query($sql);
			$tile=$this->q->next_record();
	    //get target groupmembers
			$sql="select t1.parent,t2.id,t2.position from seso_labels t1 left join seso_labels t2
            on t2.parent=t1.parent where t1.id=".$target[0]." and t1.language=0 and t2.language=0 
						and t2.kind=3 and t2.name='tiletitle' order by position, id";
			$this->q->query($sql);
      $positions=array();
			$pos=0;
			$newparent=0;
      //arrange new positions;
			while ($row=$this->q->next_record()) 
			{ if ($target[1]=='prev')
			  { if ($row['id']==$target[0]) { $positions[$tile_id]=$pos; $pos++; $newparent=$row['parent'];}
				  if ($row['id']!=$tile_id)   { $positions[$row['id']]=$pos; $pos++;}
				}
				if ($target[1]=='next')
			  { if ($row['id']!=$tile_id)   { $positions[$row['id']]=$pos; $pos++;}
          if ($row['id']==$target[0]) { $positions[$tile_id]=$pos; $pos++; $newparent=$row['parent'];}
				}  
			}
			//shift tile to the target group
			if ($tile['parent']!=$newparent)
			{ $sql="update seso_labels set parent=".$newparent." where id=".$tile_id;
		    $this->q->exec_sql($sql);
      }
			//save new positions
			foreach($positions as $id=>$position)
			{ $sql="update seso_labels set position=".$position." where id=".$id;
			  $this->q->exec_sql($sql);
			}	
    }			
	}
	private function _tileshift_position($id,$position)
	{ $tile=$this->q->get_record('seso_labels','id',$id,'position');
	  $from=$tile['position'];
		if ($from!=$position)
		{ $sql="select t1.parent,t2.id,t2.position from seso_labels t1 left join seso_labels t2
					  on t2.parent=t1.parent where t1.id=".$id." and t1.language=0 and t2.language=0 
					  and t2.kind=3 and t2.name='tiletitle' order by position, id";
			$this->q->query($sql);
			$positions=array();
			$pos=0;
			while ($row=$this->q->next_record()) 
			{ if($position<$from)
				{ if ($row['position']==$position) { $positions[$id]=$position;$pos++;}
				  if ($row['id']!=$id)   { $positions[$row['id']]=$pos; $pos++;}
				}
        if($position>$from)
				{ if ($row['id']!=$id)   { $positions[$row['id']]=$pos; $pos++;}
			    if ($row['position']==$position) { $positions[$id]=$position;$pos++;}
				}	
			}
			foreach($positions as $id=>$position)
			{ $sql="update seso_labels set position=".$position." where id=".$id;
       	$this->q->exec_sql($sql);
			}	
		}	
	}
	private function _addvar($name)
	{ $newid=$this->q->new_id('seso_labels','id');
		foreach($this->languages as $langnr=>$language)
	  { $sql="insert into seso_labels (id,kind,page,name,language)
		        values(".$newid.",1,'system','".addslashes($name)."',".$langnr.")";		
      $this->q->exec_sql($sql);
    }
		return $newid;
  }
	private function _getvars()
	{ $vars=array();
	  $sql="select id,page,name,text,info from seso_labels where kind=1 and language=".$this->language." order by name"; 
	  $this->q->query($sql);
		while ($row=$this->q->next_record()) 
		{ $vars[$row['id']]=$row;}
	  return $vars;
	}	
	private function _getvar($id)
	{ $var=array();
	  $sql="select * from seso_labels where id=".$id." and kind=1 order by language"; 
	  $this->q->query($sql);
		while ($row=$this->q->next_record()) 
		{ $var[$row['language']]=$row;}
	  return $var;
	}	
	private function _varcheck($field)
  { $sql="select id from seso_labels where kind=1 and name=".$field." and language=".$this->language;
	  $this->q->query($sql);
		return $this->q->rowcount;
	}
	private function _varsave($id,$field,$value,$language)
	{ $sql="update seso_labels set ".$field."='".addslashes($value)."' where kind=1 and id=".$id." and language=".$language;    
    $this->q->exec_sql($sql);	
	}
	private function _varremove($kind,$id)
	{ $sql="delete from seso_labels where kind=1 and id=".$id;    
    $this->q->exec_sql($sql);	
	}
//public functions

	public function getvariabele($name,$language)
	{ return $this->_getvariabele($name,$language);
	}
  public function getpages()
  { $this->_pages_init();
	  return $this->pages;
	}
  public function getpage($id,$fields)
  { $this->_page_init($id,$fields);
	  return $this->page;
	}
  public function getpagelabel($id)
	{ $this->_pagelabel_init($id);
	  return $this->pagelabel;
  }
	public function pagesave($kind,$id,$field,$value)
	{ return $this->_page_save($kind,$id,$field,$value);
	}
	public function pagelabelremove($kind,$id)
	{ $this->_pagelabel_remove($kind,$id);
	}
	public function pageremove($pageid)
	{ $this->_page_remove($pageid);
	}
	public function pagecheck($kind,$pageid,$field,$value)
  { return $this->_page_check($kind,$pageid,$field,$value);
	}
	public function gettiletree($id)
  { $this->_gettiletree($id);
	  return $this->tiletree;
  }
	public function getpagetitle($id)
	{ return $this->_getpagetitle($id);
	}
	public function gettilepath($id)
	{ return $this->_gettilepath($id);
  }	
	public function gettiles($id)
	{ return $this->_gettiles($id);
	}
	public function gettilerecord($id,$fields)
	{	return $this->_gettilerecord($id,$fields);
	}
	public function gettile($id)
	{ return $this->_gettile($id);
	}
	public function tileremove($id)
	{ return $this->_tileremove($id);
	}
	public function getsubcount($kind,$id)
  {	return $this->_getsubcount($kind,$id);
  }
	public function getsubtiles($id)
	{ return $this->_getsubtiles($id);
	}
	public function gettileimg($id)
	{ return $this->_gettileimg($id);
	}
	public function savetilevalue($id,$value)
	{ $this->_savetilevalue($id,$value);
	}
	public function savetilename($id,$value)
	{ return $this->_savetilename($id,$value);
	}
	public function savetiletitle($id,$value,$language)
	{ $this->_savetiletitle($id,$value,$language);
	}
	public function savetiletext($field,$id,$value,$language)
	{ $this->_savetiletext($field,$id,$value,$language);
	}
	public function savetilevideo($field,$id,$value,$language)
	{ $this->_savetilevideo($field,$id,$value,$language);
	}
  public function addsubpage($id)	
	{ return $this->_addsubpage($id);
	}
	public function addsub($kind,$id)
	{ return $this->_addsub($kind,$id);
	}
	public function addvideo($id)
	{ return $this->_addvideo($id);
	}
	public function removesubtext($id)
	{ $this->_removesubtext($id);
  }
	public function removesubvideo($id)
	{ $this->_removesubvideo($id);
  }
	public function shifttilesub($id,$shift)
	{ $this->_shifttilesub($id,$shift);
  }
	public function tileshift($tile_id,$to_parent)
	{ $this->_tileshift($tile_id,$to_parent);
	}
	public function tileshift_position($id,$position)
  { $this->_tileshift_position($id,$position);
  }
	public function addvar($name)
	{ return $this->_addvar($name);
	}
	public function getvars()
	{ return $this->_getvars();
	}	
	public function getvar($id)
	{ return $this->_getvar($id);
	}	
	public function varcheck($field)
  { return $this->_varcheck($field);
	}
	public function varsave($id,$field,$value,$language)
	{ return $this->_varsave($id,$field,$value,$language);
	}
	public function varremove($kind,$id)
	{ $this->_varremove($kind,$id);
	}
}
?>