<?
include("logincheck.php");

include("prepare_connections.php");

$credentials = $_SESSION['credentials'];

//$neodb->dumpVar($projectrows);exit();

include 'includes/header.php';


$dataset_id=$_GET['dataset_id'];

$spots = $strabo->getDatasetSpots($dataset_id);

//$neodb->dumpVar($spots);exit();

$strat_spots = [];

foreach($spots['features'] as $spot){
	if($spot['properties']['sed']->strat_section){
		$strat_spots[]=$spot;
	}
}

//$neodb->dumpVar($strat_spots);
/*
foreach($strat_spots as $spot){
	$neodb->dumpVar($spot);
}
*/

?>

<h2>Strat Sections:</h2>

<?

if(count($strat_spots)>0){
	?>


			<div class="strabotable" style="margin-left:0px;">

			<table>

			<tr>
				<td style="width:100px;">&nbsp;</td>
				<td>Strat Section</td>
			</tr>
	<?
	foreach($strat_spots as $spot){
		$name = $spot['properties']['name'];
		$strat_spot_id = $spot['properties']['id'];
	?>
			<tr>
				<td><div align="center"><a href="strat_section?id=<?=$strat_spot_id?>&did=<?=$dataset_id?>" target="_blank">Download</a></div></td>
				<td><?=$name?></td>
			</tr>
	<?
	}
	?>
			</table>
			
			</div>


	<?
}else{
	?>
	Sorry. No Strat Section data exists in this dataset.
	<?
}
































include 'includes/footer.php';
?>