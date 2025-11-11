<?php
/**
 * File: resendlink.php
 * Description: Resends account validation email link
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

session_start();

// Import PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;// Load Composer's autoloader

require 'includes/PHPMailer/PHPMailer/src/Exception.php';
require 'includes/PHPMailer/PHPMailer/src/PHPMailer.php';
require 'includes/PHPMailer/PHPMailer/src/SMTP.php';

include_once "./includes/config.inc.php";
include("db.php");
include("neodb.php");

	if($_POST['submit_resend_vlink']!=""){

		$email = strtolower(trim($_POST['email']));

		// Validate email format
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$error = "Invalid email format";
			$myrow = null;
		} else {
			$myrow = $db->get_row_prepared("SELECT * FROM users WHERE email=$1 LIMIT 1", array($email));
		}

		if($db->num_rows > 0){
			//send email here

			$hash = $myrow->hash;

			$message= "<html><body>
					<h2>StraboSpot</h2>
					Thanks for your interest in StraboSpot<br><br>
					Please click on the link below to confirm your user account.<br><br>

					<a href=\"https://www.strabospot.org/validate/$hash\">https://www.strabospot.org/validate/$hash</a><br><br>

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
			$mail->Subject = 'StraboSpot Account Validation Link';
			$body = $message;
			$mail->Body = $body;
			$mail->send();

			$error="Email sent. Please use link sent to validate account.";

		}else{

			$error="Email address not found.";

		}

	}

include("includes/mheader.php");

?>

			<!-- Main -->
				<div id="main" class="wrapper style1">
					<div class="container">

						<header class="major">
							<h2>Resend Verification Link</h2>
						</header>

<?php

if(error!=""){
?>
<div style="color:#FF2A00;"><?php echo $error?></div>
<?php
}

?>

  <form method="POST">

	<p>Email Address: <input type="text" name="email" ></p>
	<p><input class="primary" type="submit" name="submit_resend_vlink" id="submit_resend_vlink" value="Resend Verification Link"></p>
	<input type="hidden" name="script" value="#session.script#">

  </form>

					<div class="bottomSpacer"></div>

					</div>
				</div>

<?php
include("includes/mfooter.php");
?>
