<?php
/**
 * File: passwordreset.php
 * Description: Password reset form handler and email sender
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

include("./includes/config.inc.php");
include("db.php");

$hash = $_GET['hash'] ?? $_POST['hash'] ?? '';
// Only allow alphanumeric characters in hash
$hash = preg_replace('/[^a-zA-Z0-9]/', '', $hash);
if($hash==""){exit();}

$count=$db->get_var_prepared("SELECT count(*) FROM users WHERE hash=$1", array($hash));

if($count > 0){

	if($_POST['submit']!=""){
		//check passwords
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

		if($error==""){
			//update password - SECURE: Using prepared statement
			$db->prepare_query("UPDATE users SET password=crypt($1, gen_salt('md5')) WHERE hash=$2", array($password, $hash));

			include("includes/header.php");
			?>

				<h1>Success</h1><br><br>
				Password has been reset.

			<?php
			include("includes/footer.php");
			exit();
		}
	}

	if($error!=""){
		$error="<div style=\"color:#FF0000;padding:10px;\">$error</div>";
	}

	include("includes/header.php");

	?>

		<h1>Password Reset</h1><br>
		<?php echo $error?>
		<form method="POST">
		<table>
			<tr><td>New Password: </td><td><input type="password" name="password" value="<?php echo $password?>"></td></tr>
			<tr><td>Confirm New Password: </td><td><input type="password" name="passwordconfirm" value="<?php echo $passwordconfirm?>"></td></tr>
			<tr><td colspan="2"><div align="center"><input type="submit" name="submit" value="Submit"></div></td></tr>
		</table>
		<input type="hidden" name="hash" value="<?php echo $hash?>">
		</form>
	<?php

	include("includes/footer.php");

}

?>