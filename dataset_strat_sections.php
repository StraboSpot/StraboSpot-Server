<?php
/**
 * File: dataset_strat_sections.php
 * Description: Displays stratigraphic sections for datasets
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

include("logincheck.php");

include("prepare_connections.php");

$credentials = $_SESSION['credentials'];

include 'includes/mheader.php';

$dataset_id=$_GET['dataset_id'];

$spots = $strabo->getDatasetSpots($dataset_id);

$strat_spots = [];

foreach($spots['features'] as $spot){
	if($spot['properties']['sed']->strat_section){
		$strat_spots[]=$spot;
	}
}

?>

			<!-- Main -->
				<div id="main" class="wrapper style1">
					<div class="container">

						<header class="major">
							<h2>Strat Sections:</h2>
						</header>
							<section id="content">

<?php

if(count($strat_spots)>0){
	?>

			<div class="strabotable" style="margin-left:0px;">

			<table>

			<tr>
				<td style="width:100px;">Download</td>
				<td>Strat Section</td>
			</tr>
	<?php
	foreach($strat_spots as $spot){
		$name = $spot['properties']['name'];
		$strat_spot_id = $spot['properties']['id'];
	?>
			<tr>
				<td nowrap>
					<div align="center">
						<a href="strat_section?id=<?php echo $strat_spot_id?>&did=<?php echo $dataset_id?>" target="_blank">SVG</a>
						&nbsp;&nbsp;&nbsp;
						<a href="strat_section_csv?id=<?php echo $strat_spot_id?>&did=<?php echo $dataset_id?>" target="_blank">CSV</a>
					</div>
				</td>
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
	Sorry. No Strat Section data exists in this dataset.
	<?php
}

?>
							</section>

					<div class="bottomSpacer"></div>

					</div>
				</div>
<?php

include 'includes/mfooter.php';
?>