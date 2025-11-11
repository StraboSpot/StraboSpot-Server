<?php
/**
 * File: add_institute_user.php
 * Description: Adds new records to instrument_users table(s)
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

include("logincheck.php");

$userpkey = (int)$_SESSION['userpkey'];

include("prepare_connections.php");

$credentials = $_SESSION['credentials'];

if($userpkey != 3) exit();

include('includes/header.php');

if($_POST['submit'] != ""){

	$institutionPkey = $_POST['institutionPkey'] ?? '';
	if(!is_numeric($institutionPkey) || $institutionPkey == "") exit("No institute chosen.");
	$institutionPkey = (int)$institutionPkey;

	$email = strtolower(trim($_POST['email'] ?? ''));
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		exit("Invalid email format.");
	}

	$userpkey = $db->get_var_prepared("SELECT pkey FROM users WHERE email=$1", array($email));

	if($userpkey == "") exit("User not found.");

	$db->prepare_query("INSERT INTO instrument_users (users_pkey, institution_pkey) VALUES ($1, $2)", array($userpkey, $institutionPkey));

	echo "User $email added to $facilityPkey<br><br>";
	?>
	<a href="/apparatus_users_admin">Continue</a>
	<?php

	exit();
}

$institutionRows = $db->get_results("select * from institute order by institute_name")

?>
<form method="POST">
Add User to Institute:<br><br>
<select name="institutionPkey">
	<option value="">select...</option>
	<?php
	foreach($institutionRows as $row){
		?>
		<option value="<?php echo $row->pkey?>"><?php echo $row->institute_name?></option>
		<?php
	}
	?>
</select><br><br>
Email: <input type="text" name="email"><br><br>
<input type="submit" name="submit" value="Submit">
</form>

<?php
include('includes/footer.php');
?>