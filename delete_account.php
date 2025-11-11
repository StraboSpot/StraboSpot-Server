<?php
/**
 * File: delete_account.php
 * Description: Record deletion handler
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


set_time_limit(300);
include("logincheck.php");
include("includes/datamodel.php");
include("prepare_connections.php");

include 'includes/mheader.php';

?>

			<!-- Main -->
				<div id="main" class="wrapper style1">
					<div class="container">

						<header class="major">
							<h2>Delete Account</h2>
						</header>

<style type='text/css'>
	.warningHeader {
		font-size:32px;
		font-weight:bold;
		color:#e44c65;
	}
	.warningMessage {
		font-size:16px;
		font-weight:bold;
		color:#e44c65;
		padding-bottom:20px;
	}
	.submitButton {
		width:130px;
		height:30px;
	}
	.errorMessage {
		font-size:16px;
		font-weight:bold;
		color:#e44c65;
		padding-top:20px;
		padding-bottom:40px;
		padding-left:20px;
	}
	.successMessage {
		font-size:16px;
		font-weight:bold;
		color:#0a8006;
		padding-top:20px;
		padding-bottom:10px;
		padding-left:20px;
	}
</style>

<?php

if($_POST['accountsubmit'] != ""){
	$email = strtolower(trim($_POST['email']));
	$password = $_POST['password'];

	// Validate email format
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$error = "Invalid email format";
		$row = null;
	} else {
		$row = $db->get_row_prepared("SELECT * FROM users WHERE email=$1 AND crypt($2, password) = password AND active = TRUE LIMIT 1", array($email, $password));
	}

	if(!$row || $row->pkey == ""){
		$error = "Invalid email/password provided.";
	}else{

		$pkey = (int)$row->pkey;
		$db->prepare_query("UPDATE users SET deleted = true WHERE pkey = $1", array($pkey));

		//Delete user from MailChimp
		exec('curl -X DELETE https://us8.api.mailchimp.com/3.0/lists/693e23d78d/members/'.$email.' --user "key:'.$mailchimpAPIkey.'" > /dev/null 2>&1 &', $out);

		//Delete Account Here
		?>

			<h2>Delete Account</h2>

			<div class="successMessage">
			Success. Account deleted.
			</div>

			<div class="successMessage">
				<span id="countdown"></span>
			</div>

<script type="text/javascript">
	var timeleft = 3;
	var downloadTimer = setInterval(function(){
	  if(timeleft <= 0){
		clearInterval(downloadTimer);
		document.getElementById("countdown").innerHTML = "";
		window.location.href = '/';
	  } else {
		document.getElementById("countdown").innerHTML = timeleft + " Please Wait.";
	  }
	  timeleft -= 1;
	}, 1000);
</script>

					<div class="bottomSpacer"></div>

					</div>
				</div>

		<?php

		session_destroy();

		include 'includes/mfooter.php';

		exit();
	}

}

if($error!=""){
	$error = "<div class=\"errorMessage\">$error</div>";
}
?>

	<script type="text/javascript">

	function formvalidate(){

		console.log("checking form here...");

		var errors='';

		var email = document.getElementById("email").value;
		var password = document.getElementById("password").value;

		if(email == "" || password == ""){errors += 'email and Password must be provided.\n';}

		if(errors!="" && errors!=null){
			alert(errors);
			return false;
		}else{
			return confirm('Do you really want to delete your account?\nThis cannot be undone!!!');
		}
	}

	</script>

	<div class="warningHeader">Warning!!</div>
	<div class="warningMessage">
	This interface will permanently delete your account and all StraboSpot data!<br>
	Deleting your account is permanent and cannot be reversed!
	Please proceed with caution!
	</div>

	<?php echo $error?>

	To permanently delete your account, please enter your email and password below:
	<form name="uploadform" method="POST" onsubmit="return formvalidate();" enctype="multipart/form-data">

		<p>Email: <input type="text" name="email" id="email" ></p>
		<p>Password: <input type="password" name="password" id="password"></p>
		<p><input class="primary" type="submit" name="accountsubmit" value="Delete Account"></p>

	</form>

					<div class="bottomSpacer"></div>

					</div>
				</div>

<?php
include 'includes/mfooter.php';
?>
