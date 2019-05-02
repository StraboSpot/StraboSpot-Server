<?
$id = $_GET['id'];

function dumpVar($var){
	echo "<pre>\n";
	print_r($var);
	echo "\n</pre>";
}

$json = file_get_contents("data/measurements.json");
$rows = json_decode($json);

//dumpVar($rows);

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
			Details for <?=$id?>:
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
		<?
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
				<td><?=$date_of_measurement?></td>
				<td><?=$time_of_measurement?></td>
				<td><?=$location_1?></td>
				<td><?=$location_1_stick_length?></td>
				<td><?=$location_1_stick_length_err?></td>
				<td><?=$location_1_shadow?></td>
				<td><?=$location_1_shadow_err?></td>
				<td><?=$location_1_angle?></td>
				<td><?=$location_2?></td>
				<td><?=$location_2_stick_length?></td>
				<td><?=$location_2_stick_length_err?></td>
				<td><?=$location_2_shadow?></td>
				<td><?=$location_2_shadow_err?></td>
				<td><?=$location_2_angle?></td>
				<td><?=$calculated_circumference?></td>
				<td><?=$percent_accurate?></td>
			</tr>
		<?
		}
		?>
		</table>
		<div class="linkheading">
			<a href="data/EratosthenesData.xlsx">Download Data</a>
		</div>
	</div>
	<?
}else{
	?>
	<div style="padding:20px;">
		Sorry, no data has been uploaded for <?=$id?> yet.
	</div>
	<?
}

?>