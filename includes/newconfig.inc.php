<?php
/**
 * File: newconfig.inc.php
 * Description: This file should be renamed to config.inc.php and
 *              populated with app-specific secrets below.
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

$neousername = "myneo4jusername"; 			//Neo4j username
$neopassword = "myneo4jpassword"; 			//Neo4j password
$neohost = "neo4jhostname"; 				//Neo4j host
$neoport = 7687; 							//Neo4j port
$neomode = "bolt"; 							//Neo4j connection mode
$dbusername = "mydbusername"; 				//Postgres username
$dbpassword = "mydbpassword"; 				//Postgres password
$dbname = "mydbname"; 						//Postgres database name
$dbhost = "mydbhost"; 						//Postgres database host
$straboemailaddress = "myemail"; 			//Gmail address
$straboemailpassword = "myemailpassword" 	//Gmail password
$mailchimpAPIkey = "mailchimpapikey"; 		//For maintaining mailchimp mailing list
$captchasecret="googlecaptchakey"; 			//Google captcha key
$jwtsecret = "jwtsigningkey"; 				//JWT signing key
$pushover_token = "pushovertoken"; 			//For alerting about new user registrations
$pushover_user = "pushoveruser"; 			//Pushover user token
$vloc="/var/www/versions"; 					//location to store versions

?>
