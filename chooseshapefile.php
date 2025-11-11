<?php
/**
 * File: chooseshapefile.php
 * Description: Shapefile selection interface for geographic data import
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

include("logincheck.php");
include("prepare_connections.php");
include 'includes/mheader.php';

$dsids = $_GET['dsids'];

?>

			<!-- Main -->
				<div id="main" class="wrapper style1">
					<div class="container">

						<header class="major">
							<h2>Choose Shapefile Type</h2>
						</header>
							<section id="content">

<h3>Legacy Shapefile:</h3>
<div>
	This shapefile option exports additional columns for each orientation measurement which results in a single row for each spot, with multiple additional columns for each orientation measurement.
	<div style="text-align:center;padding-top:10px;">
		<input class="primary" type="submit" onclick="location.href='searchdownload?type=shapefile&userpkey=<?php echo $userpkey?>&dsids=<?php echo $dsids?>';" value="Download Legacy Shapefile" />
	</div>
</div>

<br><br>

<h3>Expanded Shapefile:</h3>
<div>
	This shapefile option exports additional rows for each orientation measurement for a given spot. This results in multiple rows per spot with orientation data collected in a columnar fashion. This export is a "pivoted" version of the legacy shapefile export.
	<div style="text-align:center;padding-top:10px;">
		<input class="primary" type="submit" onclick="location.href='searchdownload?type=expandedShapefile&userpkey=<?php echo $userpkey?>&dsids=<?php echo $dsids?>';" value="Download Expanded Shapefile" />
	</div>
</div>

<div style="display:none;">
searchdownload?type=shapefiledev&userpkey=<?php echo $userpkey?>&dsids=<?php echo $dsids?>
searchdownload?type=devexpandedShapefile&userpkey=<?php echo $userpkey?>&dsids=<?php echo $dsids?>
</div>

<?php

?>
<a href="searchdownload?type=shapefiledev&userpkey=<?php echo $userpkey?>&dsids=<?php echo $dsids?>">.</a>

							</section>

					<div class="bottomSpacer"></div>

					</div>
				</div>

<?php
include 'includes/mfooter.php';
?>