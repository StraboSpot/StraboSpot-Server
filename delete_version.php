<?php
/**
 * File: delete_version.php
 * Description: Deletes records from versions table(s)
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

include("logincheck.php");
include("prepare_connections.php");

$myuuid = $_GET['uuid'];

$count = $db->get_var("select count(*) from versions where userpkey=$userpkey and uuid='$myuuid'");

if($count > 0){
	unlink("versions/$myuuid");
	$db->query("delete from versions where userpkey=$userpkey and uuid='$myuuid'");
}

header("Location:versioning");

?>