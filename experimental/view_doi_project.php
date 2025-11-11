<?php
/**
 * File: view_doi_project.php
 * Description: Displays Digital Object Identifier (DOI) information and metadata
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

//include("../logincheck.php");
include("../prepare_connections.php");

$uuid = isset($_GET['u']) ? preg_replace('/[^a-zA-Z0-9\-]/', '', $_GET['u']) : '';
if($uuid == "") die("Project not found.");
$row = $db->get_row_prepared("SELECT * FROM dois WHERE uuid = $1", array($uuid));
if($row->pkey == "") die("Project not found.");

if($row->doi != "") $doi = $row->doi;

$doiuserpkey = $row->user_pkey;
$userrow = $db->get_row_prepared("SELECT * FROM users WHERE pkey = $1", array($doiuserpkey));
$owner = $userrow->firstname." ".$userrow->lastname;

$json = file_get_contents("/srv/app/www/doi/doiFiles/$uuid/project.json");
$json = json_decode($json);

$projectname = $json->name;
$created = $json->created_timestamp;
$modified = $json->modified_timestamp;
$experiments = $json->experiments;


include("../includes/header.php");
?>

<h2 style="text-align:center;">StraboExperimental Project:</h2>
<h2 style="text-align:center;"><?php echo $projectname?></h2>
<div style="text-align:center;">Owner: <?php echo $owner?></div>

<?php
if($doi != ""){
?>
<div style="text-align:center;">DOI: <?php echo $doi?></div>
<?php
}
?>

<div style="text-align:center;">Created: <?php echo $created?></div>
<div style="text-align:center;">Modified: <?php echo $modified?></div>

<?php
if(count($experiments) > 0){
?>

	<h3>Experiments:</h3>
	<div class="strabotable" style="margin-left:0px;">
		<table>
			<tbody>
				<tr>
					<td style="width:140px;"></td>
					<td>Name</td>
					<td style="width:110px;">Created</td>
					<td style="width:110px;">Modified</td>
				</tr>
<?php
foreach($experiments as $row){

	$name = $row->metadata->name;
	$created_timestamp = $row->metadata->created_timestamp;
	$modified_timestamp = $row->metadata->modified_timestamp;
	$exuuid = $row->metadata->uuid;

?>
				<tr>
					<td>
						<div style="text-align:center;">
							<a href="view_doi_experiment?p=<?php echo $uuid?>&e=<?php echo $exuuid?>" target="_blank">View</a>&nbsp;&nbsp;&nbsp;&nbsp;
							<a href="download_doi_experiment?p=<?php echo $uuid?>&e=<?php echo $exuuid?>" target="_blank">Download</a>
						</div>
					</td>
					<td nowrap=""><?php echo $name?></div></td>
					<td nowrap=""><?php echo $created_timestamp?></div></td>
					<td nowrap=""><?php echo $modified_timestamp?></div></td>
				</tr>
<?php
}
?>
			</tbody>
		</table>
	</div>

<?php
}else{
?>
	No saved DOI projects found.
<?php
}
?>




<?php
include("../includes/footer.php");
?>