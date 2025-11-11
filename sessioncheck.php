<?php
/**
 * File: sessioncheck.php
 * Description: Handles sessioncheck operations
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


include_once("adminkeys.php");
session_start();

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 7200)) {
	// last request was more than 30 minutes ago
	session_unset();     // unset $_SESSION variable for the run-time
	session_destroy();   // destroy session data in storage
}
$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp

?>