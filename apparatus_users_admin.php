<?php
/**
 * File: apparatus_users_admin.php
 * Description: Apparatus Admin
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

include("logincheck.php");

$userpkey = $_SESSION['userpkey'];

include("prepare_connections.php");

$credentials = $_SESSION['credentials'];

if($userpkey != 3) exit();

include('includes/header.php');

?>

<h2 class="wsite-content-title">Apparatus Admin</h2>
<div style="padding-left:20px;">
	<h3 class="wsite-content-title">Experimental:</h3>
	<div style="padding-left:20px;">
		<a href="experimental/apparatus_repository">Repository</a><br>
		<a href="experimental/addFacilityForUser">Add Facility to Repository</a><br>
		<a href="experimental/addUserToFacility">Add User to Facility</a><br>
	</div>
</div>

<div style="padding-left:20px;">
	<h3 class="wsite-content-title">Micro:</h3>
	<div style="padding-left:20px;">
		<a href="instrumentcatalog">Instrument Catalog</a><br>
		<a href="add_institute">Add Institute</a><br>
		<a href="add_institute_user">Add User to Institute</a><br>
	</div>
</div>

<?php
include('includes/footer.php');
?>