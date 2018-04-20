<?php
	session_start();
	$title=$_GET['title'];
	include('inc/show.inc');
	include('inc/datefunctions.inc');
	//include('../inc/menu.inc');
	include('classes/class_labels.php');
	//if(isset($_SESSION['user'])==false) { echo "<script>top.window.close()</script>";}
	if (isset($_SESSION['language'])) { $language=$_SESSION['language'];} else { echo "<script>top.location='home.php';</script>";}
	$labels = new seso_labels($title,$language);
  $now=substr(actualdatecode(),0,8);
  $kind=$title;
	$id=$_GET['id']; 
	if (isset($_GET['unit']))
	{ $unitname=$_GET['unit'];}
	else
	{ $unitname=$title;}
	
	$helpid=$labels->gethelpID($title);

	$image=array(
	       0=>'nav_home.png',
	       1=>'nav_back.png',
	       2=>'nav_edit.png',
	       3=>'nav_save.png',
	       4=>'nav_filter.png',
	       5=>'nav_delete.png',
	       6=>'nav_start.png',
	       7=>'nav_new.png',
	       8=>'nav_search.png',
	       9=>'nav_action.png',
	       10=>'nav_groeperen1.png',
	       11=>'nav_groeperen0.png',
	       12=>'nav_submenu.png',
         13=>'nav_afdruk.png',
         14=>'nav_copy.png',
         15=>'nav_printer.png',
         16=>'nav_verzend.png',
         17=>'nav_plakken.png',
         18=>'nav_figuren.png',
         19=>'nav_verderzoeken.png',
         20=>'nav_afdrukselectie.png',
	       21=>'nav_submenu.png', //extra als submitknop voor beheerselectie
         22=>'nav_maanddecl.png',
         23=>'nav_googleagenda.png',
         24=>'nav_googleimport.png',
	       25=>'nav_save.png',
				 26=>'nav_herhalen.png',
         27=>'nav_persoonplus.png',
         28=>'nav_projectplus.png',
				 29=>'nav_briefplus.png',
				 30=>'nav_back.png',
         31=>'nav_lijst.png',
				 32=>'nav_betalen.png',
         33=>'nav_stap1.png',				 
         34=>'nav_stap2.png',				 
         35=>'nav_stap3.png',				 
         36=>'nav_stap4.png',
         37=>'nav_declaraties.png',
				 38=>'nav_stap2_terug.png',
         39=>'nav_next.png',	
				 40=>'nav_annuleren.png',
         41=>'nav_offerte.png',
				 42=>'nav_newsub.png',
				 43=>'nav_tijdreg.png',
				 44=>'nav_brieven.png',
				 45=>'nav_email.png',
				 46=>'nav_mailing.png',
				 47=>'nav_emailing.png',
				 48=>'nav_adressen.png',
				 49=>'nav_allelijsten.png',
				 50=>'nav_download.png',
				 51=>'nav_selectx.png',
				 52=>'nav_select0.png',
				 53=>'nav_selectiehulp.png',
				 54=>'nav_tree.png',
				 55=>'nav_instellingen.png',
				 56=>'nav_docgeg.png',
				 57=>'nav_tekstblok.png',
				 58=>'nav_figuurblok.png',
				 59=>'nav_tabelblok.png',
				 60=>'nav_uitbreiden.png',
				 61=>'nav_samenvoegen.png',
				 62=>'nav_delen.png',
				 63=>'nav_openen.png',
				 64=>'nav_ordening.png',
				 65=>'nav_import.png',
				 66=>'nav_valuta.png',
				 67=>'nav_emailplus.png',
				 68=>'nav_legenamen.png',
				 69=>'nav_deletevraag.png',
				 70=>'nav_logout.png',
				 71=>'nav_prullenmand.png',
				 72=>'nav_archiving.png'
				 ); 
	$imgpath='img/';			 

  function title2path($text)
	{ global $unitname;
	  if ($text=='searchlist') {$text=$unitname;}
	  $ts=array();
		$ts['title']=$text;
		$ts['path']=$text."/".$text.".php";
		return $ts;
	}
	
  function menubutton($kind,$level,$destination,$title,$id,$check="",$info="")
	{ global $image,$imgpath,$labels;
	  if ($check!="") { $checktext=$labels->getlabel($check);} else { $checktext="";}
		if ($info=="") { $info=$labels->getlabel($title);} else { $info=$labels->getpagelabel($info);}
	  if ($kind>1 && $kind!=19 && $kind!=40)  { $style=" align='right' ";} else { $style="style='float: left;'";}
	  echo "<a href='#' class='submenu' onclick='goto(\"".$level."\",\"".$destination."\",\"".$title."\",\"".$id."\",\"".$checktext."\");'>";
    echo "<img src='".$imgpath.$image[$kind]."?t=1' width=36 ".$style." title='".$info."'></a>";
	}
	
	$pagetitle=$labels->getlabel('title'); 
	$st=title2path($kind);

  menubutton(0,0,"home.php","mainmenu",0,0);
	
	//units for translations
  if ($kind=='submenu_login')
	{
	}	
	if ($kind=='submenu')
  { menubutton(70,1,"am/am_exit.php","am_exit",$id,0); 
  }
  if ($kind=='news_items')
  { menubutton(1,0,"submenu.php","submenu",0,0);
    menubutton(71,1,"news/news_archived.php","news_archived",0,0); 
    menubutton(7,1,"news/news_add.php","news_add",0,0); 
  }
  if ($kind=='news_archived')
  { menubutton(1,0,"news/news_items.php","news_items",0,0);
  }
  if ($kind=='news_item')
  { menubutton(1,0,"news/news_items.php","news_items",0,0);
    menubutton(2,1,"news/news_edit.php","news_edit",$id,0); 
  }
  if ($kind=='news_archiveditem')
  { menubutton(1,0,"news/news_archived.php","news_archived",0,0);
  }
  if ($kind=='news_edit')
  { menubutton(1,0,"news/news_item.php","news_item",$id,0);
    menubutton(72,1,"news/news_archive.php","news_archive",$id,0); 
  }
	if ($kind=='news_sheet')
  {   }
  
  if ($kind=='vs_visitors')
  { menubutton(1,0,"submenu.php","submenu",0,0);
  }
 if ($kind=='vs_visitor_edit')
  { menubutton(1,0,"vs/vs_visitors.php","vs_visitors",1,0);
  }
  if ($kind=='us_users')
  { menubutton(1,0,"submenu.php","submenu",0,0);
    menubutton(7,1,"us/us_user_edit.php","us_user_edit","-1&usergroup=".$id,0); 
  }
  if ($kind=='us_user_edit')
  { if ($_GET['usergroup']<3)
		{ menubutton(1,0,"submenu.php","submenu",0,0);}
	  else
		{ menubutton(1,0,"us/us_users.php","us_users",$_GET['usergroup'],0);
      menubutton(5,1,"us/us_user_remove.php","us_user_remove",$id,"removewarning"); 
	  }	
  }
	if ($kind=='am_pages')
  { menubutton(1,0,"submenu.php","submenu",0,0);
    menubutton(7,1,"am/am_page_add.php","am_page_add",0,0); 
  }
	if ($kind=='am_page_add') 
  { menubutton(1,0,"am/am_pages.php","am_pages",0,0);
  }
	if ($kind=='am_page_sheet')
  { menubutton(1,0,"am/am_pages.php","am_pages",0,0);
    menubutton(7,1,"am/am_pagelabel_add.php","am_pagelabel_add",$id,0); 
  }
	if ($kind=='am_pagelabel_add')
  { menubutton(1,0,"am/am_page_sheet.php","am_page_sheet",$id,0);
  }
	if ($kind=='am_pagelabel_edit')
  { menubutton(1,0,"am/am_page_sheet.php","am_page_sheet",$id,0);
    menubutton(5,1,"am/am_pagelabel_remove.php","am_pagelabel_remove",$id,"removewarning");
  }
	if ($kind=='am_vars')
  { menubutton(1,0,"submenu.php","submenu",0,0);
    menubutton(7,1,"am/am_var_add.php","am_var_add",0,0); 
  }
	if ($kind=='am_var_add')
  { menubutton(1,0,"am/am_vars.php","am_vars",0,0);
  }
	if ($kind=='am_var_edit')
  { menubutton(1,0,"am/am_vars.php","am_vars",0,0);
    menubutton(5,1,"am/am_var_remove.php","am_var_remove",$id,"removewarning");
  }

	if ($kind=='cms_homepage')
  { menubutton(1,0,"submenu.php","submenu",0,0);
  }
	if ($kind=='cms_tile_sheet')
  { menubutton(1,0,"cms/cms_homepage.php","cms_homepage",0,0);
    menubutton(5,1,"cms/cms_tile_remove.php","cms_tile_remove",$id,"removewarning");
  }
	if ($kind=='cms_tile_edit')
  { menubutton(1,0,"cms/cms_tile_sheet.php","cms_tile_sheet",$id,0);
 	}		
	if ($kind=='cms_tiletitle_edit')
  { menubutton(1,0,"cms/cms_tile_sheet.php","cms_tile_sheet",$id,0);
  }
	if ($kind=='cms_tileimg_edit')
  { menubutton(1,0,"cms/cms_tile_sheet.php","cms_tile_sheet",$id,0);
  }
	if ($kind=='cms_tiletext_edit')
  { menubutton(1,0,"cms/cms_tile_sheet.php","cms_tile_sheet",$_GET['parent'],0);
    menubutton(5,1,"cms/cms_tiletext_remove.php","cms_tiletext_remove",$id,"removewarning");
  }
	if ($kind=='cms_tilevideo_edit')
  { menubutton(1,0,"cms/cms_tile_sheet.php","cms_tile_sheet",$_GET['parent'],0);
    menubutton(5,1,"cms/cms_tilevideo_remove.php","cms_tilevideo_remove",$id,"removewarning");
  }
  //apps
	if ($kind=='app_page')
  { menubutton(1,0,"home.php","mainmenu",0,0);
  }
	if (substr($title,0,4)=='app_')
	{ $pagetitle=$labels->gettilepath($id);
	}

  $logout=array(0=>'afmelden',1=>'log out',2=>'log out');
	if (isset($_SESSION['user']['fullname'])) { $username=$_SESSION['user']['fullname'];} else { $username="";} 
	echo "<script>
	  $('#pagetitle').html(\"".$pagetitle."\");
	  $('#log').html(\"".$username."<br>\");
		helpid='".$helpid."';
	  if (helpid>0) 
	  { $('#seso_help').html('<a href=\"#\" onclick=\"toonhelp('+helpid+',".$language.");\"><img src=\"img/helpknop.png\"></a>');}
	  else
		{ $('#seso_help').html('');}
  </script>";

?>