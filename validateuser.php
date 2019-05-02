<?
include_once "./includes/config.inc.php";
include("db.php");
include("neodb.php");

$hash = $_GET['hash'];

$row=$db->get_row("select * from users where hash='$hash'");

//$db->dumpVar($row);

if($row->pkey==""){
	exit();
}else{
	if($row->active=="t"){
		include("includes/header.php");
		?>
		<h1>Error!</h1><br>
		Your account has already been activated.<br>
		Please use the login link below to login to your account.<br><br>
		<div style="padding-left:150px;"><a href="/login">Login</a></div><br>
		Thanks,<br><br>
		The StraboSpot Team
		<?
		include("includes/footer.php");
		exit();
	}
}

$db->query("update users set active = true where hash='$hash'");

//also insert node
$userpkey = (int)$row->pkey;
$firstname = $row->firstname;
$lastname = $row->lastname;
$email=$row->email;

$injson["userpkey"]=$userpkey;
$injson["firstname"]=$firstname;
$injson["lastname"]=$lastname;
$injson["email"]=$email;

$jsoninjson = json_encode($injson);


$count = $neodb->get_var("match (u:User) where u.userpkey=$userpkey return count(u)");

if($count==0){
	$neodb->createNode($jsoninjson,"User");
}

include("includes/header.php");
?>
<h1>Account Activated</h1><br>
Congratulations! Your account has been activated.<br><br>
Please use the login link below to login to your account.<br><br>
<div style="padding-left:150px;"><a href="/login">Login</a></div><br>
Thanks,<br><br>
The StraboSpot Team
<?
include("includes/footer.php");
?>