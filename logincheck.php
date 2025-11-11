<?php
/**
 * File: logincheck.php
 * Description: User login and authentication
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

include_once("adminkeys.php");

session_start();

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 7200)) { //1800
	// last request was more than 30 minutes ago
	$_SESSION['loggedin']="no";
}
$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp

if($_SESSION['loggedin']!="yes"){
	if (strpos($_SERVER['REQUEST_URI'], 'update_token') == false) {
		$_SESSION['uri']=$_SERVER['REQUEST_URI'];
	}
	header("Location:/login.php");
	exit();
}

?>