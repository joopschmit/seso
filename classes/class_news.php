<?php
if (file_exists("../inc/dbhulp.inc"))
{ require_once("../inc/dbhulp.inc");}
else
{ require_once("inc/dbhulp.inc");}

class seso_news {
	private $q=0;
	private $language=0;
	public $languages=array();
  public function __construct($language)
  { $this->q=new db_joop();
		$this->language=$language;
		$this->languages=$this->q->get_languages($language);
	}
//private functions
  private function _getprevnext)$nid)
	{
	}
  private function _getnewsitems($archive,$usergroup)
	{ $nlist=array();
	  $sql="select nid,nitem,ndate,nwatched,narchive,narchivedon from seso_news where nusergroup<=".$usergroup." and language=".$this->language;
		if ($archive>0) { $sql.=" and narchive!=0 and narchive<=".$archive;} else { $sql.=" and narchive=0";} 
		$sql.=" order by narchive,ndate desc";
	  $this->q->query($sql);
		while ($row=$this->q->next_record()) {$nlist[$row['nid']]=$row;}
		return $nlist; 
	}
  private function _check_newsrecords($nid)
	{ $item=array();
	  $sql="select * from seso_news where narchive>-1 and nid=".$nid." and nitem<>'' order by language";
	  $this->q->query($sql);
		while ($row=$this->q->next_record()) { $item[$row['language']]=$row; $filled=$row['language'];}
		$n=$item[$filled];
		foreach($this->languages as $lang=>$langname)
		{ if (isset($item[$lang])==false)
			{ $sql="insert into seso_news (nid,nitem,ntext,ndate,language,nwatched,narchive,nusergroup)
		          values(".$n['nid'].",'".$n['nitem']."','".$n['ntext']."','".$n['ndate']."',".$lang.",".$n['nwatched'].",".$n['narchive'].",".$n['nusergroup'].")";
			  $this->q->exec_sql($sql);
			}	
    }
	}	
  private function _getnewsitem($nid)
	{ $item=array();
	  $sql="select * from seso_news where nid=".$nid." order by language";
	  $this->q->query($sql);
		if ($this->q->rowcount!=count($this->languages))
		{ $this->_check_newsrecords($nid);
			$sql="select * from seso_news where nid=".$nid." order by language";
			$this->q->query($sql);
    }	
		while ($row=$this->q->next_record()) 
	  { $item[$row['language']]=$row;}	
	  return $item; 
	}
  private function _getnewsimage($nid)
	{ $item=array();
	  $sql="select nimage,nimagewidth from seso_news where nid=".$nid." and language=0";
	  $this->q->query($sql);
		$row=$this->q->next_record(); 
	  return $row; 
	}
  private function _addnewsitem($item,$date,$usergroup)
	{ $newnid=$this->q->new_id('seso_news','nid');
	  foreach($this->languages as $nr=>$language)
		{ $sql="insert into seso_news (nid,nitem,ndate,language,nusergroup) 
	          values(".$newnid.",'".addslashes($item)."','".addslashes($date)."',".$nr.",".$usergroup.")";
     	$this->q->exec_sql($sql);
    }
		return $newnid;		
	}
	private function _newssave($nid,$field,$value,$language)
	{ $sql="update seso_news set ".$field."='".addslashes($value)."' where nid=".$nid." and language=".$language;
	  $this->q->exec_sql($sql);
	}	
	private function _saveimage($nid,$field,$value)
	{ $sql="update seso_news set ".$field."='".$value."' where nid=".$nid;
	  $this->q->exec_sql($sql);
	}	
  private function _newsarchive($date,$nid)
	{ $sql="update seso_news set narchive=9, narchivedon='".$date."' where nid=".$nid;
	  $this->q->exec_sql($sql);
	}	
  private function _newsunarchive($nid)
	{ $sql="update seso_news set narchive=0, narchivedon='' where nid=".$nid;
	  $this->q->exec_sql($sql);
	}	
	private function _getlastitem()
	{ $id=$this->q->new_id('seso_news','nid');
	  $nid=$id-1;
		return $nid;
  }		
//public functions

  public function getnewsitems($archive,$usergroup)
	{ return $this->_getnewsitems($archive,$usergroup);
	}
  public function getnewsitem($nid)
	{ return $this->_getnewsitem($nid);
	}
  public function getnewsimage($nid)
	{ return $this->_getnewsimage($nid);
	}
  public function addnewsitem($item,$date,$usergroup)
	{ return $this->_addnewsitem($item,$date,$usergroup);
	}
	public function newssave($nid,$field,$value,$language)
	{ $this->_newssave($nid,$field,$value,$language);
  }
	public function saveimage($nid,$field,$value)
	{ $this->_saveimage($nid,$field,$value);
	}
	public function newsarchive($date,$nid)
	{ $this->_newsarchive($date,$nid);
	}
  public function newsunarchive($nid)
	{ $this->_newsunarchive($nid);
	}
	public function getlastitem()
	{ return $this->_getlastitem();
	}

}
?>