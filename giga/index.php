<?php
/**
 * File: index.php
 * Description: Main page or directory index
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

include_once "../includes/config.inc.php"; //credentials, etc
include "../db.php"; //postgres database abstraction layer

include("header.php");
?>

<div style"padding-left:30px;">
	<table border="0">
		<tr>
			<td>
				<div class="aboutbox" style="width:800px;">
					<p>
					Welcome to the Rogers Atlas of Rocks in Thin Section. This site comprises ~130 images of whole petrographic thin sections, mostly in paired plane- and polarized-light views. Images are hosted at gigapan.com, and most are >20,000 pixels in long dimension, which allows smooth zooming from whole-image scale to areas ~1 mm across. The atlas includes terrestrial samples, lunar samples, and meteorites. It is slanted heavily toward igneous rocks. We hope it will be expanded in days to come.
					</p>
					<p>
					This site was put together with funding from the John and Barbara Rogers Fund for Excellence in Geochemistry of the University of North Carolina Department of Geological Sciences. Professor John J. W. Rogers was a great friend of the department, and this atlas is named for him.
					</p>
					<p>
					A tremendous amount of work went into acquiring and processing these images. Special thanks go to Dr. Pablo Ariel, Director of the Microscopy Services Laboratory at the University of North Carolina Department of Pathology and Laboratory Medicine, who trained users and oversaw image acquisition on the Olympus IX81 widefield inverted microscope. The website was put together on short notice by Jason Ash, a lead programmer for the Strabospot digital data acquisition effort (strabospot.org). Students Naomi Becker, Gracie Pearsall, and Skylar Goliber acquired and processed the images. Samples were provided by Rosalind Helz, Gerhard Wörner, J. Robert Butler, Anthony Love, Walt Gray, Steven Singletary, Skip Stoddard, Kayla Ireland, Steve Ehrenberg, Bryan Law, and NASA, via the lunar collection efforts of the crews of Apollo 12, 14, 15, 16, and 17.
					</p>
					<div>
					—Allen Glazner, March 2020
					</div>
				</div>
			</td>
		</tr>
		<tr>
			<td>
				<div style="text-align: center">
					<hr/>
				</div>
			</td>
		</tr>
		<tr>
			<td>
				<div style="text-align: center">
					<span class="detailheading">Keywords:</span> <input type="text" id="keywords">
				</div>
			</td>
		</tr>
		<tr>
			<td>

				<table width="100%" border="0">
					<tr>

						<td width="50%" valign="top">
							<div style="padding-left:100px;">
								<div class="detailheading">Class:</div>
								<div class="searchitem"><input type="checkbox" class="rockclass" id="igneous" name="igneous"><label for="igneous"> igneous</label></div>
								<div class="searchitem"><input type="checkbox" class="rockclass" id="metamorphic" name="metamorphic"><label for="metamorphic"> metamorphic</label></div>
								<div class="searchitem"><input type="checkbox" class="rockclass" id="lunar" name="lunar"><label for="lunar"> lunar</label></div>
								<div class="searchitem"><input type="checkbox" class="rockclass" id="meteorite" name="meteorite"><label for="meteorite"> meteorite</label></div>
							</div>
						</td>
						<td width="50%" valign="top">
							<div style="padding-left:150px;">
								<div class="detailheading">Type:</div>
								<div class="searchitem"><input type="checkbox" class="rocktype" id="volcanic" name="volcanic"><label for="volcanic"> volcanic</label></div>
								<div class="searchitem"><input type="checkbox" class="rocktype" id="plutonic" name="plutonic"><label for="plutonic"> plutonic</label></div>
								<div class="searchitem"><input type="checkbox" class="rocktype" id="regional_metamorphic" name="regional_metamorphic"><label for="regional_metamorphic"> regional metamorphic</label></div>
								<div class="searchitem"><input type="checkbox" class="rocktype" id="contact_metamorphic" name="contact_metamorphic"><label for="contact_metamorphic"> contact metamorphic</label></div>
								<div class="searchitem"><input type="checkbox" class="rocktype" id="cumulate" name="cumulate"><label for="cumulate"> cumulate</label></div>
								<div class="searchitem"><input type="checkbox" class="rocktype" id="mantle" name="mantle"><label for="mantle"> mantle</label></div>
							</div>
						</td>

					</tr>

				</table>

			</td>
		</tr>
		<tr>
			<td>
				<div style="text-align: center;width:100%;padding-top:20px;padding-bottom:20px;">
					<!--
					<table border="0" width="100%">
						<tr>
							<td>
								<input class="allsamplesbutton" type="submit" value="View All Samples" onclick="viewAll();">
							</td>
							<td>
								<input class="savebutton" type="submit" value="Search" onclick="doSearch();">
							</td>
						<tr>
					</table>
					-->
					<input class="savebutton" type="submit" value="Search" onclick="doSearch();">
				</div>
			</td>
		</tr>
		<tr>
			<td>
				<div style="text-align: center;width:100%;">
					<hr/>
					<div>OR</div>
					<hr/>
				</div>
			</td>
		</tr>
		<tr>
			<td>
				<div style="text-align: center;width:100%;padding-top:20px;">
					<input class="allsamplesbutton" type="submit" value="View All Rocks" onclick="viewAll();">
				</div>
			</td>
		</tr>
	</table>
</div>

<script>
	//Add listener to keywords box to do search when enter is pressed
	var input = document.getElementById("keywords");
	input.addEventListener("keyup", function(event) {
		if (event.keyCode === 13) {
			doSearch();
		}
	});
</script>

<?php
include("footer.php");
?>