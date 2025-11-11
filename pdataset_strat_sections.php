<?php
/**
 * File: pdataset_strat_sections.php
 * Description: Displays stratigraphic sections for datasets
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


include("prepare_connections.php");

$credentials = $_SESSION['credentials'];

include 'includes/header.php';

$strat_spots = [];

$dataset_ids=explode(",",$_GET['dataset_ids']);

foreach($dataset_ids as $dataset_id){
	$spots = $strabo->getPublicDatasetSpots($dataset_id);

	foreach($spots['features'] as $spot){
		if($spot['properties']['sed']->strat_section){
			$strat_spots[]=$spot;
		}
	}
}

?>

<h2>Strat Sections:</h2>

<?php

if(count($strat_spots)>0){
	?>

			<div class="strabotable" style="margin-left:0px;">

			<table>

			<tr>
				<td style="width:100px;">&nbsp;</td>
				<td>Strat Section</td>
			</tr>
	<?php
	foreach($strat_spots as $spot){
		$name = $spot['properties']['name'];
		$strat_spot_id = $spot['properties']['id'];
	?>
			<tr>
				<td><div align="center"><a href="pstrat_section?id=<?php echo $strat_spot_id?>&did=<?php echo $dataset_id?>" target="_blank">Download</a></div></td>
				<td><?php echo $name?></td>
			</tr>
	<?php
	}
	?>
			</table>

			</div>

	<?php
}else{
	?>
	Sorry. No Strat Section data exists.
	<?php
}

include 'includes/footer.php';
?>