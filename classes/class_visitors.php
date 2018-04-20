<?php
if (file_exists("../inc/dbhulp.inc"))
{ require_once("../inc/dbhulp.inc");}
else
{ require_once("inc/dbhulp.inc");}

class seso_visitors {
	
  public function __construct()
  { $this->q=new db_joop();
	}
  private function _saverequest($sid,$req,$date)
  { $sql="select vid from seso_visitors where sid='".$sid."'";
	  $this->q->query($sql);
		if ($this->q->rowcount==0)
	  { $sql="insert into seso_visitors (ip,sid,query,vdate,request)
	        values('".$req['REMOTE_ADDR']."','".$sid."','".$req['QUERY_STRING']."','".$date."','".serialize($req)."')";
      $this->q->exec_sql($sql);
		}	
	}
	private function _getauthorized($sid)
	{ $sql="select authorized from seso_visitors where sid='".$sid."' order by vid desc";
	  $this->q->query($sql);
		if ($this->q->rowcount>0) { $row=$this->q->next_record(); $auth=$row['authorized'];} else { $auth=0;}
		return $auth;
	}	
	private function _authorize($sid,$level)
	{ $sql="update seso_visitors set authorized=".$level." where sid='".$sid."'";
    $this->q->exec_sql($sql);
	}
	private function _visitorexit($sid)
	{ $sql="update seso_visitors set authorized=0 where sid='".$sid."'";
    $this->q->exec_sql($sql);
	}	
  private function _check_visitor($sid,$email,$pw)
	{ $au=0;
	  $sql="select vid,authorized from seso_visitors where (pw<>'' and pw='".$pw."' and email<>'' and email='".$email."') or (authorized=1 and sid='".$sid."')";
	  $this->q->query($sql);
		if ($this->q->rowcount>0) 
		{ $row=$this->q->next_record();
	    $au=$row['authorized']; 
      $sql="update seso_visitors set authorized=1 where sid='".$sid."'";			
			$this->q->exec_sql($sql);
		}
		else
		{ $au=-1;
	    $sql="update seso_visitors set email='".$email."',pw='".$pw."',authorized=".$au." where sid='".$sid."'";
			$this->q->exec_sql($sql);
		}
    return $au;	
	}
	private function _getvisitor($vid,$fields)
	{ $sql="select ".$fields." from seso_visitors where vid=".$vid;
	  $this->q->query($sql);
		return $this->q->next_record();
	}
  private function _getvisitorlist($group)
	{ $vlist=array();
	  $sql="select *,count(ip) as number from seso_visitors ";
		if ($group==1) 
		{ $sql.=" where email<>'' group by email order by email";}
		else
		{ $sql.=" where email='' group by ip order by vdate,ip";}
	  $this->q->query($sql);
		while ($row=$this->q->next_record()) { $vlist[$row['vid']]=$row;}
		return $vlist;
	}	
  private function _savevisitor($vid,$values)
	{ $sql="update seso_visitors set ";
    $komma="";
		foreach($values as $field=>$value)
    { $sql.=$komma." ".$field."='".addslashes($value)."'"; $komma=", ";}
    $sql.=" where vid=".$vid;
    $this->q->exec_sql($sql);		
	}
	
//public functions  
	public function saverequest($sid,$request,$date)
	{ $this->_saverequest($sid,$request,$date);
	}
	public function getauthorized($sid)
	{ return $this->_getauthorized($sid);
	}
	public function authorize($sid,$level)
	{ $this->_authorize($sid,$level);
	}
	public function visitorexit($sid)
  { $this->_visitorexit($sid);
	}
  public function check_visitor($sid,$email,$pw)
	{ return $this->_check_visitor($sid,$email,$pw);
	}
  public function getvisitor($vid,$fields)
	{ return $this->_getvisitor($vid,$fields);
	}
	public function getvisitorlist($group)
	{ return $this->_getvisitorlist($group);
  } 
	public function savevisitor($vid,$values)
	{ $this->_savevisitor($vid,$values);
	}
	
}
?>