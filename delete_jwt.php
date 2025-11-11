<?php
/**
 * File: delete_jwt.php
 * Description: Deletes records from jwts table(s)
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


include("logincheck.php");

$jwtuuid = $_GET['u'] ?? '';
$jwtuuid = preg_replace('/[^a-zA-Z0-9\-]/', '', $jwtuuid);
$userpkey = $_SESSION['userpkey'] ?? 0;

include("includes/config.inc.php");
include("db.php");

$row = $db->get_row_prepared("SELECT * FROM jwts WHERE uuid=$1 AND user_pkey=$2", array($jwtuuid, $userpkey));

if($row->pkey!=""){
	$db->prepare_query("DELETE FROM jwts WHERE uuid=$1 AND user_pkey=$2", array($jwtuuid, $userpkey));
	header("Location:/my_jwts");
}

?>