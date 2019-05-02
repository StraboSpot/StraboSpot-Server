<?
include_once "./includes/config.inc.php";
include("db.php");

$chars=array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z","a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z","0","1","2","3","4","5","6","7","8","9");

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

	$firstname=$_POST['firstname'];
	$lastname=$_POST['lastname'];
	$email=strtolower(trim($_POST['email']));
	$password=$_POST['password'];
	$passwordconfirm=$_POST['passwordconfirm'];

	
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
		$mailcount=$db->get_var("select count(*) from users where email='$email'");
		if($mailcount > 0){
			$error.=$errordelim."Email Address ($email) is already registered at StraboSpot.";$errordelim="<br>";
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

	
	if($error==""){
	
		//no errors, so let's put in user and send out confirmation email
		
		$randstring="";
		for($x=0;$x<21;$x++){
			$randstring.=$chars[rand(0,61)];
		}
		
		$db->query("insert into users (firstname,lastname,password,hash,email) values 
										('$firstname',
										'$lastname',
										crypt('$password', gen_salt('md5')),
										'$randstring',
										'$email')
									");
	

		//Now send email
		require_once "Mail.php";
		
		$user = $email;
	
		$from     = "StraboSpot <strabospot@gmail.com>";
		$subject="StraboSpot Account Validation";
	
		$host     = "ssl://smtp.gmail.com";
		$port     = "465";
		$emailusername = "$straboemailaddress";  //<> give errors
		$password = "$straboemailpassword";
	
		$headers = array(
			'From'    => $from,
			'Subject' => $subject,
			'Content-Type' => "text/html; charset=iso-8859-1"
		);

		$message= "<html><body>
					<h2>StraboSpot</h2>
					Thanks for your interest in StraboSpot<br><br>
					Please click on the link below to confirm your user account.<br><br>
					
					<a href=\"https://www.strabospot.org/validate/$randstring\">https://www.strabospot.org/validate/$randstring</a><br><br>

					
					Thanks,<br><br>
					The StraboSpot Team
					<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
					</body></html>";
					
		$smtp = Mail::factory('smtp', array(
			'host'     => $host,
			'port'     => $port,
			'auth'     => true,
			'username' => $emailusername,
			'password' => $password
		));
	
		$to = $user;
		
		$headers['To']=$user;
		
		$mail = $smtp->send($to, $headers, $message);



		include("includes/header.php");
		
		
		?>
		
		<h2 class="wsite-content-title">Success!</h2><br><br>
		A confirmation link has been emailed to <?=$email?>. Please allow a few minutes for this email to arrive.<br><br>
		Clicking on the link will activate your account.<br><br>
		Thanks,<br><br>
		The StraboSpot Team

		<?

		include("includes/footer.php");

		curl_setopt_array($ch = curl_init(), array(
		  CURLOPT_URL => "https://api.pushover.net/1/messages.json",
		  CURLOPT_POSTFIELDS => array(
			"token" => "aom183fpsg4e9rkian3z63i4twa41m",
			"user" => "usyp3bd4ikh3qaotmg1um6qut8chgq",
			"message" => "New Strabo User: $firstname $lastname - $email",
			"title" => "New Strabo User",
		  ),
		  CURLOPT_SAFE_UPLOAD => true,
		  CURLOPT_RETURNTRANSFER => true,
		));
		curl_exec($ch);
		curl_close($ch);



		exit();
	}

	$error="<div style=\"color:#FF0000;padding:10px;\">Error:<br>$error</div>";

}


include("includes/header.php");


?>

<h2 class="wsite-content-title">Register for Account:</h2>
<?=$error?>
<form method="POST">
	<table>
		<tr><td>First Name:</td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="firstname" value="<?=$firstname?>"></td></tr>
		<tr><td>Last Name:</td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="lastname" value="<?=$lastname?>"></td></tr>
		<tr><td>email:</td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="email" value="<?=$email?>"></td></tr>
		<tr><td>Password:</td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="password" name="password" value="<?=$password?>"></td></tr>
		<tr><td>Confirm Password:</td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="password" name="passwordconfirm" value="<?=$passwordconfirm?>"></td></tr>
		<tr><td colspan="2"><div align="center"><div class="g-recaptcha" data-sitekey="6LdpswwUAAAAAFoitK0R2qztzQb8KT59pq1jUKEQ"></div></div></td></tr>
		<tr><td colspan="2"><div align="center"><input type="submit" name="submit" value="Submit"></div></td></tr>
		

	</table>
</form>









<?

include("includes/footer.php");


?>