<?php
/**
 * File: change_password.php
 * Description: Password change form for logged-in users
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


include("logincheck.php");

$userpkey = (int)$_SESSION['userpkey'];

include("includes/config.inc.php");
include("db.php");

$count=$db->get_var_prepared("SELECT count(*) FROM users WHERE pkey=$1", array($userpkey));

if($count > 0){

	if($_POST['submit']!=""){
		//check passwords
		$currentpassword=$_POST['currentpassword'];
		$password=$_POST['password'];
		$passwordconfirm=$_POST['passwordconfirm'];

		if($password==""){
			$error.=$errordelim."Password cannot be blank.";$errordelim="<br>";
		}

		if($password!=""){
			if($password != $passwordconfirm){
				$error.=$errordelim."Passwords do not match.";$errordelim="<br>";
			}
		}

		//check current password - SECURE: Using prepared statement
		$count = $db->get_var_prepared("SELECT count(*) FROM users WHERE crypt($1, password) = password AND pkey=$2", array($currentpassword, $userpkey));
		if($count==0){
			$error.=$errordelim."Incorrect current password provided.";$errordelim="<br>";
		}

		if($error==""){
			//update password - SECURE: Using prepared statement
			$db->prepare_query("UPDATE users SET password=crypt($1, gen_salt('md5')) WHERE pkey=$2", array($password, $userpkey));

			include("includes/mheader.php");
			?>
			<!-- Main -->
				<div id="main" class="wrapper style1">
					<div class="container">

						<header class="major">
							<h2>Success!</h2>
						</header>
				Password has been reset.
					<div class="bottomSpacer"></div>

					</div>
				</div>
			<?php
			include("includes/mfooter.php");
			exit();
		}
	}

	if($error!=""){
		$error="<div style=\"color:#e44c65;padding:10px;\">$error</div>";
	}

	include("includes/mheader.php");

	?>
			<!-- Main -->
				<div id="main" class="wrapper style1">
					<div class="container">

						<header class="major">
							<h2>Password Reset</h2>
						</header>
		<?php echo $error?>
		<form method="POST">
			<div class="row gtr-uniform gtr-50">
			<div class="col-12"><h3>Current Password:</h3></div>
			<div class="col-12"><input type="password" name="currentpassword" value="<?php echo $currentpassword?>"></div>
			<div class="col-12"><h3>New Password:</h3></div>
			<div class="col-12"><input type="password" name="password" value="<?php echo $password?>"></div>
			<div class="col-12"><h3>Confirm Password:</h3></div>
			<div class="col-12"><input type="password" name="passwordconfirm" value="<?php echo $passwordconfirm?>"></div>
			<div class="col-12"><input class="primary" type="submit" name="submit" value="Submit"></div>
			</div>
		</form>

					<div class="bottomSpacer"></div>

					</div>
				</div>

	<?php

	include("includes/mfooter.php");

}

?>