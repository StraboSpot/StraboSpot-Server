<?php
/**
 * File: loadarcshapefile.php
 * Description: Shapefile Upload
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


include("shapefuncs.php");
include("logincheck.php");
include("includes/config.inc.php");
include("db.php");
include_once('includes/geophp/geoPHP.inc');
include("neodb.php");

$userpkey=(int)$_SESSION['userpkey'];

//check for arcid
$arcid = $_GET['arcid'] ?? '';
$arcid = preg_replace('/[^a-zA-Z0-9\-]/', '', $arcid);

$count = $db->get_var_prepared("SELECT count(*) FROM arcfiles WHERE arcid=$1", array($arcid));

if($count==0){
	exit();
}

include 'includes/header.php';

$safe_userpkey = addslashes($userpkey);

//check for projects here
$projectrows = $neodb->get_results("match (a:Project) where a.userpkey = $safe_userpkey optional match (a)-[b:CONTAINS]-(d:Dataset) return distinct(a),count(d) order by a.id desc;");

if(count($projectrows)==0){
	?>
	No Projects found. You must create a project in which to store your shapefile. Click <a href="new_project?arcid=<?php echo $arcid?>">here</a> to add project.
	<?php
	include 'includes/footer.php';
	exit();
}

?>

	<script type="text/javascript">

	function formvalidate(){
		var errors='';

		var e = document.getElementById("projectid");
		var projectid = e.options[e.selectedIndex].value;

		if(projectid=="" || projectid==null){errors=errors+'Project must be selected.\n';}

		if(errors!="" && errors!=null){
			alert(errors);
			return false;
		}
	}

	</script>

	<h2>Shapefile Upload</h2><br>

	<?php echo $error?>

	<form name="uploadform" method="POST" action="load_shapefile" onsubmit="return formvalidate();" enctype="multipart/form-data">

		<table>
			<tr>
				<td>
					Project:
				</td>
				<td>
					&nbsp;&nbsp;
					<select name="projectid" id="projectid">
						<option value="">Select...
						<?php
						foreach($projectrows as $pr){
						$pr=$pr->get("a");
						$pr=(object)$pr->values();
						?>
						<option value="<?php echo $pr->id?>"><?php echo $pr->desc_project_name?>
						<?php
						}
						?>
					</select>
				</td>
			</tr>
		</table>

		<br><br>

		<input type="submit" name="submitarc" value="Submit">

		<input type="hidden" name="arcid" value="<?php echo $arcid?>">
	</form>

<?php
include 'includes/footer.php';
?>
