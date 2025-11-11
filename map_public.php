<?php
/**
 * File: map_public.php
 * Description: Public map viewing interface
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

include("logincheck.php");

include("prepare_connections.php");

$hash = $_GET['maphash'];

if($_GET['state']=='public'){
	$db->query("update geotiffs set ispublic = TRUE where hash='$hash' and userpkey=$userpkey");
}

if($_GET['state']=='private'){
	$db->query("update geotiffs set ispublic = FALSE where hash='$hash' and userpkey=$userpkey");
}

?>