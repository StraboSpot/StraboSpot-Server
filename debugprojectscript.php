<?
//15127006137888
//15200201621597

include("logincheck.php");

$project_id = $_GET['project_id'];

if($project_id==""){
	echo "no project id provided";exit();
}

if(!is_numeric($project_id)){
	echo "invalid project id.";exit();
}

include("prepare_connections.php");

$credentials = $_SESSION['credentials'];

$row = $neodb->get_results("match (p:Project {userpkey:$userpkey,id:$project_id}) optional match (p)-[HAS_DATASET]->(d:Dataset) optional match (d)-[HAS_SPOT]->(s:Spot) with p,d,count(s) as count with p,collect ({d:d,count:count}) as d return p,d order by p.modified_timestamp desc;");
$row = $row[0];

if($row==""){
	echo "project $project_id not found";exit();
}

include 'includes/header.php';

$project = $row->get("p");
$project=(object)$project->values();

$project_id = $project->id;

//echo "project_id: $project_id";

$project_data = $strabo->getProject($project_id);
$project_data = json_encode($project_data, JSON_PRETTY_PRINT);

?>
<h2>Project JSON:</h2>
<pre>
<?=$project_data?>
</pre>
<br><br>
<?

$datasets = $row->get("d");
if(count($datasets)>0){

	foreach($datasets as $dataset){
	
		$spotcount = $dataset["count"];
		
		$ds=$dataset["d"];
		$ds=(object)$ds->values();

		$dataset_id = $ds->id;
		
		$dataset_data = $strabo->getSingleDataset($dataset_id);
		$dataset_data = json_encode($dataset_data, JSON_PRETTY_PRINT);
		
		?>
		<h2>Dataset JSON:</h2>
		<pre>
		<?=$dataset_data?>
		</pre>
		<br><br>
		<?
		
		
		?>
		<h2>Spots JSON:</h2>
		<?
		if($spotcount > 0){
		
			$spot_data = $strabo->getDatasetSpots($dataset_id);
			$spot_data = json_encode($spot_data, JSON_PRETTY_PRINT);
			?>
			<pre>
			<?=$spot_data?>
			</pre>
			<br><br>
			<?
		
		}else{
			?>
			<pre>
			No spots found for this dataset.
			</pre>
			<br><br>
			<?
		}

	}
}




include 'includes/footer.php';












?>