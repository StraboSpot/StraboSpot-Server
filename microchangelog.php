<?php
/**
 * File: microchangelog.php
 * Description: Displays Strabo Micro version history and changelog
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
							<h2>Strabo Micro Changelog</h2>
						</header>

<div class="strabotable" style="margin-left:0px;">

	<table>

		<tr>
			<td>Version</td>
			<td>Date</td>
			<td>Changes</td>
		</tr>

		<tr>
			<td style="vertical-align:top;">1.2.18</td>
			<td style="vertical-align:top;">08/20/2025</td>
			<td>
				<ul style="padding-left:10px;">
					<li>Additional fixes for handling large project uploads.</li>
					<li>Updated JDeploy build routines for new API reference.</li>
					<li>Small bug fixes.</li>
				</ul>
			</td>
		</tr>

		<tr>
			<td style="vertical-align:top;">1.2.17</td>
			<td style="vertical-align:top;">08/13/2025</td>
			<td>
				<ul style="padding-left:10px;">
					<li>Fixed issue with large uploads truncating PDF output. PDF output routines re-written.</li>
					<li>Fixed issue with lithology not being reported if mineralogy not set.</li>
					<li>Small bug fixes.</li>
				</ul>
			</td>
		</tr>

		<tr>
			<td style="vertical-align:top;">1.2.15</td>
			<td style="vertical-align:top;">08/04/2025</td>
			<td>
				<ul style="padding-left:10px;">
					<li>Small bug fixes.</li>
					<li>Added ability to import cross-polarized images alongside plane-polarized images.</li>
				</ul>
			</td>
		</tr>

		<tr>
			<td style="vertical-align:top;">1.2.14</td>
			<td style="vertical-align:top;">07/10/2025</td>
			<td>
				<ul style="padding-left:10px;">
					<li>Small bug fixes.</li>
				</ul>
			</td>
		</tr>

		<tr>
			<td style="vertical-align:top;">1.2.12</td>
			<td style="vertical-align:top;">01/29/2025</td>
			<td>
				<ul style="padding-left:10px;">
					<li>Small bug fixes.</li>
					<li>Added functionality for downloading single annotated micrographs.</li>
					<li>Added functionality for downloading batch annotated micrographs.</li>
				</ul>
			</td>
		</tr>

		<tr>
			<td style="vertical-align:top;">1.2.10</td>
			<td style="vertical-align:top;">01/08/2025</td>
			<td>
				<ul style="padding-left:10px;">
					<li>Small bug fixes.</li>
					<li>Fixed issue with reference micrographs being incorrectly oriented when adding associated micrographs.</li>
				</ul>
			</td>
		</tr>

		<tr>
			<td style="vertical-align:top;">1.2.9</td>
			<td style="vertical-align:top;">09/30/2024</td>
			<td>
				<ul style="padding-left:10px;">
					<li>Small bug fixes.</li>
					<li>Move to jdeploy build routines for deployment.</li>
				</ul>
			</td>
		</tr>

		<tr>
			<td style="vertical-align:top;">1.2.6</td>
			<td style="vertical-align:top;">08/02/2024</td>
			<td>
				<ul style="padding-left:10px;">
					<li>Rewrite of image placement routines.</li>
					<li>Further improvements of large project uploads.</li>
					<li>Various bug fixes to app navigation.</li>
				</ul>
			</td>
		</tr>

		<tr>
			<td style="vertical-align:top;">1.0.6</td>
			<td style="vertical-align:top;">08/24/2023</td>
			<td>
				<ul style="padding-left:10px;">
					<li>Rewrite of upload routines to fix error with large project files.</li>
				</ul>
			</td>
		</tr>

		<tr>
			<td style="vertical-align:top;">1.0.5</td>
			<td style="vertical-align:top;">07/18/2023</td>
			<td>
				<ul style="padding-left:10px;">
					<li>Small bug fixes.</li>
				</ul>
			</td>
		</tr>

		<tr>
			<td style="vertical-align:top;">1.0.4</td>
			<td style="vertical-align:top;">06/28/2023</td>
			<td>
				<ul style="padding-left:10px;">
					<li>Added support for new StraboMicro Web Viewer.</li>
				</ul>
			</td>
		</tr>

		<tr>
			<td style="vertical-align:top;">1.0.2</td>
			<td style="vertical-align:top;">04/19/2023</td>
			<td>
				<ul style="padding-left:10px;">
					<li>Small update to fix associated micrograph bug.</li>
				</ul>
			</td>
		</tr>

		<tr>
			<td style="vertical-align:top;">1.0.0</td>
			<td style="vertical-align:top;">03/29/2023</td>
			<td>
				<ul style="padding-left:10px;">
					<li>Initial public production release.</li>
				</ul>
			</td>
		</tr>

		<tr>
			<td style="vertical-align:top;">0.2.39 Beta</td>
			<td style="vertical-align:top;">03/13/2023</td>
			<td>
				<ul style="padding-left:10px;">
					<li>Various bug fixes. Better stability.</li>
					<li>Improved tags interface.</li>
					<li>UI enhancements.</li>
					<li>Improved PDF export format.</li>
				</ul>
			</td>
		</tr>

		<tr>
			<td style="vertical-align:top;">0.2.35 Beta</td>
			<td style="vertical-align:top;">09/20/2022</td>
			<td>
				<ul style="padding-left:10px;">
					<li>Fixed bug that prevented older field projects from being imported into StraboMicro.</li>
				</ul>
			</td>
		</tr>

		<tr>
			<td style="vertical-align:top;">0.2.34 Beta</td>
			<td style="vertical-align:top;">08/11/2022</td>
			<td>
				<ul style="padding-left:10px;">
					<li>Fixed bug that prevented viewing remote samples on StraboSpot server.</li>
				</ul>
			</td>
		</tr>

		<tr>
			<td style="vertical-align:top;">0.2.33 Beta</td>
			<td style="vertical-align:top;">07/22/2022</td>
			<td>
				<ul style="padding-left:10px;">
					<li>Fixed bug that prevented showing/hiding associated micrographs.</li>
				</ul>
			</td>
		</tr>

		<tr>
			<td style="vertical-align:top;">0.2.32 Beta</td>
			<td style="vertical-align:top;">07/06/2022</td>
			<td>
				<ul style="padding-left:10px;">
					<li>Many improvements over previous versions.</li>
				</ul>
			</td>
		</tr>

		<tr>
			<td style="vertical-align:top;">0.2.4 Beta</td>
			<td style="vertical-align:top;">05/27/2021</td>
			<td>
				<ul style="padding-left:10px;">
					<li>Added instrument type to list of instruments.</li>
					<li>Added units for WDS dwell times.</li>
					<li>Fixed notes display for associated files.</li>
				</ul>
			</td>
		</tr>

		<tr>
			<td style="vertical-align:top;">0.2.3 Beta</td>
			<td style="vertical-align:top;">05/27/2021</td>
			<td>
				<ul style="padding-left:10px;">
					<li>Fixed text encoding error caused by non-UTF8 characters in Text Fields.</li>
				</ul>
			</td>
		</tr>

		<tr>
			<td style="vertical-align:top;">0.2.2 Beta</td>
			<td style="vertical-align:top;">05/26/2021</td>
			<td>
				<ul style="padding-left:10px;">
					<li>Fixed loading file bug.</li>
					<li>Various bug fixes.</li>
				</ul>
			</td>
		</tr>

		<tr>
			<td style="vertical-align:top;">0.2.1 Beta</td>
			<td style="vertical-align:top;">04/12/2021</td>
			<td>
				<ul style="padding-left:10px;">
					<li>Improved raster functionality.</li>
					<li>Various bug fixes.</li>
				</ul>
			</td>
		</tr>

		<tr>
			<td style="vertical-align:top;">0.2.0 Beta</td>
			<td style="vertical-align:top;">03/26/2021</td>
			<td>
				<ul style="padding-left:10px;">
					<li>Complete rewrite of image handling routines. New raster-based system alleviates slowdowns associated with large number of images, and is much more scalable.</li>
					<li>Added scanner instrument type option.</li>
				</ul>
			</td>
		</tr>

		<tr>
			<td style="vertical-align:top;">0.1.4 Beta</td>
			<td>03/18/2021</td>
			<td>
				<ul style="padding-left:10px;">
					<li>New features include spot-level color options and spot-level labels useful for annotation.</li>
				</ul>
			</td>
		</tr>

		<tr>
			<td style="vertical-align:top;">0.1.3 Beta</td>
			<td>02/16/2021</td>
			<td>
				<ul style="padding-left:10px;">
					<li>Initial Beta Release for Community Testing</li>
				</ul>
			</td>
		</tr>

		<tr>
			<td style="vertical-align:top;">0.1.2 Pre-Release Beta</td>
			<td>02/03/2021</td>
			<td>
				<ul style="padding-left:10px;">
					<li>Initial Beta Release for Internal Testing</li>
				</ul>
			</td>
		</tr>

	</table>

</div>

					<div class="bottomSpacer"></div>

					</div>
				</div>

<?php
include 'includes/mfooter.php';
?>