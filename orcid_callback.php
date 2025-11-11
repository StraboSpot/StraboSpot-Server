<?php
/**
 * File: orcid_callback.php
 * Description: Handles orcid callback operations
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

include("prepare_connections.php");

function isJson($string) {
   json_decode($string);
   return json_last_error() === JSON_ERROR_NONE;
}

$origcreds = $_GET['creds'];
$creds = $_GET['creds'];
$code = $_GET['code'];

if($creds == ""){
	$message = "No credentials provided.";
}elseif($code == ""){
	$message = "No code found in URL.";
}else{

	//Check creds
	$creds = base64_decode($creds);
	$creds = explode(":", $creds);
	$email = strtolower(trim($creds[0] ?? ''));
	$password = $creds[1] ?? '';

	// Validate email format
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$message = "Invalid email format.";
	}elseif($password == ""){
		$message = "Password not provided.";
	}else{
		$pkey = $db->get_var_prepared("SELECT pkey FROM users WHERE email = $1 AND crypt($2, password) = password", array($email, $password));

		//Add token option here
		if($pkey == ""){
			$email = $db->get_var_prepared("SELECT email FROM apptokens WHERE uuid=$1", array($password));
			if($email != ""){
				$pkey = $db->get_var_prepared("SELECT pkey FROM users WHERE email = $1", array($email));
			}
		}

		if($pkey == ""){
			$message = "Invalid username/password.";
		}else{
			$pkey = (int)$pkey;

			//use curl to get id_token - SECURE: Using escapeshellarg to prevent command injection
			exec("curl -H 'Accept: application/json' --data " . escapeshellarg("code=$code&client_id=APP-YW6QNFNBJZQERER4&client_secret=ed8d9c53-db53-4cab-8d46-0435035d793f&grant_type=authorization_code&redirect_uri=https://www.strabospot.org/orcid_callback?creds=$origcreds") . " https://orcid.org/oauth/token", $results);
			$results = $results[0] ?? '';

			if(!isJson($results)){
				$message = "Invalid JSON returned from Orcid.";
			}else{

				$jsonresults = $results;
				$results = json_decode($results);
				$id_token = $results->id_token ?? '';

				$db->prepare_query("UPDATE users SET orcid_token = $1 WHERE pkey = $2", array($id_token, $pkey));

				$message = "Successfully signed into ORCID.";

			}
		}
	}
}
?>
 <!DOCTYPE html>
<html>
<head>
</head>
<body>
	<div style="padding-top:50px;text-align:center;width:100%;">
		<div style=""><img src="/includes/images/strabo_front_page_banner.jpg" width="400px;"></div>
		<div id="eventMessage" style="font-size:1.5em;padding-top:50px;"><?php echo $message?></div>
		<div id="" style="font-size:1.5em;padding-top:50px;">
			<a href="strabofield://orcid_id/<?php echo $id_token?>" onClick="window.close();">Back to StraboField</a>
		</div>
	</div>
	<div style="padding-top:50px;"><pre><?php echo $jsonresults?></pre></div>
</body>
</html>