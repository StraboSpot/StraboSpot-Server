<?
session_start();

include_once "./includes/config.inc.php";
include("db.php");
include("neodb.php");


	if($_POST['submit_forgot_password']!=""){
	
		$email=strtolower($_POST['email']);
	
		$myrow=$db->get_row("select * from users where email='$email' limit 1");
		
		if($db->num_rows > 0){
			//send email here
			
			$hash = $myrow->hash;
			
			require_once "Mail.php";
		
			$user = $email;
	
			$from     = "StraboSpot <$straboemailaddress>";
			$subject="StraboSpot Password Reset";
	
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
						This is a StraboSpot password reset request.<br><br>
						Please click on the link below to reset your password.<br><br>
					
						<a href=\"https://www.strabospot.org/passwdreset/$hash\">https://www.strabospot.org/passwdreset/$hash</a><br><br>

					
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


			$error="Email sent. Please use link sent to reset password.";
			
		}else{
		
			$error="Email address not found.";
			
		}	
	
	}




include("includes/header.php");

if(error!=""){
?>
<div style="color:#FF2A00;"><?=$error?></div>
<?
}

?>
  
  
  



  <form method="POST">
    <table>

		  <tr><td colspan=2>
          <h2 class="wsite-content-title">Forgot password?</h2></td></tr>
		  <tr><td align="right">E-mail address</td><td><input type="text" name="email" value=""></td></tr>
		  <tr><td colspan=2 align="right"><input type="submit" name="submit_forgot_password" id="submit_forgot_password" value="Reset My Password"></td>
      </tr>
    </table>
    <input type="hidden" name="script" value="#session.script#">
  </form>
  <p />
  <!--<b>dev username/password: test/test123</b> </cfoutput>-->
<?
include("includes/footer.php");
?>
