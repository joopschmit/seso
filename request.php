<?php
	session_start();
	if(isset($_GET['fase'])) { $fase=$_GET['fase'];} else { $fase=0;}
	$sid=session_id();
	$language=$_SESSION['language'];
	include('inc/datefunctions.inc');
	include('classes/class_visitors.php');
//	include('classes/class_users.php');
  require_once('Rmail/Rmail.php');
	$vs=new seso_visitors();
//	$users=new seso_users($language);
  $email=$_POST['email'];
	$pw=$_POST['pw'];
	
	$authorized=$vs->check_visitor($sid,$email,$pw);
//	if ($authorized>0)
//	{ $_SESSION['user']=$users->get_visitor_user($email,md5($pw));}
	if ($authorized==-1)
	{  //email met aanvraag versturen
		$mail = new Rmail();
		$mail->setFrom('Sentro Solari Visitor<checkmail@moval.nl>');
		$mail->setSubject('Visitor request mmail');
		$mail->setPriority('high');

		$txt="Onderstaande bezoeker wil toegang tot de website van Sentro Solari";
		$txt.="\n\nid :".$sid."\n";
		$txt.="\nEmail adres :".$email;
		$txt.="\nToegangscode:".$pw;
    $txt.="\n\nWebbeheerder Sentro Solari"; 

		$mail->setText($txt);

		$html="Onderstaande bezoeker wil toegang tot de website van Sentro Solari<br>";
		$html.="<br>id: ".$sid;
		$html.="<br>Emailadres : ".$email;
		$html.="<br>Toegangscode: ".$pw;
    $html.="<br><br>Webbeheerder Sentro Solari"; 
		
		$mail->setHTML($html);
    
		$mail->setReceipt('joop@eprom.nl');
		$address = 'joop@eprom.nl';
		$result  = $mail->send(array($address));
		if ($result==false) { $autorized=-2;}
	}
  echo $authorized;
?>