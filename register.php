<?php
/**
 * File: register.php
 * Description: User registration
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


// Import PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;// Load Composer's autoloader

require 'includes/PHPMailer/PHPMailer/src/Exception.php';
require 'includes/PHPMailer/PHPMailer/src/PHPMailer.php';
require 'includes/PHPMailer/PHPMailer/src/SMTP.php';

include_once "./includes/config.inc.php";
include("db.php");

$chars=array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z","a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z","0","1","2","3","4","5","6","7","8","9");

if($_POST['email'] != "") $email = $_POST['email'];

function check_email_address($email)
{
   $isValid = true;
   $atIndex = strrpos($email, "@");
   if (is_bool($atIndex) && !$atIndex)
   {
	  $isValid = false;
   }
   else
   {
	  $domain = substr($email, $atIndex+1);
	  $local = substr($email, 0, $atIndex);
	  $localLen = strlen($local);
	  $domainLen = strlen($domain);
	  if ($localLen < 1 || $localLen > 64)
	  {
		 // local part length exceeded
		 $isValid = false;
	  }
	  else if ($domainLen < 1 || $domainLen > 255)
	  {
		 // domain part length exceeded
		 $isValid = false;
	  }
	  else if ($local[0] == '.' || $local[$localLen-1] == '.')
	  {
		 // local part starts or ends with '.'
		 $isValid = false;
	  }
	  else if (preg_match('/\\.\\./', $local))
	  {
		 // local part has two consecutive dots
		 $isValid = false;
	  }
	  else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))
	  {
		 // character not valid in domain part
		 $isValid = false;
	  }
	  else if (preg_match('/\\.\\./', $domain))
	  {
		 // domain part has two consecutive dots
		 $isValid = false;
	  }
	  else if
(!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/',
				 str_replace("\\\\","",$local)))
	  {
		 // character not valid in local part unless
		 // local part is quoted
		 if (!preg_match('/^"(\\\\"|[^"])+"$/',
			 str_replace("\\\\","",$local)))
		 {
			$isValid = false;
		 }
	  }
	  if ($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A")))
	  {
		 // domain not found in DNS
		 $isValid = false;
	  }
   }
   return $isValid;
}

if($_POST['submit']!=""){

	$firstname = trim($_POST['firstname']);
	$lastname = trim($_POST['lastname']);
	$email = strtolower(trim($_POST['email']));
	$password = $_POST['password'];
	$passwordconfirm = $_POST['passwordconfirm'];

	if($firstname==""){$error.=$errordelim."First Name cannot be blank.";$errordelim="<br>";}
	if($lastname==""){$error.=$errordelim."Last Name cannot be blank.";$errordelim="<br>";}
	if($email==""){$error.=$errordelim."Email cannot be blank.";$errordelim="<br>";}
	if($password==""){$error.=$errordelim."Password cannot be blank.";$errordelim="<br>";}

	if($email!=""){
		if(!check_email_address($email)){
			$error.=$errordelim."Invalid Email Address.";$errordelim="<br>";
		}
	}

	if($email!=""){
		$mailrow=$db->get_row_prepared("SELECT * FROM users WHERE email=$1", array($email));
		if($mailrow->pkey != ""){
			if($mailrow->deleted == true){
				$error.=$errordelim."Email Address ($email) is already registered at StraboSpot.";$errordelim="<br>";
			}else{
				$error.=$errordelim."Email Address ($email) has been deleted and is unusable. Please contact strabospot@gmail.com if you would like to re-activate this account.";$errordelim="<br>";
			}

		}
	}

	if($password!=""){
		if($password!=$passwordconfirm){
			$error.=$errordelim."Passwords don't match.";$errordelim="<br>";
		}
	}

	//Check Captcha from Google

	$secret = $captchasecret;
	$response = $_POST["g-recaptcha-response"];

	$url = 'https://www.google.com/recaptcha/api/siteverify';
	$fields = array(
		'secret' => urlencode($secret),
		'response' => urlencode($response)
	);

	//url-ify the data for the POST
	foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
	rtrim($fields_string, '&');

	//open connection
	$ch = curl_init();

	//set the url, number of POST vars, POST data
	curl_setopt($ch,CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch,CURLOPT_POST, count($fields));
	curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

	//execute post
	$result = curl_exec($ch);

	//close connection
	curl_close($ch);

	$result = json_decode($result);

	if(!$result->success){
		$error.=$errordelim."Captcha Error. Please check the captcha box.";$errordelim="<br>";
	}

	if(substr_count($email, '.') > 4){
		header("Location: /");
		exit();
	}

	if($error==""){

		//no errors, so let's put in user and send out confirmation email

		$randstring="";
		for($x=0;$x<21;$x++){
			$randstring.=$chars[rand(0,61)];
		}

		$db->prepare_query("INSERT INTO users (firstname, lastname, password, hash, email) VALUES ($1, $2, crypt($3, gen_salt('md5')), $4, $5)",
			array($firstname, $lastname, $password, $randstring, $email));

		$message= "<html><body>
					<h2>StraboSpot</h2>
					Thanks for your interest in StraboSpot<br><br>
					Please click on the link below to confirm your user account.<br><br>

					<a href=\"https://www.strabospot.org/validate/$randstring\">https://www.strabospot.org/validate/$randstring</a><br><br>

					Thanks,<br><br>
					The StraboSpot Team
					<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
					</body></html>";

		$mail = new PHPMailer(true);
		$mail->isSMTP();
		$mail->SMTPDebug = 0;
		$mail->Debugoutput = 'html';
		$mail->Host = 'smtp.gmail.com';
		$mail->SMTPAuth = true;
		$mail->SMTPSecure= 'tls';
		$mail->Port = 587;
		$mail->Username = $straboemailaddress;
		$mail->Password = $straboemailpassword;
		$mail->From = $straboemailaddress;
		$mail->FromName = 'StraboSpot';
		$mail->addAddress($email);
		$mail->isHTML(true);
		$mail->CharSet = 'UTF-8';
		$mail->Encoding = 'base64';
		$mail->Subject = 'StraboSpot Account Validation';
		$body = $message;
		$mail->Body = $body;
		$mail->send();

		include("includes/mheader.php");

		?>

			<!-- Main -->
				<div id="main" class="wrapper style1">
					<div class="container">

						<header class="major">
							<h2>Register for Account</h2>
						</header>

		<h2 class="wsite-content-title">Success!</h2><br><br>
		A confirmation link has been emailed to <?php echo $email?>. Please allow a few minutes for this email to arrive.<br><br>
		Clicking on the link will activate your account.<br><br>
		Thanks,<br><br>
		The StraboSpot Team

						<div class="bottomSpacer"></div>

					</div>
				</div>

		<?php

		include("includes/mfooter.php");

		curl_setopt_array($ch = curl_init(), array(
		  CURLOPT_URL => "https://api.pushover.net/1/messages.json",
		  CURLOPT_POSTFIELDS => array(
			"token" => "$pushover_token",
			"user" => "$pushover_user",
			"message" => "New Strabo User: $firstname $lastname - $email",
			"title" => "New Strabo User",
		  ),
		  CURLOPT_SAFE_UPLOAD => true,
		  CURLOPT_RETURNTRANSFER => true,
		));
		curl_close($ch);

		exit();
	}

	$error="<div style=\"color:#FF0000;padding:10px;\">Error:<br>$error</div>";

}

include("includes/mheader.php");

?>
			<!-- Main -->
				<div id="main" class="wrapper style1">
					<div class="container">

						<header class="major">
							<h2>Register for Account</h2>
						</header>

						<?php echo $error?>
						<form method="POST">
								<div>First Name:</td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="firstname" value="<?php echo $firstname?>"></div>
								<div class="padTop">Last Name:</td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="lastname" value="<?php echo $lastname?>"></div>
								<div class="padTop">Email:</td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="email" value="<?php echo $email?>"></div>
								<div class="padTop">Password:</td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="password" name="password" value="<?php echo $password?>"></div>
								<div class="padTop">Confirm Password:</td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="password" name="passwordconfirm" value="<?php echo $passwordconfirm?>"></div>
								<div align="center" class="padTop"><div class="g-recaptcha" data-sitekey="6LdpswwUAAAAAFoitK0R2qztzQb8KT59pq1jUKEQ"></div></div></div>
								<div align="center"><input class="primary" type="submit" name="submit" value="Submit"></div>
						</form>

						<div class="bottomSpacer"></div>

					</div>
				</div>
<?php

include("includes/mfooter.php");

?>