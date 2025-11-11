<?php
/**
 * File: validateuser.php
 * Description: Error!
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

include_once "./includes/config.inc.php";
include("db.php");
include("neodb.php");

$hash = $_GET['hash'] ?? '';
$hash = preg_replace('/[^a-zA-Z0-9]/', '', $hash);
if($hash==""){exit("error");}

$row=$db->get_row_prepared("SELECT * FROM users WHERE hash=$1", array($hash));

if($row->pkey==""){
	exit("error");
}else{
	if($row->active=="t"){
		include("includes/mheader.php");
		?>
			<!-- Main -->
				<div id="main" class="wrapper style1">
					<div class="container">

						<header class="major">
							<h2>Error!</h2>
						</header>
		Your account has already been activated.<br>
		Please use the login link below to login to your account.<br><br>
		<div style="padding-left:150px;"><a href="/login">Login</a></div><br>
		Thanks,<br><br>
		The StraboSpot Team
						<div class="bottomSpacer"></div>

					</div>
				</div>
		<?php
		include("includes/mfooter.php");
		exit();
	}
}

$db->prepare_query("UPDATE users SET active = true WHERE hash=$1", array($hash));

//also insert node
$userpkey = (int)$row->pkey;
$firstname = $row->firstname;
$lastname = $row->lastname;
$email = $row->email;

$injson["userpkey"]=$userpkey;
$injson["firstname"]=$firstname;
$injson["lastname"]=$lastname;
$injson["email"]=$email;

$jsoninjson = json_encode($injson);

$count = $neodb->get_var("match (u:User) where u.userpkey=$userpkey return count(u)");

if($count==0){
	$neodb->createNode($jsoninjson,"User");
}

//Add user to MailChimp:
exec('curl -X POST \'https://us8.api.mailchimp.com/3.0/lists/693e23d78d/members?skip_merge_validation=false\' --user "key:'.$mailchimpAPIkey.'" -d \'{"email_address":"'.$email.'","email_type":"html","status":"subscribed","merge_fields":{"FNAME": "'.$firstname.'", "LNAME": "'.$lastname.'", "BIRTHDAY": "", "ADDRESS": { "addr1": "", "city": "", "state": "", "zip": ""}}}\' > /dev/null 2>&1 &', $out);

include("includes/mheader.php");
?>
			<!-- Main -->
				<div id="main" class="wrapper style1">
					<div class="container">

						<header class="major">
							<h2>Account Activated!</h2>
						</header>
Congratulations! Your account has been activated.<br><br>
Please use the login link below to login to your account.<br><br>
<div style="padding-left:150px;"><a href="/login">Login</a></div><br>
Thanks,<br><br>
The StraboSpot Team
						<div class="bottomSpacer"></div>

					</div>
				</div>
<?php
include("includes/mfooter.php");
?>