<?php
/**
 * File: microErrorReports.php
 * Description: Displays and manages Strabo Micro error reports and diagnostics
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

include("logincheck.php");
include("prepare_connections.php");

include("includes/header.php");

$rows = $db->get_results("
						select
						pkey,
						(select firstname || ' ' || lastname from users where email = ml.email) as name,
						email,
						uploaded,
						notes
						from micro_logs ml order by pkey desc
						");

?>
<div align="center">
	<h2>Micro Error Reports</h2>
</div>

<div class="strabotable" style="margin-left:0px;">
	<table>

		<tr>
			<td style="width:50px;">&nbsp;</td>
			<td>Reported By</td>
			<td>Date</td>
			<td>Details</td>
		</tr>

		<?php
		foreach($rows as $row){
		?>
		<tr>
			<td style="width:50px;"><a href="microLogView?pkey=<?php echo $row->pkey?>" target="_blank">View</a></td>
			<td><?php echo $row->name?> (<?php echo $row->email?>)</td>
			<td><?php echo $row->uploaded?></td>
			<td><?php echo substr($row->notes, 0, 40)."..."?></td>
		</tr>
		<?php
		}
		?>

	</table>
</div>

<?php
include("includes/footer.php");
?>