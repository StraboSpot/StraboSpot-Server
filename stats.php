<?php
/**
 * File: stats.php
 * Description: Backend Holdings
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

include("logincheck.php");

include("prepare_connections.php");

$admin_pkeys = array(3,9,7217);
if(!in_array($userpkey, $admin_pkeys)) {
	echo "unauthorized";
	exit();
}

include 'includes/header.php';

$users = $db->get_var("select count(*) from users where active");
$projects = $neodb->get_var("match (x:Project) return count(x)");
$datasets = $neodb->get_var("match (x:Dataset) return count(x)");
$spots = $neodb->get_var("match (x:Spot) return count(x)");
$images = $neodb->get_var("match (x:Image) return count(x)");
$samples = $neodb->get_var("match (x:Sample) return count(x)");

?>

<div style="">
	<h1>Backend Holdings</h1>
	<table style="font-weight:bold;">
		<tr><td>Users:</td><td><?php echo $users?></td></tr>
		<tr><td>Projects:</td><td><?php echo $projects?></td></tr>
		<tr><td>Datasets:</td><td><?php echo $datasets?></td></tr>
		<tr><td>Spots:</td><td><?php echo $spots?></td></tr>
		<tr><td>Images:</td><td><?php echo $images?></td></tr>
		<tr><td>Samples:</td><td><?php echo $samples?></td></tr>
	</table>
</div>

<?php
include 'includes/footer.php';
?>