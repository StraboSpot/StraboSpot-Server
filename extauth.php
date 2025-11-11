#!/usr/bin/php

/**
 * File: extauth.php
 * Description: Handles External Authentication for Apache
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

// External Authenticator for Apache

include_once "/srv/app/www/includes/config.inc.php";
include("/srv/app/www/db.php");

$prog = $argv[0];
$user = trim(fgets(STDIN));
$pass = trim(fgets(STDIN));

if($user == "" || $pass == ""){
	exit(1);
}

if(!filter_var($user, FILTER_VALIDATE_EMAIL)){
	exit(1);
}

$count = $db->get_var_prepared("SELECT count(*) FROM users WHERE email=$1 AND deleted = FALSE", array($user));
if($count > 0){
	if(md5($pass)==$hashval) exit(0);
}

$rows=$db->get_row_prepared("SELECT * FROM users WHERE email=$1 AND crypt($2, password) = password AND active = TRUE AND deleted = FALSE", array($user, $pass));
if($db->num_rows>0) exit(0);

//Also check in apptokens table, but delete first
$db->query("DELETE FROM apptokens WHERE created_on < NOW() - INTERVAL '2 hours'");

$rows=$db->get_row_prepared("SELECT * FROM apptokens, users WHERE users.email = apptokens.email AND apptokens.email=$1 AND apptokens.uuid = $2 AND users.deleted = FALSE", array($user, $pass));
if($db->num_rows>0){
	$db->prepare_query("UPDATE apptokens SET created_on = now() WHERE email=$1 AND uuid = $2", array($user, $pass));
	exit(0);
}

exit(1);

