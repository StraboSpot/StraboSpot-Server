<?php
/**
 * File: igsnland.php
 * Description: International Geo Sample Number (IGSN) integration
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

include 'includes/mheader.php';
$igsn = $_GET['igsn'];
?>

			<!-- Main -->
				<div id="main" class="wrapper style1">
					<div class="container">

						<header class="major">
							<h2>Details for IGSN <?php echo $igsn?></h2>
						</header>

<div class="medHeader" style="padding-top:20px;padding-bottom:20px;text-align:center;">
For Future Development.<br>This page will eventually display details for all StraboField, StraboMicro,
<br>
and StraboExperimental projects that contain data for IGSN <?php echo $igsn?>
</div>

					<div class="bottomSpacer"></div>

					</div>
				</div>

<?php
include 'includes/mfooter.php';
?>