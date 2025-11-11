<?php
/**
 * File: delete_instrument.php
 * Description: Deletes records from instrument table(s)
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

include("logincheck.php");

$userpkey = $_SESSION['userpkey'];

$instrument_pkey = isset($_GET['ii']) ? (int)$_GET['ii'] : 0;
if($instrument_pkey == 0){
	exit();
}

include("prepare_connections.php");

$credentials = $_SESSION['credentials'];

$instcount = $db->get_var_prepared("
	SELECT count(*) FROM instrument_users WHERE users_pkey = $1
", array($userpkey));

$admin_pkeys = array(3,9,500);

if(!in_array($userpkey, $admin_pkeys) && $instcount == 0){
	exit();
}

if(in_array($userpkey, $admin_pkeys)){
	$db->prepare_query("
		DELETE FROM instrument WHERE pkey = $1
	", array($instrument_pkey));
}else{
	$institutionrow = $db->get_row_prepared("
		SELECT ii.*
		FROM
		instrument i,
		instrument_users iu,
		institute ii
		WHERE
		ii.pkey = i.institution_pkey
		AND iu.institution_pkey = ii.pkey
		AND iu.users_pkey = $1
		AND i.pkey = $2
	", array($userpkey, $instrument_pkey));

	if($institutionrow->pkey != ""){
		$db->prepare_query("DELETE FROM instrument WHERE pkey = $1", array($instrument_pkey));
	}

}

header("Location: instrumentcatalog");
?>