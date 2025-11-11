<?php
/**
 * File: whatisstrabospotoffline.php
 * Description: What is StraboSpot Offline?
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

include 'includes/mheader.php';
?>

			<!-- Main -->
				<div id="main" class="wrapper style1">
					<div class="container">

						<header class="major">
							<h2>What is StraboSpot Offline?</h2>
						</header>
<p>
StraboSpot Offline allows members of the Strabo community host their own Strabo backend as
an offline server. This is especially useful in a field-camp scenario, where internet access likely
isn’t available. StraboSpot offline allows users to back up their application data, upload
shapefiles, create custom downloadable maps, and maintain versioned copies of projects and
datasets; all without internet access.
</p>

						<header class="major">
							<h2>How does StraboSpot Offline Work?</h2>
						</header>
<p>
StraboSpot Offline is a web application packaged in a container that can be run on a variety of
computer systems. A container is a standard unit of software that packages up code and all its
dependencies, so the application runs quickly and reliably from one computing environment to
another. StraboSpot Offline uses a Docker container, which is a lightweight, standalone,
executable package of software that includes everything needed to run an application: code,
runtime, system tools, system libraries and settings.
</p>

						<header class="major">
							<h2>What is Needed to Run StraboSpot Offline?</h2>
						</header>
<p>
StraboSpot Offline will run on nearly any computer. For an offline field-camp scenario, this will
likely be a laptop, but StraboSpot Offline will also run on any desktop or server. Docker
software is available for Mac, Windows, and Linux.
In addition to a laptop for running the Docker image, a network device will also be needed.
There are a variety of travel WIFI routers that are suitable, many of which can be powered by
USB battery banks which make using in the field quite easy.
</p>

<div style="text-align:center;font-size:1.5em;padding-top:20px;">
	<a href="/StraboSpotOfflineManual" target="_blank">StraboSpot Offline User Guide</a>
</div>

<div style="text-align:center;font-size:1.5em;padding-top:20px;">
	<a href="/StraboSpotOffline.zip">Download StraboSpot Offline</a>
</div>

					<div class="bottomSpacer"></div>

					</div>
				</div>

<?php
include 'includes/mfooter.php';
?>
