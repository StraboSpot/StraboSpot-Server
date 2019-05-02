<?

if($_SERVER['REQUEST_METHOD']!="POST"){
	header("Bad Request", true, 400);
	$output['error'] = "Invalid Request Method.";
	header('Content-Type: application/json; charset=utf8');
	echo json_encode($output);
	exit();
}

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


$body = file_get_contents('php://input');

if(is_writable("test.txt")){
	if($label==""){$label="User Register";}
	file_put_contents ("test.txt", "\n\n$label\n\n $body \n\n", FILE_APPEND);
	file_put_contents ("test.txt", "\n\n************************************************************************************************************************\n\n", FILE_APPEND);
}

$jsonbody = json_decode($body);

include_once "./includes/config.inc.php";
include("db.php");


$firstname=$jsonbody->first_name;
$lastname=$jsonbody->last_name;
$email=trim(strtolower($jsonbody->email));
$password=$jsonbody->password;
$passwordconfirm=$jsonbody->confirm_password;


if($firstname==""){$errors[]="First Name cannot be blank.";}
if($lastname==""){$errors[]="Last Name cannot be blank.";}
if($email==""){$errors[]="Email cannot be blank.";}
if($password==""){$errors[]="Password cannot be blank.";}

if($email!=""){
	if(!check_email_address($email)){
		$errors[]="Invalid Email Address.";
	}
}

if($email!=""){
	$mailcount=$db->get_var("select count(*) from users where email='$email'");
	if($mailcount > 0){
		$errors[]="Email Address ($email) is already registered at StraboSpot.";
	}
}


if($password!=""){
	if($password!=$passwordconfirm){
		$errors[]="Passwords don't match.";
	}
}

if($password!=""){
	if(strlen($password)<8){
		$errors[]="Password too short. Passwords must be at least 8 characters long.";
	}
}

if($errors!=""){
	//$errors = json_encode($errors);
	$errors = implode(" ", $errors);
}




if($errors==""){

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
				
				<a href=\"http://www.strabospot.org/validate/$randstring\">https://www.strabospot.org/validate/$randstring</a><br><br>

				
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

	
	$message = "A confirmation link has been emailed to $email. Please allow a few minutes for this email to arrive. Clicking on the link will verify your account at which time you may login.";

	$output['valid']="true";
	$output['message']=$message;

	header('Content-Type: application/json; charset=utf8');
	echo json_encode($output);
	



}else{


	$output['valid']="false";
	$output['message']=$errors;

	header('Content-Type: application/json; charset=utf8');
	echo json_encode($output);


}



























































exit();
if($_SERVER['REQUEST_METHOD']!="POST"){
	header("Bad Request", true, 400);
	$output['error'] = "Invalid Request Method.";
	header('Content-Type: application/json; charset=utf8');
	echo json_encode($output);
	exit();
}

$body = file_get_contents('php://input');

$jsonbody = json_decode($body);

$email = strtolower($jsonbody->email);
$password = $jsonbody->password;

if($email == "" || $password == ""){
	header("Bad Request", true, 400);
	$output['error'] = "Email address and passord must be provided.";
	header('Content-Type: application/json; charset=utf8');
	echo json_encode($output);
	exit();
}

include_once "./includes/config.inc.php";
include("db.php");

$row = $db->get_row("select * from users where email='$email' and crypt('$password', password) = password;");

if($row->pkey != ""){
	$output['valid']="true";
	if($row->profileimage != ""){
		$output['profileimage']="http://strabospot.org/db/profileimage";
	}
}else{
	$output['valid']="false";
}

header('Content-Type: application/json; charset=utf8');
echo json_encode($output);
return true;

?>