<?php
/**
 * File: image_basemap.php
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

$i = $_GET['i'];

include 'includes/mheader.php';

?>

			<!-- Main -->
				<div id="main" class="wrapper style1">
					<div class="container">

						<header class="major">
							<h2>Image Basemap</h2>
						</header>
							<section id="content">

<div style="padding-bottom:5px;padding-left:30px;"><a href="download_basemap?i=<?php echo $i?>">Download</a></div>
<div style="text-align:center;">
	<a href="/basemap_image?i=<?php echo $i?>" target="_blank"><img width="1200px" src="/basemap_image?i=<?php echo $i?>"></a>
</div>

							</section>

					<div class="bottomSpacer"></div>

					</div>
				</div>

<?php
include 'includes/mfooter.php';
?>