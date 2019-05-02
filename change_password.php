<?

include("logincheck.php");

$userpkey = $_SESSION['userpkey'];

include("includes/config.inc.php");
include("db.php");

$count=$db->get_var("select count(*) from users where pkey=$userpkey");

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
		
		//check current password
		$count = $db->get_var("select count(*) from users where crypt('$currentpassword', password) = password and pkey=$userpkey");
		if($count==0){
			$error.=$errordelim."Incorrect current password provided.";$errordelim="<br>";
		}
		
		if($error==""){
			//update password
			$db->query("update users set password=crypt('$password', gen_salt('md5')) where pkey=$userpkey");

		
			include("includes/header.php");
			?>
		
				<h1>Success</h1><br><br>
				Password has been reset.
		
			<?
			include("includes/footer.php");
			exit();
		}
	}


	if($error!=""){
		$error="<div style=\"color:#FF0000;padding:10px;\">$error</div>";
	}

	include("includes/header.php");
	
	
	?>
	
		<h2>Password Reset:</h2><br>
		<?=$error?>
		<form method="POST">
		<table>
			<tr><td>Current Password</td><td><input type="password" name="currentpassword" value="<?=$currentpassword?>"></td></tr>
			<tr><td>New Password</td><td><input type="password" name="password" value="<?=$password?>"></td></tr>
			<tr><td>Confirm Password</td><td><input type="password" name="passwordconfirm" value="<?=$passwordconfirm?>"></td></tr>
			<tr><td colspan="2"><div align="center"><input type="submit" name="submit" value="Submit"></div></td></tr>
		</table>
		</form>
	<?
	
	
	
	include("includes/footer.php");





}











?>