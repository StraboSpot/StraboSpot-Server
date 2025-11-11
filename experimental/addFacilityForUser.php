<?php
/**
 * File: addFacilityForUser.php
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

	$labname = isset($_POST['labname']) ? trim($_POST['labname']) : '';
	$instituteName = isset($_POST['instituteName']) ? trim($_POST['instituteName']) : '';

	$email = isset($_POST['email']) ? trim($_POST['email']) : '';
	if(!filter_var($email, FILTER_VALIDATE_EMAIL)) exit("Invalid email address.");

	$userpkey = $db->get_var_prepared("SELECT pkey FROM users WHERE email = $1", array($email));

	if($userpkey == "") exit("User not found.");

	$unixtime = time();

	$facpkey = $db->get_var("SELECT nextval('apprepo.facility_pkey_seq')");

	//Insert into apprepo.facility
	$db->prepare_query("
		INSERT INTO apprepo.facility
			(
				pkey,
				userpkey,
				created_timestamp,
				modified_timestamp,
				institute,
				name
			)
				VALUES
			(
				$1,
				3,
				$2,
				$3,
				$4,
				$5
			)
	", array($facpkey, $unixtime, $unixtime, $instituteName, $labname));

	$db->prepare_query("INSERT INTO apprepo.facility_users (users_pkey, facility_pkey) VALUES ($1, $2)", array($userpkey, $facpkey));

	echo "Institute/Lab Added<br><br>";

	?>
	<a href="/apparatus_users_admin">Continue</a>
	<?php

	exit();
}




?>
<form method="POST">
Add Facility for User:<br><br>
Lab Name: <input type="text" name="labname"><br><br>
Institute Name: <input type="text" name="instituteName"><br><br>
Email: <input type="text" name="email"><br><br>
<input type="submit" name="submit" value="Submit">
</form>

<?php
include('../includes/footer.php');
?>