<?php
require_once("../../inc/dbhulp.inc");

class seso_persons {
	private $q=0;
	private $language=0;
	
  public function __construct($page,$language)
  { $this->q=new db_joop();
		$this->language=$language;
	}

}
?>