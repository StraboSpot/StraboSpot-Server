<?php
/**
 * File: locationdetails.php
 * Description: Handles locationdetails operations
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

$id = $_GET['id'];

function dumpVar($var){
	echo "<pre>\n";
	print_r($var);
	echo "\n</pre>";
}

$json = file_get_contents("data/measurements.json");
$rows = json_decode($json);

$outrows = [];

foreach($rows as $row){
	if($row->location_1==$id || $row->location_2==$id){
		$outrows[]=$row;
	}
}

if(count($outrows)>0){
	?>
	<div style="padding:5px;">
		<div class="locationheading">
			Details for <?php echo $id?>:
		</div>
		<table class="locationdetails">
			<tr>
				<th>Date of Measurement</th>
				<th>Time of Measurement</th>
				<th>Location 1</th>
				<th>Location 1 Stick Length (cm)</th>
				<th>± (cm)</th>
				<th>Location 1 Shadow (cm)</th>
				<th>± (cm)</th>
				<th>Location 1 Angle</th>
				<th>Location 2</th>
				<th>Location 2 Stick Length (cm)</th>
				<th>± (cm)</th>
				<th>Location 2 Shadow (cm)</th>
				<th>± (cm)</th>
				<th>Location 2 Angle</th>
				<th>Calculated Circumference</th>
				<th>Percent Accurate</th>
			</tr>
		<?php
		foreach($rows as $row){
			$date_of_measurement=$row->date_of_measurement;
			$time_of_measurement=$row->time_of_measurement;
			$location_1=$row->location_1;
			$location_1_stick_length=$row->location_1_stick_length;
			$location_1_stick_length_err=$row->location_1_stick_length_err;
			$location_1_shadow=$row->location_1_shadow;
			$location_1_shadow_err=$row->location_1_shadow_err;
			$location_1_angle=round($row->location_1_angle,2);
			$location_2=$row->location_2;
			$location_2_stick_length=$row->location_2_stick_length;
			$location_2_stick_length_err=$row->location_2_stick_length_err;
			$location_2_shadow=$row->location_2_shadow;
			$location_2_shadow_err=$row->location_2_shadow_err;
			$location_2_angle=round($row->location_2_angle,2);
			$calculated_circumference=round($row->calculated_circumference);
			$percent_accurate=round($row->percent_accurate,3)*100 . "%";

		?>
			<tr>
				<td><?php echo $date_of_measurement?></td>
				<td><?php echo $time_of_measurement?></td>
				<td><?php echo $location_1?></td>
				<td><?php echo $location_1_stick_length?></td>
				<td><?php echo $location_1_stick_length_err?></td>
				<td><?php echo $location_1_shadow?></td>
				<td><?php echo $location_1_shadow_err?></td>
				<td><?php echo $location_1_angle?></td>
				<td><?php echo $location_2?></td>
				<td><?php echo $location_2_stick_length?></td>
				<td><?php echo $location_2_stick_length_err?></td>
				<td><?php echo $location_2_shadow?></td>
				<td><?php echo $location_2_shadow_err?></td>
				<td><?php echo $location_2_angle?></td>
				<td><?php echo $calculated_circumference?></td>
				<td><?php echo $percent_accurate?></td>
			</tr>
		<?php
		}
		?>
		</table>
		<div class="linkheading">
			<a href="data/EratosthenesData.xlsx">Download Data</a>
		</div>
	</div>
	<?php
}else{
	?>
	<div style="padding:20px;">
		Sorry, no data has been uploaded for <?php echo $id?> yet.
	</div>
	<?php
}

?>