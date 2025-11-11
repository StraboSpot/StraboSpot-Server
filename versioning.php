<?php
/**
 * File: versioning.php
 * Description: Versioning Data
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

include("logincheck.php");

include("prepare_connections.php");

include 'includes/mheader.php';
//get groups based on userpkey

?>

			<!-- Main -->
				<div id="main" class="wrapper style1">
					<div class="container">

						<header class="major">
							<h2>Versioning Data</h2>
						</header>

<div style ="padding-left:0px;padding-top:20px;">
All versions are kept, but using a version rolls back to that copy.
</div>
<div style ="padding-left:0px;padding-top:20px;">
<?php

$exprows = $db->get_results("select uuid from straboexp.versions where userpkey=$userpkey group by uuid");

if(count($exprows) > 0){
?>
<h2>StraboExperimental Data:</h2>
<?php
	foreach($exprows as $row){

		$valRows = $db->get_results("select pkey,projectname,uuid,to_char(datecreated, 'Mon DD, YYYY - HH12:MI:SSPM TZ') as datecreated,experimentcount
													from straboexp.versions where uuid = '$row->uuid' and userpkey = $userpkey order by pkey asc");
		foreach($valRows as $vRow){
			$projectname = $vRow->projectname;
			break;
		}

		?>
		<h3>Project Name: <?php echo $projectname?></h3><br>

		<div class="strabotable" style="margin-left:0px;margin-bottom:20px;">

			<table>

				<thead>
				<tr>
					<th>&nbsp;</th>
					<th>Date:</th>
					<th>Experiments:</th>
					<th>&nbsp;</th>
				</tr>
				</thead>

		<?php
		foreach($valRows as $vr){

			$uuid = $vr->uuid;
			$datecreated = $vr->datecreated;
			$experimentcount = $vr->experimentcount;

		?>
		<tr>
			<td style="width:100px;">
				<a href="experimental/activate_version?p=<?php echo $vr->pkey?>" OnClick="return confirm('Are you sure you want to activate this version from  <?php echo $datecreated?>?')">Enable</a>
			</td>

			<td>
				<?php echo $datecreated?>
			</td>
			<td>
				<?php echo $experimentcount?>
			</td>
			<td style="width:50px;">
				<a href="experimental/delete_version?p=<?php echo $vr->pkey?>" OnClick="return confirm('Are you sure you want to delete this version from  <?php echo $datecreated?>?')">Delete</a>
			</td>
		</tr>

		<?php
		}
		?>

			</table>

		</div>

		<?php

	}
}
?>
<h2>StraboField Data:</h2>
<?php
$projectrows = $db->get_results("select projectid from versions where userpkey=$userpkey group by projectid order by projectid desc");
if(count($projectrows)==0){
	?>
		No Version data found.
	<?php
}else{

	foreach($projectrows as $projectrow){

		$projectid = $projectrow->projectid;

		$versionrows = $db->get_results("select projectname,uuid,to_char(datecreated, 'Mon DD, YYYY - HH12:MI:SSPM TZ') as datecreated,datasetcount,spotcount
													from versions where projectid = '$projectid' and userpkey = $userpkey order by pkey asc");

		foreach($versionrows as $vr){
			if($vr->projectname!=""){
				$projectname = $vr->projectname;
				break;
			}
		}

		?>

		<div style = "padding-left:5px; padding-top:5px; border: 0px dashed #CCCCCC; margin-bottom:15px;">

		<h3>Project Name: <?php echo $projectname?></h3><br>

		<div class="strabotable" style="margin-left:0px;">

		<table>

		<thead>
		<tr>
			<th>&nbsp;</th>
			<th>Date:</th>
			<th>Datasets:</th>
			<th>Spots:</th>
			<th>&nbsp;</th>
		</tr>
		</thead>

		<?php
		foreach($versionrows as $vr){

			$uuid = $vr->uuid;
			$datecreated = $vr->datecreated;
			$datasetcount = $vr->datasetcount;
			$spotcount = $vr->spotcount;

		?>
		<tr>
			<td style="width:100px;">
				<a href="activate_version?uuid=<?php echo $uuid?>" OnClick="return confirm('Are you sure you want to activate this version from  <?php echo $datecreated?>?')">Enable</a>
			</td>

			<td>
				<?php echo $datecreated?>
			</td>
			<td>
				<?php echo $datasetcount?>
			</td>
			<td>
				<?php echo $spotcount?>
			</td>
			<td style="width:50px;">
				<a href="delete_version?uuid=<?php echo $uuid?>" OnClick="return confirm('Are you sure you want to delete this version from  <?php echo $datecreated?>?')">Delete</a>
			</td>
		</tr>

		<?php
		}
		?>
		</table>

		</div><br><br>

		</div>
		<?php

	}

}// end if count projectrows
?>

</div>

<!--
<?php echo $total?>
-->
					<div class="bottomSpacer"></div>

					</div>
				</div>

<?php
include 'includes/mfooter.php';
?>