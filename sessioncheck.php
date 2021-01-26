<?

/*
******************************************************

This script checks session time and destroys it if it
is greater than 30 minutes. This prevents the main
page from showing logged in when a timeout has occurred.

Author: Jason Ash (jasonash@ku.edu)
Date: 02/03/2017

******************************************************
*/
include_once("adminkeys.php");
session_start();

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
    // last request was more than 30 minutes ago
    session_unset();     // unset $_SESSION variable for the run-time 
    session_destroy();   // destroy session data in storage
}
$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp

?>