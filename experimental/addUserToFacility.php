<?php
/**
 * File: addUserToFacility.php
 * Description: Creates new facility records
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

include("../logincheck.php");

$userpkey = $_SESSION['userpkey'];

include("../prepare_connections.php");

$credentials = $_SESSION['credentials'];

if($userpkey != 3) exit();

include('../includes/header.php');


if($_POST['submit'] != ""){

	$facilityPkey = isset($_POST['facilityPkey']) ? (int)$_POST['facilityPkey'] : 0;
	if($facilityPkey == 0) exit("No facility chosen.");

	$email = isset($_POST['email']) ? trim($_POST['email']) : '';
	if(!filter_var($email, FILTER_VALIDATE_EMAIL)) exit("Invalid email address.");

	$userpkey = $db->get_var_prepared("SELECT pkey FROM users WHERE email = $1", array($email));

	if($userpkey == "") exit("User not found.");

	$db->prepare_query("INSERT INTO apprepo.facility_users (users_pkey, facility_pkey) VALUES ($1, $2)", array($userpkey, $facilityPkey));

	echo "User $email added to $facilityPkey<br><br>";
	?>
	<a href="/apparatus_users_admin">Continue</a>
	<?php

	exit();
}


$facilityRows = $db->get_results("select * from apprepo.facility order by institute")


?>
<form method="POST">
Add User to Facility:<br><br>
<select name="facilityPkey">
	<option value="">select...</option>
	<?php
	foreach($facilityRows as $row){
		?>
		<option value="<?php echo $row->pkey?>"><?php echo $row->institute?> - <?php echo $row->name?></option>
		<?php
	}
	?>
</select><br><br>
Email: <input type="text" name="email"><br><br>
<input type="submit" name="submit" value="Submit">
</form>

<?php
include('../includes/footer.php');
?>