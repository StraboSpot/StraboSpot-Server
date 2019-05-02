<?
include("logincheck.php");

include("prepare_connections.php");

include 'includes/header.php';
//get groups based on userpkey

$projectrows = $db->get_results("select projectid from versions where userpkey=$userpkey group by projectid order by projectid desc");
?>





<h2>Versioning Data:</h2>
<div style ="padding-left:0px;padding-top:20px;">
All versions are kept, but using a version rolls back to that copy.
</div>
<div style ="padding-left:0px;padding-top:20px;">
<?

if(count($projectrows)==0){
	?>
		No Version data found.
	<?
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
	
		<h3>Project Name: <?=$projectname?></h3><br>
	

		
		<div class="strabotable" style="margin-left:0px;">

		<table>

		<tr>
			<td>&nbsp;</td>
			<td>Version Created Date:</td>
			<td>Number of Datasets:</td>
			<td>Number of Spots:</td>
			<td>&nbsp;</td>
		</tr>

		<?
		foreach($versionrows as $vr){
		
			$uuid = $vr->uuid;
			$datecreated = $vr->datecreated;
			$datasetcount = $vr->datasetcount;
			$spotcount = $vr->spotcount;

		?>
		<tr>
			<td style="width:100px;">
				<a href="activate_version?uuid=<?=$uuid?>" OnClick="return confirm('Are you sure you want to activate this version from  <?=$datecreated?>?')">Use This Version</a>
			</td>

			<td>
				<?=$datecreated?>
			</td>
			<td>
				<?=$datasetcount?>
			</td>
			<td>
				<?=$spotcount?>
			</td>
			<td style="width:50px;">
				<a href="delete_version?uuid=<?=$uuid?>" OnClick="return confirm('Are you sure you want to delete this version from  <?=$datecreated?>?')">Delete</a>
			</td>
		</tr>

		<?
		}
		?>
		</table>

		</div><br><br>

		</div>
		<?
	
	}

}// end if count projectrows
?>

</div>

<!--
<?=$total?>
-->



<?
include 'includes/footer.php';
?>