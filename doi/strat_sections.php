<?php
/**
 * File: strat_sections.php
 * Description: Stratigraphic section display and management
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

//include("logincheck.php");
$uuid = pg_escape_string($_GET['u']);
if($uuid == "") die("No UUID provided.");

function dumpVar($var){
	echo "<pre>";
	print_r($var);
	echo "</pre>";
}

//Gather data from DOI file:
$json = file_get_contents("doiFiles/".$uuid."/data.json");
if($json == "") die("DOI file not found.");

$json = json_decode($json, true);
$spots = $json['spotsDb'];

//dumpVar($spots);exit();

include '../includes/header.php';

$strat_spots = [];

foreach($spots as $spot){
	if($spot['properties']['sed']['strat_section']){
		$strat_spots[]=$spot;
	}
}

//dumpVar($strat_spots);exit();
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
				<td><div align="center"><a href="strat_section?id=<?php echo $strat_spot_id?>&u=<?php echo $uuid?>" target="_blank">Download</a></div></td>
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
	Sorry. No Strat Section data exists for this project.
	<?php
}
































include '../includes/footer.php';
?>