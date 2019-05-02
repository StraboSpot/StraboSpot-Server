<?
include("./includes/config.inc.php");
include("db.php");

$hash=$_GET['hash'];
if($hash==""){$hash=$_POST['hash'];}
if($hash==""){exit();}

$count=$db->get_var("select count(*) from users where hash='$hash'");

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
			//update password
			$db->query("update users set password=crypt('$password', gen_salt('md5')) where hash='$hash'");

		
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
	
		<h1>Password Reset</h1><br>
		<?=$error?>
		<form method="POST">
		<table>
			<tr><td>Password</td><td><input type="password" name="password" value="<?=$password?>"></td></tr>
			<tr><td>Confirm Password</td><td><input type="password" name="passwordconfirm" value="<?=$passwordconfirm?>"></td></tr>
			<tr><td colspan="2"><div align="center"><input type="submit" name="submit" value="Submit"></div></td></tr>
		</table>
		<input type="hidden" name="hash" value="<?=$hash?>">
		</form>
	<?
	
	
	
	include("includes/footer.php");





}











?>