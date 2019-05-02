<?

include("shapefuncs.php");
include("logincheck.php");
include("includes/config.inc.php");
include("db.php");
include_once('includes/geophp/geoPHP.inc');
include("neodb.php");

$userpkey=(int)$_SESSION['userpkey'];


//check for arcid
$arcid = $_GET['arcid'];

$count = $db->get_var("select count(*) from arcfiles where arcid='$arcid'");

if($count==0){
	exit();
}

include 'includes/header.php';


//check for projects here
$projectrows = $neodb->get_results("match (a:Project) where a.userpkey = $userpkey optional match (a)-[b:CONTAINS]-(d:Dataset) return distinct(a),count(d) order by a.id desc;");

if(count($projectrows)==0){
	?>
	No Projects found. You must create a project in which to store your shapefile. Click <a href="new_project?arcid=<?=$arcid?>">here</a> to add project.
	<?
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

	<?=$error?>

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
						<?
						foreach($projectrows as $pr){
						$pr=$pr->get("a");
						$pr=(object)$pr->values();
						?>
						<option value="<?=$pr->id?>"><?=$pr->desc_project_name?>
						<?
						}
						?>
					</select>
				</td>
			</tr>
		</table>
	


		<br><br>
	
		<input type="submit" name="submitarc" value="Submit">
		
	
		<input type="hidden" name="arcid" value="<?=$arcid?>">
	</form>

<?
include 'includes/footer.php';
?>
