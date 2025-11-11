<?php
/**
 * File: microLogView.php
 * Description: Displays information from users table(s)
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


include("logincheck.php");
include("prepare_connections.php");

$pkey = isset($_GET['pkey']) ? (int)$_GET['pkey'] : 0;
$row = $db->get_row_prepared("SELECT
						pkey,
						(select firstname || ' ' || lastname from users where email = ml.email) as name,
						email,
						uploaded,
						notes,
						appversion
						FROM micro_logs ml WHERE pkey = $1"
					, array($pkey));

if($row->pkey == "") exit();

include("includes/header.php");

$log = file_get_contents("microLogs/$pkey.txt");

?>
<div align="center">
	<h2>Error Report</h2>
</div>

<h3>Reported By: <?php echo $row->name?> (<a href="mailto:<?php echo $row->email?>"><?php echo $row->email?></a>)</h3>
<h3>Time Reported: <?php echo $row->uploaded?>
<h3>StraboMicro Version: <?php echo $row->appversion?>

<h3 style="padding-top:25px;">Description of Error:</h3>
<div style="padding-left:50px;"><?php echo $row->notes?></div>

<h3 style="padding-top:25px;">Log File:</h3>
<pre style="padding-left:50px;"><?php echo $log?></pre>

<?php
include("includes/footer.php");
?>