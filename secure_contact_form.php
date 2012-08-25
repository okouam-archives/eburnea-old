<?php

/**
*	powered by @cafewebmaster.com
*	free for private use
*	please donate by paypal or give a backlink
*/



// Configuration
define("ADMIN_MAIL", "contact@residence-eburnea.net");
define("MAIL_SUBJECT", "Message sur Formulaire de contact de votre site Web");

// one line input fields like name, phone, fax, website etc, just add or remove what do you want
$contact_fields_input = array("Nom", "Email", "Tel", "Adresse postale"); // no spaces, no symbols

// multiline input field(s), just add or remove what do you want
$contact_fields_textarea = array("Message");

// required fields, visitor must fill to send form
$contact_fields_required = array("Nom", "Email", "Tel", "Message");





// You dont need to change below
###############################################################################

foreach($_GET as $k=>$v){
	$req_string .= "".$k."=".$v."&";
}
$selfurl = 'http://'.$_SERVER["HTTP_HOST"].$_SERVER["PHP_SELF"]."?".$req_string ;


session_start();


function playCaptcha(){

	$text = rand(999,9999); // 4 chars number
	$seckey = md5(strtolower($text));
	$_SESSION["seckey"] = $seckey;
	
	$img_w = 80;
	$img_h = 30;
	$left = 5;
	$top = 2;
	$font = 'verdana.ttf';
	$font_size = 6;
	$sleep = 1; // against  brute-force
	
	
	
	
	
	
	$imgd = imagecreate($img_w, $img_h);
	
	$bg_light = imagecolorallocate($imgd, rand(200,255), rand(200,255), rand(200,255));
	$red = imagecolorallocate($imgd, 255, 0, 0);
	
	imagefill($imgd, 0, 0, $bg_light);
	
		
	$polight = imagecolorallocate($imgd, rand(155,240), rand(155,240), rand(155,240));
	$points = array( rand(0,$img_w), rand(0,$img_h),  rand(0,$img_w), rand(0,$img_h),
                     rand(0,$img_w), rand(0,$img_h),  rand(0,$img_w), rand(0,$img_h),  rand(0,$img_w), rand(0,$img_h));
	imagefilledpolygon($imgd, $points, 5, $polight);
	
	
	
	while($i2<10){ $i2++;
		$clight = imagecolorallocate($imgd, rand(155,240), rand(155,240), rand(155,240));
		$r1 = rand(0,$img_w);   $r2 = rand(0,$img_h);   $r3 = rand(0,$img_w);   $r4 = rand(0,$img_h);
		imageline ( $imgd, $r1, $r2, $r3, $r4,  $clight);
	#   imageline ( $imgd, $r1+1, $r2, $r3+1, $r4,  $clight);
	}
	
	
	while( $i < strlen($text) ){ $i++;
		$darkcolor = imagecolorallocate($imgd, rand(0,111), rand(0,222), rand(0,222));
		$current_letter = substr($text, $i-1, 1);
	
		$font2 = $font;
	#       if( is_numeric($current_letter) ){ $darkcolor = $red ; $font2 = 'times.ttf'; }
	#       imagettftext($imgd, $font_size, rand(-45,45), $left+($i*30), $top+rand(25,30), $darkcolor, $font2, $current_letter);
	
      imagestring($imgd, $font_size, $left+(($i-1)*20), $top+rand(1,5), $current_letter, $darkcolor);
	}
	
	sleep($sleep);
	
	header("Pragma: no-cache");
	header("Content-type: image/jpg");
	ImageJPEG( $imgd );
	imagedestroy($imgd);
	
	exit;

} // end of playCaptcha









function displayContactForm(){
	
	global $selfurl, $contact_fields_input, $contact_fields_textarea, $contact_fields_required ;
	
	foreach($contact_fields_input as $v){ 
		$redmark = ($_POST && in_array($v, $contact_fields_required) && !$_POST[$v]) ? "redmark" : "" ;
		if($redmark) $error_req++;
		$required = in_array($v, $contact_fields_required) ? "*" : "" ;
		$htmo_form .= "<p><label for=\"$v\">$v $required :</label>
		<input name=\"$v\" size=\"60\" value=\"".$_POST[$v]."\" class=\"contactFields $redmark\" /></p>"; 
		}
	
	foreach($contact_fields_textarea as $v)	{ 
		$redmark = ($_POST && in_array($v, $contact_fields_required) && !$_POST[$v]) ? "redmark" : "" ;
		if($redmark) $error_req++;
		$required = in_array($v, $contact_fields_required) ? "*" : "" ;
		$htmo_form .= "<p><label for=\"$v\">$v $required :</label>
		<textarea name=\"$v\" cols=\"60\" rows=\"20\" class=\"contactFields $redmark\">$_POST[$v]</textarea></p>";
		}
	
		$redmark = ($_POST && $_SESSION["seckey"] != md5($_POST['captcha'])) ? "redmark" : "" ;
		if($redmark) $error_req++;
				$htmo_form .= "<p><label for=\"captcha\">Captcha:</label> 
			<img src=\"".$selfurl."yekta=captcha\" height=\"30\" width=\"80\" align=\"absmiddle\"> 
			&#187;&#187;
			<input name=\"captcha\" class=\"captcha $redmark\" /> 
			<input type=\"submit\"  class=\"submit\" /></p>";
	
		
		if($_POST && !$error_req){	
			foreach($_POST as $k=>$v){
				if($k == "captcha") continue;
				$mailbody .= "$k : \n$v\n\n";
			}
		
			if(@mail(ADMIN_MAIL, MAIL_SUBJECT, $mailbody, "From: ".ADMIN_MAIL."\r\n")) {
				$htmo_form = "<p>Votre email a été envoyé. Merci</p>";
			} else {
				$htmo_form = "<p class=\"redmark\">Error: Votre message n'a pu être envoyé!</p>";
			}
		}

		
echo <<<cafewebmaster_com
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
	<head>
		<style type="text/css">
			#cform { margin: auto; width: 310px; background-color: #fefce5; padding: 10px; font: bold 12px Tahoma; }
			#cform img { border: 1px solid #636363; }
			#cform .redmark { border: 2px solid #f00; }
			#cform h2 { background-color: #e1950c; padding: 5px; margin: 0; color: #fff; }
			#cform p { background-color: #fefad7; padding: 5px ;  }
			#cform label { display: block; float: left; width: 105px; clear: left; color: #636363; }
			#cform input.contactFields { width: 290px; color: #636363; }
			#cform input.submit { width: 120px; color: #636363; }
			#cform input.captcha { width: 40px; color: #636363; }
			#cform textarea.contactFields { width: 290px; height: 10em; color: #636363; }
		</style>
	</head>
	<body>

	<div id="cform">
		<h2>MESSAGE CONTACT</h2>
		<form method="post" action="$selfurl">$htmo_form</form>
		
	</div>

	</body></html>
cafewebmaster_com;

}




switch($_GET['yekta']){
	case "captcha": 
		playcaptcha(); 
		break;
		
	default: displayContactForm();
}

?>

