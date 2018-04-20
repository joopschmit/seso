<?php
if (file_exists("../inc/dbhulp.inc"))
{ require_once("../inc/dbhulp.inc");}
else
{ require_once("inc/dbhulp.inc");}

class brm_variabelen {
  private $vlijst=array();
	private $q=0;
	private $cust_id=-1;
	private $variabele="";
	private $keuzereeks=array();
	
	public function __construct($cust_id)
  { $this->q=new db_joop();
    $this->cust_id=$cust_id;
	}
  //private functions

	private function init_variabele($vid,$vnaam)
  { $nm="";
		if ($vid>0 || $vnaam!="")
		{ if ($vnaam!="") {$where="vnaam='".$vnaam."'";} else {$where="vid=".$vid;}
			$sql="select vinhoud from brm_variabelen where cust_id=".$this->cust_id." and ".$where;
			$this->q->query($sql);
			if ($row=$this->q->next_record()) {$nm=$row['vinhoud'];} else {$nm="";}
			//standaardinstellinegn
			if ($vnaam=='adrespositie' && $nm=="0;0")
			{ $nm="40;130";}
		}
		$this->variabele=$nm;
	}
	private function init_keuzereeks($soort)
  { $sql="select vid, vinhoud from brm_variabelen where cust_id=".$this->cust_id." and vsoort='".$soort."' order by vnr,vid";
    $this->q->query($sql);
    $t=0;
    $this->keuzereeks=array();
    while ($row=$this->q->next_record()) { $this->keuzereeks[$t]=$row['vinhoud']; $t++; }
  }
	private function get_keuzes($tabel,$id,$veldnaam,$selectie,$order)
	{ $keuzes=array();
	  $tcode="";
	  $tgeg=explode(" ",$tabel); 
		if (count($tgeg)>1) { $tcode=$tgeg[1].".";}
		$sql="select distinct ".$id." as id, ".$veldnaam." as optie from ".$tabel." where ".$tcode."cust_id=".$this->cust_id." and ".$id.">0 ";
		if ($selectie!="") { $sql.=" and ".$selectie." ";}
		if ($order!="") { $sql.=" order by ".$order; }
	  $this->q->query($sql);
  	while ($row=$this->q->next_record()) { $keuzes[$row['id']]=$row['optie'];}
	  return $keuzes;
	}
	private function varlijst()
	{ $vlijst=array();
	  $sql="select vsoort from brm_variabelen where vsoort<>'' and vid>0 group by vsoort order by vsoort";
    $this->q->query($sql);
		$teller=0;
		while ($row=$this->q->next_record()) { $vlijst[$teller]=$row['vsoort']; $teller++;}
		return $vlijst;
	}
	//public functions
	public function haal_variabele($vid,$vnaam)
	{ $this->init_variabele($vid,$vnaam);
	  return $this->variabele;
	}	
	public function haal_keuzereeks($soort)
	{ $this->init_keuzereeks($soort);
	  return $this->keuzereeks;
	}	
	public function haal_keuzes($tabel,$id,$veld,$selectie,$order)
	{ return $this->get_keuzes($tabel,$id,$veld,$selectie,$order);
	}
	public function haal_varlijst()
	{ return $this->varlijst();
	}
}

?>