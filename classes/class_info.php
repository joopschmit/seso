<?php
if (file_exists("../../inc/dbhulp.inc"))
{ require_once("../../inc/dbhulp.inc");}
else
{ if (file_exists("../inc/dbhulp.inc"))
  { require_once("../inc/dbhulp.inc");}
  else
  { require_once("inc/dbhulp.inc");}
}
class seso_info {
	private $q=0;
	private $language=0;
	
  public function __construct($language)
  { $this->q=new db_joop();
		$this->language=$language;
	}

}
?>