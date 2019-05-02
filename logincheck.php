<?
session_start();

//print_r($_SERVER);exit();

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) { //1800
    // last request was more than 30 minutes ago
    //session_unset();     // unset $_SESSION variable for the run-time 
    //session_destroy();   // destroy session data in storage
    $_SESSION['loggedin']="no";
}
$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp

if($_SESSION['loggedin']!="yes"){
	$_SESSION['uri']=$_SERVER['REQUEST_URI'];
	header("Location:/login.php");
	exit();
}






/*
$scriptstring=$_SERVER['SCRIPT_NAME'];
$scriptparts=explode("/",$scriptstring);
$scriptlength=count($scriptparts);
$thispage=$scriptparts[$scriptlength-1];
if($thispage!="login.php"&&$thispage!="logout.php"&&$thispage!="results.php"){
	$_SESSION['script']=$thispage;
}else{
	$_SESSION['script']="index.php";
}

if($_SESSION['loggedin']!="yes"){
	header("Location:login.php");
	exit();
}
*/
?>