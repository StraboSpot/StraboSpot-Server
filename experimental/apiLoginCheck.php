<?php
/**
 * File: apiLoginCheck.php
 * Description: User login and authentication
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

session_start();

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 7200)) { //1800
	$_SESSION['loggedin']="no";
}

$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp

if($_SESSION['loggedin']!="yes"){
	$error = new stdClass();
	$error->Error = "Login Timed Out.\nPlease login again.";
	header('Content-type: application/json');
	echo json_encode($error, JSON_PRETTY_PRINT);
	exit();
}
?>