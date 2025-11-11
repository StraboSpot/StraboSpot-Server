<?php
/**
 * File: edit_project.php
 * Description: Project editing interface for modifying project details and settings
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

include("../logincheck.php");
include("../prepare_connections.php");
include("../expdb/straboexpclass.php");

$pkey = isset($_GET['ppk']) ? (int)$_GET['ppk'] : 0;

$credentials = $_SESSION['credentials'];

if(!in_array($userpkey, $admin_pkeys)){
	$is_admin = false;
}else{
	$is_admin = true;
}

//Check to be sure we are authorized to edit this facility
if($is_admin){
	$row = $db->get_row_prepared("SELECT * FROM straboexp.project WHERE pkey = $1", array($pkey));
}else{
	$row = $db->get_row_prepared("SELECT * FROM straboexp.project WHERE pkey = $1 AND userpkey = $2", array($pkey, $userpkey));
}

if($row->pkey == ""){
	echo "project not found.";exit();
}

//Check to be sure we are authorized to edit this facility


if($is_admin){
	//$db->query("delete from straboexp.project where pkey = $pkey");
}else{
	//$db->query("delete from straboexp.project where pkey = $pkey and userpkey = $userpkey");
}

//header("Location: /my_data#straboexperimental");

$project_pkey = $row->pkey;
$project_name = $row->name;
$project_notes = $row->notes;



include("../includes/header.php");
?>
<link rel="stylesheet" href="/experimental/experimental.css" type="text/css" />
<script src='/experimental/experimental.js'></script>
<script src='/assets/js/jquery/jquery.min.js'></script>

<div class="topTitle">Edit Experimental Project</div>


<!--<a onClick="doTest();">do test</a>-->

<div id="bigWindow">
	<fieldset class="mainFS">
		<legend class="mainFSLegend">Project Info</legend>
		<div class="formRow">
			<div class="formCell w33">
				<div class="formPart">
					<label class="formLabel">Project Name <span class="reqStar">*</span></label>
					<input id="projectName" class="formControl" type="text" value="<?php echo $project_name?>"></input>
				</div>
			</div>
		</div>
		<div class="formRow">
			<div class="formCell w100">
				<div class="formPart">
					<label class="formLabel">Description</span></label>
					<textarea class="formControl docDescText" data-schemaformat="markdown" id="projectDescription"><?php echo $project_notes?></textarea>
				</div>
			</div>
		</div>
	</fieldset>
	<div class="fsSpacer"></div>

	<input type="hidden" name="projectPkey" id="projectPkey" value="<?php echo $project_pkey?>">

	<div style="text-align:center;">
		<button class="submitButton" style="vertical-align:middle;margin-right:150px;" onclick="javascript:history.back();"><span>Cancel </span></button>
		<button class="submitButton" style="vertical-align:middle" onclick="doSubmitEditProject()"><span>Save </span></button>
	</div>
</div>

<?php
include("../includes/footer.php");
?>










?>