<?php
/**
 * File: collaborate.php
 * Description: Project Collaborators
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

include("logincheck.php");
include("prepare_connections.php");

$project_id = $_GET['p'] ?? '';
$project_id = preg_replace('/[^a-zA-Z0-9\-]/', '', $project_id);
if($project_id == "") exit("No project id provided.");

$project = $strabo->getProject($project_id);

if($project->Error != "") exit($project->Error);

$project_name = $project->description->project_name;

$rows = $db->get_results_prepared("
	SELECT 	collaborator_user_pkey,
			collaboration_level,
			accepted,
			uuid,
			(SELECT email FROM users WHERE pkey = collaborator_user_pkey) as email
	FROM collaborators
	WHERE strabo_project_id = $1 AND project_owner_user_pkey = $2
	ORDER BY email
", array($project_id, $userpkey));

include 'includes/mheader.php';
?>
<script>

function updateCollaborationLevel(uuid){
	console.log(uuid + ' changed');

	var collablevel = $('#collaborationlevel_'+uuid).find(":selected").val();
	console.log("level: " + collablevel);

	$.get("/update_collaboration_level?u="+uuid+"&l="+collablevel);

}

</script>
			<!-- Main -->
				<div id="main" class="wrapper style1">
					<div class="container">

						<header class="major">
							<h2>Project Collaborators</h2>
						</header>

<?php
if(count($rows) > 0){

?>

<div class="row gtr-uniform gtr-50">

<?php
foreach($rows as $row){
?>
	<div class="col-3 col-12-small">
		<?php echo $row->email?>
	</div>
	<div class="col-3 col-12-small">
		<select name="collaborationlevel_<?php echo $row->uuid?>" onchange="updateCollaborationLevel('<?php echo $row->uuid?>');" id="collaborationlevel_<?php echo $row->uuid?>" class="amyDataSelect">
			<option value="readonly"<?php if($row->collaboration_level=="readonly"){echo " selected";}?>>Read Only</option>
			<option value="edit"<?php if($row->collaboration_level=="edit"){echo " selected";}?>>Edit</option>
			<option value="admin"<?php if($row->collaboration_level=="admin"){echo " selected";}?>>Admin</option>
		</select>
	</div>
	<div class="col-3 col-12-small">

<?php
if($row->accepted == "t"){
?>

		<div class="button primary fit green">Status: Active</div>

<?php
}else{
?>

		<div class="button primary fit">Status: Pending</div>

<?php
}
?>
	</div>
	<div class="col-3 col-12-small">
		<!--<td style="width:10px;"><a href="delete_collaborator?p=<?php echo $project_id?>&u=<?php echo $row->uuid?>" class="amyDataSelect" onclick="return confirm('Are you sure you want to remove <?php echo $row->email?> from your collaborator list? This user will no longer be able to work on this project.')">X</a></td>-->

		<a href="delete_collaborator?p=<?php echo $project_id?>&u=<?php echo $row->uuid?>" class="button primary fit" onclick="return confirm('Are you sure you want to remove <?php echo $row->email?> from your collaborator list? This user will no longer be able to work on this project.')">Delete</a>

	</div>
	<div class="col-12 col-12-small hideBigNineEighty">
		<hr>
	</div>

<?php
}
?>

</div>

<?php
}else{
?>

		<div class="medHeader" style="padding-top:0px;padding-bottom:20px;text-align:center;">
			No collaborators exist yet for project <?php echo $project_name?>
			<div style="padding-top:30px;">
			</div>

		</div>

<?php
}
?>

		<div class="medHeader" style="padding-top:0px;padding-bottom:20px;text-align:center;">
			<div style="padding-top:30px;">
			<a href="invite_collaborators?p=<?php echo $project_id?>" class="button primary">Invite Collaborators</a>
			</div>

		</div>

					<div class="bottomSpacer"></div>

					</div>
				</div>

<?php
include 'includes/mfooter.php';

?>