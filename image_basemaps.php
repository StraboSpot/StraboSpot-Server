<?php
/**
 * File: image_basemaps.php
 * Description: Handles basemap image retrieval and display
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

?>

			<!-- Main -->
				<div id="main" class="wrapper style1">
					<div class="container">

						<header class="major">
							<h2>Image Basemaps</h2>
						</header>
							<section id="content">

<?php

$dataset_id=$_GET['dataset_id'];

$spots = $strabo->getDatasetSpots($dataset_id);

$image_basemaps = [];

foreach($spots['features'] as $spot){
	if($spot['properties']['image_basemap']!=""){
		if(!in_array($spot['properties']['image_basemap'],$image_basemaps)){
			$image_basemaps[] = $spot['properties']['image_basemap'];
		}
	}
}

if(count($image_basemaps) == 0){

?>

	No image basemaps found in this dataset.

							</section>

					<div class="bottomSpacer"></div>

					</div>
				</div>

<?php

	include 'includes/mfooter.php';
	exit();
}

?>
<div style="padding-bottom:5px;padding-left:3px;">
	<a href="zip_basemaps?dataset_id=<?php echo $dataset_id?>">Download All</a>
</div>
<table>
<?php

foreach($image_basemaps as $i){
	$imageinfo = $strabo->getImageInfo($i);
	$filename = $imageinfo->filename;
	?>
	<tr>
		<td><a href="image_basemap?i=<?php echo $i?>"><image src="/dbimages/<?php echo $filename?>" width="100"></a></td>
		<td style="padding-left:20px;"><a href="image_basemap?i=<?php echo $i?>">View</a></td>
		<td style="padding-left:20px;"><a href="download_basemap?i=<?php echo $i?>">Download</a></td>
	</tr>
	<?php
}
0
?>
</table>

							</section>

					<div class="bottomSpacer"></div>

					</div>
				</div>

<?php

include 'includes/mfooter.php';
?>