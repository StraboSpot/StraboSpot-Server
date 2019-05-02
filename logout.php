<?
session_start();

session_destroy();

//unset($_SESSION);
//$_SESSION['username']="";
//$_SESSION['loggedin']="";
//$_SESSION['userpkey']="";
header("Location:index.php");
?>