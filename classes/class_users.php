<?php
if (file_exists("../inc/dbhulp.inc"))
{ require_once("../inc/dbhulp.inc");}
else
{ require_once("inc/dbhulp.inc");}
/*
usergroups:
0 homepage viewers
1 website viewers
2 content readers
3 app users
4 administrators
5 superuser
*/
class seso_users {
	private $q=0;
  private $language=0;
	public $groups=array();
	public $members=array();
	
	public function __construct($language)
  { $this->q=new db_joop();
    $this->language=$language;
    if ($language==0) 
		{	$this->groups=array(0=>'startpagina lezers',1=>'website lezers',2=>'inhoudgebruikers',3=>'professionele gebruikers',4=>'administrators',5=>'superusers');
	    $this->members=array(0=>'startpagina lezer',1=>'website lezer',2=>'inhoudgebruiker',3=>'professionele gebruiker',4=>'administrator',5=>'superuser');
		}	
    if ($language==1) 
    if ($language==2) 
		{	$this->groups=array(0=>'homepage readers',1=>'website readers',2=>'content users',3=>'professional users',4=>'administrators',5=>'superusers');
	    $this->members=array(0=>'homepage reader',1=>'website reader',2=>'content user',3=>'professional user',4=>'administrator',5=>'superuser');
		}	
		{	$this->groups=array(0=>'homepage readers',1=>'website reader',2=>'content users',3=>'professional users',4=>'administrators',5=>'superusers');
	    $this->members=array(0=>'homepage reader',1=>'website reader',2=>'content user',3=>'professional user',4=>'administrator',5=>'superuser');
		}	
  }
//private functions
  private function _userexit()
	{ 
	}
  private function _getuserlist($group)
	{ $ulist=array();
	  $sql="select * from seso_users where usergroup=".$group." order by organisation,fullname";
	  $this->q->query($sql);
		while ($row=$this->q->next_record()) { $ulist[$row['uid']]=$row;}
		return $ulist;
	}	
	private function _setpwmd5()
	{	$reset=array();
	  $sql="select uid,password from seso_users where password<>'' and password_md5=''";
    $this->q->query($sql);
    while ($row=$this->q->next_record()) {$reset[$row['uid']]=md5($row['password']);}
		foreach($reset as $uid=>$pw5) { $this->q->exec_sql("update seso_users set password_md5='".$pw5."' where uid=".$uid); }		
	}
  private function _check_nm($name)
	{ $sql="select uid from seso_users where user='".$name."'";
	  $this->q->query($sql);
  	if ($this->q->rowcount>0) { $ansr=1;} else { $ansr=0;}
    return $ansr;	
	}	
  private function _check_pw($nm,$pw5)
	{ $this->_setpwmd5();
	  $sql="select uid from seso_users where user='".$nm."' and password_md5='".$pw5."'";
	  $this->q->query($sql);
		if ($row=$this->q->next_record()) 
		{ $ansr=$row['uid'];
	    $sql="update seso_users set sid='".session_id()."' where uid=".$row['uid'];
			$this->q->exec_sql($sql);
		} 
	  else 
		{ $ansr=0;}
    return $ansr;		
	}	
	private function _checkaccess($uid,$sid)
	{ $sql="select uid from seso_users where uid=".$uid." and sid='".$sid."'";
	  $this->q->query($sql);
		return $this->q->rowcount;
	}	
  private function _adduser($usergroup,$date)
	{ $newuid=$this->q->new_id('seso_users','uid');
	  $sql="insert into seso_users (uid,usergroup,user,authorization,memberstart) 
		      values(".$newuid.",".$usergroup.",'new user',".$usergroup.",'".$date."')";
		$this->q->exec_sql($sql);
    return $newuid;		
	}
	private function _getuser($uid,$fields)
	{ $sql="select ".$fields." from seso_users where uid=".$uid;
	  $this->q->query($sql);
		return $this->q->next_record();
	}
	private function _get_visitor_user($email,$pw5)
	{ $user=array('uid'=>0,'name'=>'unknown');
	  $sql="select * from seso_users where user='".$email."' and password_md5='".$pw5."'";
	  $this->q->query($sql);
		if ($this->q->rowcount>0)
		{ $user=$this->q->next_record();}
	  return $user;
	}
  private function _saveuser($uid,$values)
	{ $sql="update seso_users set ";
    $komma="";
		foreach($values as $field=>$value)
    { $sql.=$komma." ".$field."='".addslashes($value)."'"; $komma=", ";}
    $sql.=" where uid=".$uid;
    $this->q->exec_sql($sql);		
	}
//public functions
  public function getuserlist($group)
	{ return $this->_getuserlist($group);
	}
	public function check_nm($nm)
	{ return $this->_check_nm($nm);
	}
  public function check_pw($nm,$pw5)
	{ return $this->_check_pw($nm,$pw5);
	}
	public function checkaccess($uid,$sid)
	{ return $this->_checkaccess($uid,$sid);
  }
	public function adduser($usergroup,$date)
	{ return $this->_adduser($usergroup,$date);
	}
  public function getuser($uid,$fields)
	{ return $this->_getuser($uid,$fields);
	}
  public function get_visitor_user($email,$pw5)
	{ return $this->_get_visitor_user($email,$pw5);
	}
	public function saveuser($uid,$values)
	{ $this->_saveuser($uid,$values);
	}
}
?>	