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

include("adminheader.php");
?>

		<div class="linkheading">
			<a href="uploaddata.php">Upload XLSX Data</a>
		</div>
		<div class="linkheading">
			<a href="uploadkmz.php">Upload KMZ File</a>
		</div>

<?php
include("adminfooter.php");
?>