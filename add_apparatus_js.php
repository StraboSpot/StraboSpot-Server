<?php
/**
 * File: add_apparatus_js.php
 * Description: Creates new apparatus records
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

header("Content-type: application/javascript");
$pkey = $_GET['pkey'];
$num_sensors = 5;
?>

	var maxsensors = <?php echo $num_sensors?>;

	function validateForm(){
		var error = "";
		var errordelim = "";

		var institute_pkey = $("#institute_pkey").val();
		if(institute_pkey==""){
			error += errordelim + "Institute is required.";
			errordelim = "\n";
		}

		if(error!=""){
			alert(error);
			return false;
		}
	}

	function checkform(){
		var instrumentName = $("#instrument_name").val();
		var instrumentType = $( "#instrument_type" ).val();

		if(instrumentType != "Transmission Electron Microscopy (TEM)" && instrumentType != "Scanning Transmission Electron Microscopy (STEM)" && instrumentType != "Scanning Electron Microscopy (SEM)" && instrumentType != "Electron Microprobe"){
			//clear and hide
			$("#detectordetail").hide();
			$("#detectortype0").val(""); $("#detectormake0").val(""); $("#detectormodel0").val("");
			$("#detectortype1").val(""); $("#detectormake1").val(""); $("#detectormodel1").val(""); $("#detectorrow1").hide();
			$("#detectortype2").val(""); $("#detectormake2").val(""); $("#detectormodel2").val(""); $("#detectorrow2").hide();
			$("#detectortype3").val(""); $("#detectormake3").val(""); $("#detectormodel3").val(""); $("#detectorrow3").hide();
			$("#detectortype4").val(""); $("#detectormake4").val(""); $("#detectormodel4").val(""); $("#detectorrow4").hide();
			$("#detectortype5").val(""); $("#detectormake5").val(""); $("#detectormodel5").val(""); $("#detectorrow5").hide();
			$("#detectortype6").val(""); $("#detectormake6").val(""); $("#detectormodel6").val(""); $("#detectorrow6").hide();
			$("#detectortype7").val(""); $("#detectormake7").val(""); $("#detectormodel7").val(""); $("#detectorrow7").hide();
			$("#detectortype8").val(""); $("#detectormake8").val(""); $("#detectormodel8").val(""); $("#detectorrow8").hide();
			$("#detectortype9").val(""); $("#detectormake9").val(""); $("#detectormodel9").val(""); $("#detectorrow9").hide();
			$("#detectortype10").val(""); $("#detectormake10").val(""); $("#detectormodel10").val(""); $("#detectorrow10").hide();

		}else{
			$("#detectordetail").show();
		}

	}

	$("#instrument_type").change(function() {
		console.log( "Handler for .change() called." );
		checkform();
	});

	$("#instrument_name").keyup(function() {
		console.log( "Handler for .change() called." );
		checkform();
	});

	$("#apparatus_subtype").change(function () {
		setPossibleTestTypeChecks();
	});

	$("#cp_confiningmediumone").change(function () {
		$("#cp_confiningmediumtwo").empty();
		$("#cp_confiningmediumtwo").hide();
		$("#cp_confiningmediumother").val("");
		$("#cp_confiningmediumother").hide();

		var confiningone = $("#cp_confiningmediumone").val();
		if(confiningone!=""){
			$("#cp_confiningmediumtwo").show();
			if(confiningone == "Solid"){
				$('#cp_confiningmediumtwo').append('<option value="">Select...</option>');
				$('#cp_confiningmediumtwo').append('<option value="Salt: NaCl">Salt: NaCl</option>');
				$('#cp_confiningmediumtwo').append('<option value="Salt: KCl">Salt: KCl</option>');
				$('#cp_confiningmediumtwo').append('<option value="Diamond Anvil">Diamond Anvil</option>');
				$('#cp_confiningmediumtwo').append('<option value="Other">Other</option>');
				$("#cp_confiningmediumother").attr("placeholder", "Other Solid...");
			}else if(confiningone == "Liquid"){
				$('#cp_confiningmediumtwo').append('<option value="">Select...</option>');
				$('#cp_confiningmediumtwo').append('<option value="Silicon Oil">Silicon Oil</option>');
				$('#cp_confiningmediumtwo').append('<option value="Hydraulic Oil">Hydraulic Oil</option>');
				$('#cp_confiningmediumtwo').append('<option value="Water">Water</option>');
				$('#cp_confiningmediumtwo').append('<option value="Melt">Melt</option>');
				$('#cp_confiningmediumtwo').append('<option value="Other">Other</option>');
				$("#cp_confiningmediumother").attr("placeholder", "Other Liquid...");
			}else if(confiningone == "Gas"){
				$('#cp_confiningmediumtwo').append('<option value="">Select...</option>');
				$('#cp_confiningmediumtwo').append('<option value="Argon">Argon</option>');
				$('#cp_confiningmediumtwo').append('<option value="Nitrogen">Nitrogen</option>');
				$('#cp_confiningmediumtwo').append('<option value="Other">Other</option>');
				$("#cp_confiningmediumother").attr("placeholder", "Other Gas...");
			}
		}
	});

	$("#cp_confiningmediumtwo").change(function () {
		$("#cp_confiningmediumother").val("");
		var confiningtwo = $("#cp_confiningmediumtwo").val();
		if(confiningtwo=="Other"){
			$("#cp_confiningmediumother").show();
		}else{
			$("#cp_confiningmediumother").hide();
		}
	});

	//set up cp sensor listeners
	<?php
	for($sensor_num = 1; $sensor_num <= $num_sensors; $sensor_num++){
	?>
	$("#cp_sensortype_<?php echo $sensor_num?>").change(function () {
		console.log("cp <?php echo $sensor_num?> changed");
		$("#cp_sensorothertype_<?php echo $sensor_num?>").val("");
		var cpsensortype<?php echo $sensor_num?> = $("#cp_sensortype_<?php echo $sensor_num?>").val();
		if(cpsensortype<?php echo $sensor_num?>=="Other"){
			$("#cp_sensorothertype_<?php echo $sensor_num?>").show();
		}else{
			$("#cp_sensorothertype_<?php echo $sensor_num?>").hide();
		}
	});
	<?php
	}
	?>

	var cp_sensor_num = 2;
	function add_cp_sensor(){
		console.log("adding sensor");
		for (i = 1; i <= maxsensors; i++) {
			console.log("checking sensor "+i);
			if(!$("#cp_sensor_wrapper_" + i).is(':visible')){
				$("#cp_sensor_wrapper_" + i).show();
				cp_sensor_num++;
				if(cp_sensor_num > maxsensors){
					$("#cpaddsensorbutton").hide();
				}
				if(i==maxsensors){
					$("#cpaddsensorbutton").hide();
				}
				break;
			}
		}

	}

	//set up cp removers
	<?php
	for($sensor_num = 1; $sensor_num <= $num_sensors; $sensor_num++){
	?>
	function removecpsensor<?php echo $sensor_num?>(){
		var r = confirm("Are you sure you want to remove this sensor?");
		if(r==true){
		cpSensorFile<?php echo $sensor_num?>DropZone.removeAllFiles();
			$("#cp_sensortype_<?php echo $sensor_num?>").val("");
			$("#cp_sensorothertype_<?php echo $sensor_num?>").val("");
			$("#cp_sensorothertype_<?php echo $sensor_num?>").hide();
			$("#cp_sensorlocation_<?php echo $sensor_num?>").val("");
			$("#cp_sensorcalibration_<?php echo $sensor_num?>").val("");
			$("#cp_sensornotes_<?php echo $sensor_num?>").val("");
			$("#cp_sensor_wrapper_<?php echo $sensor_num?>").hide();
		}
	}
	<?php
	}
	?>

	//set up temp sensor listeners
	<?php
	for($sensor_num = 1; $sensor_num <= $num_sensors; $sensor_num++){
	?>
	$("#temp_sensortype_<?php echo $sensor_num?>").change(function () {
		console.log("temp <?php echo $sensor_num?> changed");
		$("#temp_othersensortype_<?php echo $sensor_num?>").val("");

		$("#temp_thermocoupletypespan_<?php echo $sensor_num?>").hide();
		$("#temp_thermocoupletype_<?php echo $sensor_num?>").val("");

		$("#temp_thermocouplesubtypespan_<?php echo $sensor_num?>").hide();
		$("#temp_thermocouplesubtype_<?php echo $sensor_num?>").empty();

		$("#temp_rtdspan_<?php echo $sensor_num?>").hide();
		$("#temp_rtdtype_<?php echo $sensor_num?>").val("");

		$("#temp_thermistortypespan_<?php echo $sensor_num?>").hide();
		$("#temp_thermistortype_<?php echo $sensor_num?>").val("");

		var tempsensortype<?php echo $sensor_num?> = $("#temp_sensortype_<?php echo $sensor_num?>").val();
		if(tempsensortype<?php echo $sensor_num?>=="Other"){
			$("#temp_othersensortypespan_<?php echo $sensor_num?>").show();
			$("#temp_thermocoupletypespan_<?php echo $sensor_num?>").val("");
			$("#temp_thermocoupletypespan_<?php echo $sensor_num?>").hide();
		}else if(tempsensortype<?php echo $sensor_num?>=="Thermocouple"){
			$("#temp_othersensortypespan_<?php echo $sensor_num?>").hide();
			$("#temp_othersensortype_<?php echo $sensor_num?>").val("");
			$("#temp_thermocoupletypespan_<?php echo $sensor_num?>").show();
		}else if(tempsensortype<?php echo $sensor_num?>=="Resistance Temperature Detector (RTD)"){
			$("#temp_rtdspan_<?php echo $sensor_num?>").show();
		}else if(tempsensortype<?php echo $sensor_num?>=="Thermistor"){
			$("#temp_thermistortypespan_<?php echo $sensor_num?>").show();
		}else{
			$("#temp_othersensortypespan_<?php echo $sensor_num?>").hide();
			$("#temp_othersensortype_<?php echo $sensor_num?>").val("");
			$("#temp_thermocoupletype_<?php echo $sensor_num?>").val("");
			$("#temp_thermocoupletypespan_<?php echo $sensor_num?>").hide();
		}
	});
	<?php
	}
	?>

	//set up temp thermocouple type listeners
	<?php
	for($sensor_num = 1; $sensor_num <= $num_sensors; $sensor_num++){
	?>
	$("#temp_thermocoupletype_<?php echo $sensor_num?>").change(function () {
		var thermocoupletype<?php echo $sensor_num?> = $("#temp_thermocoupletype_<?php echo $sensor_num?>").val();
		console.log(thermocoupletype<?php echo $sensor_num?>);

		$("#temp_thermocouplesubtype_<?php echo $sensor_num?>").empty();
		$("#temp_thermocouplesubtypespan_<?php echo $sensor_num?>").hide();

		if(thermocoupletype<?php echo $sensor_num?> == "Nickel Alloy"){
			$("#temp_thermocouplesubtypespan_<?php echo $sensor_num?>").show();
			$("#temp_thermocouplesubtype_<?php echo $sensor_num?>").append('<option value="">Select...</option>');
			$("#temp_thermocouplesubtype_<?php echo $sensor_num?>").append('<option value="Type E">Type E</option>');
			$("#temp_thermocouplesubtype_<?php echo $sensor_num?>").append('<option value="Type J">Type J</option>');
			$("#temp_thermocouplesubtype_<?php echo $sensor_num?>").append('<option value="Type K">Type K</option>');
			$("#temp_thermocouplesubtype_<?php echo $sensor_num?>").append('<option value="Type M">Type M</option>');
			$("#temp_thermocouplesubtype_<?php echo $sensor_num?>").append('<option value="Type N">Type N</option>');
			$("#temp_thermocouplesubtype_<?php echo $sensor_num?>").append('<option value="Type T">Type T</option>');
		} else if(thermocoupletype<?php echo $sensor_num?> == "Platinum/Rhodium-Alloy"){
			$("#temp_thermocouplesubtypespan_<?php echo $sensor_num?>").show();
			$("#temp_thermocouplesubtype_<?php echo $sensor_num?>").append('<option value="">Select...</option>');
			$("#temp_thermocouplesubtype_<?php echo $sensor_num?>").append('<option value="Type B">Type B</option>');
			$("#temp_thermocouplesubtype_<?php echo $sensor_num?>").append('<option value="Type R">Type R</option>');
			$("#temp_thermocouplesubtype_<?php echo $sensor_num?>").append('<option value="Type S">Type S</option>');
		} else if(thermocoupletype<?php echo $sensor_num?> == "Tungsten/Rhenium-Alloy"){
			$("#temp_thermocouplesubtypespan_<?php echo $sensor_num?>").show();
			$("#temp_thermocouplesubtype_<?php echo $sensor_num?>").append('<option value="">Select...</option>');
			$("#temp_thermocouplesubtype_<?php echo $sensor_num?>").append('<option value="Type C">Type C</option>');
			$("#temp_thermocouplesubtype_<?php echo $sensor_num?>").append('<option value="Type D">Type D</option>');
			$("#temp_thermocouplesubtype_<?php echo $sensor_num?>").append('<option value="Type G">Type G</option>');
		} else {

		}
	})
	<?php
	}
	?>

	var temp_sensor_num = 2;
	function add_temp_sensor(){
		console.log("adding sensor");
		for (i = 1; i <= maxsensors; i++) {
			console.log("checking sensor "+i);
			if(!$("#temp_sensor_wrapper_" + i).is(':visible')){
				$("#temp_sensor_wrapper_" + i).show();
				temp_sensor_num++;
				if(temp_sensor_num > maxsensors){
					$("#tempaddsensorbutton").hide();
				}
				if(i==maxsensors){
					$("#tempaddsensorbutton").hide();
				}
				break;
			}
		}

	}

	//set up temp removers
	<?php
	for($sensor_num = 1; $sensor_num <= $num_sensors; $sensor_num++){
	?>
	function removetempsensor<?php echo $sensor_num?>(){
		var r = confirm("Are you sure you want to remove this sensor?");
		if(r==true){
		tempSensorFile<?php echo $sensor_num?>DropZone.removeAllFiles();
			$("#temp_sensortype_<?php echo $sensor_num?>").val("");
			$("#temp_sensorothertype_<?php echo $sensor_num?>").val("");
			$("#temp_sensorothertype_<?php echo $sensor_num?>").hide();
			$("#temp_sensorlocation_<?php echo $sensor_num?>").val("");
			$("#temp_sensorcalibration_<?php echo $sensor_num?>").val("");
			$("#temp_sensornotes_<?php echo $sensor_num?>").val("");
			$("#temp_thermocoupletype_<?php echo $sensor_num?>").val("");
			$("#temp_thermocoupletypespan_<?php echo $sensor_num?>").hide();
			$("#temp_sensor_wrapper_<?php echo $sensor_num?>").hide();
			$("#temp_thermocouplesubtype_<?php echo $sensor_num?>").val("");
		}
	}
	<?php
	}
	?>

	//set up dispax sensor listeners
	<?php
	for($sensor_num = 1; $sensor_num <= $num_sensors; $sensor_num++){
	?>
	$("#dispax_sensortype_<?php echo $sensor_num?>").change(function () {
		console.log("dispax <?php echo $sensor_num?> changed");
		$("#dispax_sensorothertype_<?php echo $sensor_num?>").val("");
		var dispaxsensortype<?php echo $sensor_num?> = $("#dispax_sensortype_<?php echo $sensor_num?>").val();
		if(dispaxsensortype<?php echo $sensor_num?>=="Other"){
			$("#dispax_othersensortypespan_<?php echo $sensor_num?>").show();
		}else{
			$("#dispax_othersensortypespan_<?php echo $sensor_num?>").hide();
			$("#dispax_othersensortype_<?php echo $sensor_num?>").val("");
		}
	});
	<?php
	}
	?>

	var dispax_sensor_num = 2;
	function add_dispax_sensor(){
		console.log("adding sensor");
		for (i = 1; i <= maxsensors; i++) {
			console.log("checking sensor "+i);
			if(!$("#dispax_sensor_wrapper_" + i).is(':visible')){
				$("#dispax_sensor_wrapper_" + i).show();
				dispax_sensor_num++;
				if(dispax_sensor_num > maxsensors){
					$("#dispaxaddsensorbutton").hide();
				}
				if(i==maxsensors){
					$("#dispaxaddsensorbutton").hide();
				}
				break;
			}
		}

	}

	//set up dispax removers
	<?php
	for($sensor_num = 1; $sensor_num <= $num_sensors; $sensor_num++){
	?>
	function removedispaxsensor<?php echo $sensor_num?>(){
		var r = confirm("Are you sure you want to remove this sensor?");
		if(r==true){
		dispaxSensorFile<?php echo $sensor_num?>DropZone.removeAllFiles();
			$("#dispax_sensortype_<?php echo $sensor_num?>").val("");
			$("#dispax_sensorothertype_<?php echo $sensor_num?>").val("");
			$("#dispax_sensorothertype_<?php echo $sensor_num?>").hide();
			$("#dispax_sensorlocation_<?php echo $sensor_num?>").val("");
			$("#dispax_sensorcalibration_<?php echo $sensor_num?>").val("");
			$("#dispax_sensornotes_<?php echo $sensor_num?>").val("");
			$("#dispax_sensor_wrapper_<?php echo $sensor_num?>").hide();
		}
	}
	<?php
	}
	?>

	//set up disprot sensor listeners
	<?php
	for($sensor_num = 1; $sensor_num <= $num_sensors; $sensor_num++){
	?>
	$("#disprot_sensortype_<?php echo $sensor_num?>").change(function () {
		console.log("disprot <?php echo $sensor_num?> changed");
		$("#disprot_sensorothertype_<?php echo $sensor_num?>").val("");
		var disprotsensortype<?php echo $sensor_num?> = $("#disprot_sensortype_<?php echo $sensor_num?>").val();
		if(disprotsensortype<?php echo $sensor_num?>=="Other"){
			$("#disprot_othersensortypespan_<?php echo $sensor_num?>").show();
		}else{
			$("#disprot_othersensortypespan_<?php echo $sensor_num?>").hide();
			$("#disprot_othersensortype_<?php echo $sensor_num?>").val("");
		}
	});
	<?php
	}
	?>

	var disprot_sensor_num = 2;
	function add_disprot_sensor(){
		console.log("adding sensor");
		for (i = 1; i <= maxsensors; i++) {
			console.log("checking sensor "+i);
			if(!$("#disprot_sensor_wrapper_" + i).is(':visible')){
				$("#disprot_sensor_wrapper_" + i).show();
				disprot_sensor_num++;
				if(disprot_sensor_num > maxsensors){
					$("#disprotaddsensorbutton").hide();
				}
				if(i==maxsensors){
					$("#disprotaddsensorbutton").hide();
				}
				break;
			}
		}

	}

	//set up disprot removers
	<?php
	for($sensor_num = 1; $sensor_num <= $num_sensors; $sensor_num++){
	?>
	function removedisprotsensor<?php echo $sensor_num?>(){
		var r = confirm("Are you sure you want to remove this sensor?");
		if(r==true){
		disprotSensorFile<?php echo $sensor_num?>DropZone.removeAllFiles();
			$("#disprot_sensortype_<?php echo $sensor_num?>").val("");
			$("#disprot_othersensortype_<?php echo $sensor_num?>").val("");
			$("#disprot_othersensortypespan_<?php echo $sensor_num?>").hide();
			$("#disprot_sensorlocation_<?php echo $sensor_num?>").val("");
			$("#disprot_sensorcalibration_<?php echo $sensor_num?>").val("");
			$("#disprot_sensornotes_<?php echo $sensor_num?>").val("");
			$("#disprot_sensor_wrapper_<?php echo $sensor_num?>").hide();
		}
	}
	<?php
	}
	?>

	//set up disprot infinite checkbox
	$("#disprot_infinitemaxdisplacement").change(function () {
		if($("#disprot_infinitemaxdisplacement").is(':checked')){
			$("#disprot_maxdisplacement").val("");
			$("#disprot_maxdisplacement_unit").val("cm");
			$("#disprot_maxdisplacement").attr('disabled','disabled');
			$("#disprot_maxdisplacement_unit").attr('disabled','disabled');
		}else{
			console.log("unchecked");
			$("#disprot_maxdisplacement").removeAttr('disabled');
			$("#disprot_maxdisplacement_unit").removeAttr('disabled');
		}
	});

	//set up dispvol sensor listeners
	<?php
	for($sensor_num = 1; $sensor_num <= $num_sensors; $sensor_num++){
	?>
	$("#dispvol_sensortype_<?php echo $sensor_num?>").change(function () {
		console.log("dispvol <?php echo $sensor_num?> changed");
		$("#dispvol_sensorothertype_<?php echo $sensor_num?>").val("");
		var dispvolsensortype<?php echo $sensor_num?> = $("#dispvol_sensortype_<?php echo $sensor_num?>").val();
		if(dispvolsensortype<?php echo $sensor_num?>=="Other"){
			$("#dispvol_othersensortypespan_<?php echo $sensor_num?>").show();
		}else{
			$("#dispvol_othersensortypespan_<?php echo $sensor_num?>").hide();
			$("#dispvol_othersensortype_<?php echo $sensor_num?>").val("");
		}
	});
	<?php
	}
	?>

	var dispvol_sensor_num = 2;
	function add_dispvol_sensor(){
		console.log("adding sensor");
		for (i = 1; i <= maxsensors; i++) {
			console.log("checking sensor "+i);
			if(!$("#dispvol_sensor_wrapper_" + i).is(':visible')){
				$("#dispvol_sensor_wrapper_" + i).show();
				dispvol_sensor_num++;
				if(dispvol_sensor_num > maxsensors){
					$("#dispvoladdsensorbutton").hide();
				}
				if(i==maxsensors){
					$("#dispvoladdsensorbutton").hide();
				}
				break;
			}
		}

	}

	//set up dispvol removers
	<?php
	for($sensor_num = 1; $sensor_num <= $num_sensors; $sensor_num++){
	?>
	function removedispvolsensor<?php echo $sensor_num?>(){
		var r = confirm("Are you sure you want to remove this sensor?");
		if(r==true){
		dispvolSensorFile<?php echo $sensor_num?>DropZone.removeAllFiles();
			$("#dispvol_sensortype_<?php echo $sensor_num?>").val("");
			$("#dispvol_othersensortype_<?php echo $sensor_num?>").val("");
			$("#dispvol_othersensortypespan_<?php echo $sensor_num?>").hide();
			$("#dispvol_sensorlocation_<?php echo $sensor_num?>").val("");
			$("#dispvol_sensorcalibration_<?php echo $sensor_num?>").val("");
			$("#dispvol_sensornotes_<?php echo $sensor_num?>").val("");
			$("#dispvol_sensor_wrapper_<?php echo $sensor_num?>").hide();
		}
	}
	<?php
	}
	?>

	//set up dispshear sensor listeners
	<?php
	for($sensor_num = 1; $sensor_num <= $num_sensors; $sensor_num++){
	?>
	$("#dispshear_sensortype_<?php echo $sensor_num?>").change(function () {
		console.log("dispshear <?php echo $sensor_num?> changed");
		$("#dispshear_sensorothertype_<?php echo $sensor_num?>").val("");
		var dispshearsensortype<?php echo $sensor_num?> = $("#dispshear_sensortype_<?php echo $sensor_num?>").val();
		if(dispshearsensortype<?php echo $sensor_num?>=="Other"){
			$("#dispshear_othersensortypespan_<?php echo $sensor_num?>").show();
		}else{
			$("#dispshear_othersensortypespan_<?php echo $sensor_num?>").hide();
			$("#dispshear_othersensortype_<?php echo $sensor_num?>").val("");
		}
	});
	<?php
	}
	?>

	var dispshear_sensor_num = 2;
	function add_dispshear_sensor(){
		console.log("adding sensor");
		for (i = 1; i <= maxsensors; i++) {
			console.log("checking sensor "+i);
			if(!$("#dispshear_sensor_wrapper_" + i).is(':visible')){
				$("#dispshear_sensor_wrapper_" + i).show();
				dispshear_sensor_num++;
				if(dispshear_sensor_num > maxsensors){
					$("#dispshearaddsensorbutton").hide();
				}
				if(i==maxsensors){
					$("#dispshearaddsensorbutton").hide();
				}
				break;
			}
		}

	}

	//set up dispshear removers
	<?php
	for($sensor_num = 1; $sensor_num <= $num_sensors; $sensor_num++){
	?>
	function removedispshearsensor<?php echo $sensor_num?>(){
		var r = confirm("Are you sure you want to remove this sensor?");
		if(r==true){
		dispshearSensorFile<?php echo $sensor_num?>DropZone.removeAllFiles();
			$("#dispshear_sensortype_<?php echo $sensor_num?>").val("");
			$("#dispshear_othersensortype_<?php echo $sensor_num?>").val("");
			$("#dispshear_othersensortypespan_<?php echo $sensor_num?>").hide();
			$("#dispshear_sensorlocation_<?php echo $sensor_num?>").val("");
			$("#dispshear_sensorcalibration_<?php echo $sensor_num?>").val("");
			$("#dispshear_sensornotes_<?php echo $sensor_num?>").val("");
			$("#dispshear_sensor_wrapper_<?php echo $sensor_num?>").hide();
		}
	}
	<?php
	}
	?>

	//set up force sensor listeners
	<?php
	for($sensor_num = 1; $sensor_num <= $num_sensors; $sensor_num++){
	?>
	$("#force_sensortype_<?php echo $sensor_num?>").change(function () {
		console.log("force <?php echo $sensor_num?> changed");
		$("#force_sensorothertype_<?php echo $sensor_num?>").val("");
		var forcesensortype<?php echo $sensor_num?> = $("#force_sensortype_<?php echo $sensor_num?>").val();
		if(forcesensortype<?php echo $sensor_num?>=="Other"){
			$("#force_othersensortypespan_<?php echo $sensor_num?>").show();
		}else{
			$("#force_othersensortypespan_<?php echo $sensor_num?>").hide();
			$("#force_othersensortype_<?php echo $sensor_num?>").val("");
		}
	});
	<?php
	}
	?>

	var force_sensor_num = 2;
	function add_force_sensor(){
		console.log("adding sensor");
		for (i = 1; i <= maxsensors; i++) {
			console.log("checking sensor "+i);
			if(!$("#force_sensor_wrapper_" + i).is(':visible')){
				$("#force_sensor_wrapper_" + i).show();
				force_sensor_num++;
				if(force_sensor_num > maxsensors){
					$("#forceaddsensorbutton").hide();
				}
				if(i==maxsensors){
					$("#forceaddsensorbutton").hide();
				}
				break;
			}
		}

	}

	//set up force removers
	<?php
	for($sensor_num = 1; $sensor_num <= $num_sensors; $sensor_num++){
	?>
	function removeforcesensor<?php echo $sensor_num?>(){
		var r = confirm("Are you sure you want to remove this sensor?");
		if(r==true){
		forceSensorFile<?php echo $sensor_num?>DropZone.removeAllFiles();
			$("#force_sensortype_<?php echo $sensor_num?>").val("");
			$("#force_othersensortype_<?php echo $sensor_num?>").val("");
			$("#force_othersensortypespan_<?php echo $sensor_num?>").hide();
			$("#force_sensorlocation_<?php echo $sensor_num?>").val("");
			$("#force_sensorcalibration_<?php echo $sensor_num?>").val("");
			$("#force_sensornotes_<?php echo $sensor_num?>").val("");
			$("#force_sensor_wrapper_<?php echo $sensor_num?>").hide();
		}
	}
	<?php
	}
	?>

	//set up torque sensor listeners
	<?php
	for($sensor_num = 1; $sensor_num <= $num_sensors; $sensor_num++){
	?>
	$("#torque_sensortype_<?php echo $sensor_num?>").change(function () {
		console.log("torque <?php echo $sensor_num?> changed");
		$("#torque_sensorothertype_<?php echo $sensor_num?>").val("");
		var torquesensortype<?php echo $sensor_num?> = $("#torque_sensortype_<?php echo $sensor_num?>").val();
		if(torquesensortype<?php echo $sensor_num?>=="Other"){
			$("#torque_othersensortypespan_<?php echo $sensor_num?>").show();
		}else{
			$("#torque_othersensortypespan_<?php echo $sensor_num?>").hide();
			$("#torque_othersensortype_<?php echo $sensor_num?>").val("");
		}
		if(torquesensortype<?php echo $sensor_num?>=="Strain Gauges"){
			$("#torque_numberofstraingaugesspan_<?php echo $sensor_num?>").show();
		}else{
			$("#torque_numberofstraingaugesspan_<?php echo $sensor_num?>").hide();
			$("#torque_numberofstraingauges_<?php echo $sensor_num?>").val("");
		}

	});
	<?php
	}
	?>

	<?php
	for($sensor_num = 1; $sensor_num <= $num_sensors; $sensor_num++){
	?>
	$("#torque_geometryofelasticelement_<?php echo $sensor_num?>").change(function () {
		console.log("torque <?php echo $sensor_num?> changed");
		$("#torque_othergeometryofelasticelement_<?php echo $sensor_num?>").val("");
		var geometryofelasticelement<?php echo $sensor_num?> = $("#torque_geometryofelasticelement_<?php echo $sensor_num?>").val();
		if(geometryofelasticelement<?php echo $sensor_num?>=="Other"){
			$("#torque_othergeometryofelasticelementspan_<?php echo $sensor_num?>").show();
		}else{
			$("#torque_othergeometryofelasticelementspan_<?php echo $sensor_num?>").hide();
			$("#torque_othergeometryofelasticelement_<?php echo $sensor_num?>").val("");
		}
	});
	<?php
	}
	?>

	var torque_sensor_num = 2;
	function add_torque_sensor(){
		console.log("adding sensor");
		for (i = 1; i <= maxsensors; i++) {
			console.log("checking sensor "+i);
			if(!$("#torque_sensor_wrapper_" + i).is(':visible')){
				$("#torque_sensor_wrapper_" + i).show();
				torque_sensor_num++;
				if(torque_sensor_num > maxsensors){
					$("#torqueaddsensorbutton").hide();
				}
				if(i==maxsensors){
					$("#torqueaddsensorbutton").hide();
				}
				break;
			}
		}

	}

	//set up torque removers
	<?php
	for($sensor_num = 1; $sensor_num <= $num_sensors; $sensor_num++){
	?>
	function removetorquesensor<?php echo $sensor_num?>(){
		var r = confirm("Are you sure you want to remove this sensor?");
		if(r==true){
		torqueSensorFile<?php echo $sensor_num?>DropZone.removeAllFiles();
			$("#torque_geometryofelasticelement_<?php echo $sensor_num?>").val("");
			$("#torque_othergeometryofelasticelement_<?php echo $sensor_num?>").val("");
			$("#torque_othergeometryofelasticelementspan_<?php echo $sensor_num?>").hide();

			$("#torque_numberofstraingaugesspan_<?php echo $sensor_num?>").hide();
			$("#torque_numberofstraingauges_<?php echo $sensor_num?>").val("");

			$("#torque_sensortype_<?php echo $sensor_num?>").val("");
			$("#torque_othersensortype_<?php echo $sensor_num?>").val("");
			$("#torque_othersensortypespan_<?php echo $sensor_num?>").hide();

			$("#torque_sensorlocation_<?php echo $sensor_num?>").val("");
			$("#torque_sensorcalibration_<?php echo $sensor_num?>").val("");
			$("#torque_sensornotes_<?php echo $sensor_num?>").val("");
		}
	}
	<?php
	}
	?>

	//set up pore sensor listeners
	<?php
	for($sensor_num = 1; $sensor_num <= $num_sensors; $sensor_num++){
	?>
	$("#pore_sensortype_<?php echo $sensor_num?>").change(function () {
		console.log("pore <?php echo $sensor_num?> changed");
		$("#pore_sensorothertype_<?php echo $sensor_num?>").val("");
		var poresensortype<?php echo $sensor_num?> = $("#pore_sensortype_<?php echo $sensor_num?>").val();
		if(poresensortype<?php echo $sensor_num?>=="Other"){
			$("#pore_othersensortypespan_<?php echo $sensor_num?>").show();
		}else{
			$("#pore_othersensortypespan_<?php echo $sensor_num?>").hide();
			$("#pore_othersensortype_<?php echo $sensor_num?>").val("");
		}
	});
	<?php
	}
	?>

	var pore_sensor_num = 2;
	function add_pore_sensor(){
		console.log("adding sensor");
		for (i = 1; i <= maxsensors; i++) {
			console.log("checking sensor "+i);
			if(!$("#pore_sensor_wrapper_" + i).is(':visible')){
				$("#pore_sensor_wrapper_" + i).show();
				pore_sensor_num++;
				if(pore_sensor_num > maxsensors){
					$("#poreaddsensorbutton").hide();
				}
				if(i==maxsensors){
					$("#poreaddsensorbutton").hide();
				}
				break;
			}
		}

	}

	//set up pore removers
	<?php
	for($sensor_num = 1; $sensor_num <= $num_sensors; $sensor_num++){
	?>
	function removeporesensor<?php echo $sensor_num?>(){
		var r = confirm("Are you sure you want to remove this sensor?");
		if(r==true){
		poreSensorFile<?php echo $sensor_num?>DropZone.removeAllFiles();
			$("#pore_sensortype_<?php echo $sensor_num?>").val("");
			$("#pore_othersensortype_<?php echo $sensor_num?>").val("");
			$("#pore_othersensortypespan_<?php echo $sensor_num?>").hide();
			$("#pore_sensorlocation_<?php echo $sensor_num?>").val("");
			$("#pore_sensorcalibration_<?php echo $sensor_num?>").val("");
			$("#pore_sensornotes_<?php echo $sensor_num?>").val("");
			$("#pore_sensor_wrapper_<?php echo $sensor_num?>").hide();
		}
	}
	<?php
	}
	?>

//electrical_electrodetype
//electrical_otherelectrodetype
	$("#electrical_electrodetype").change(function () {
		console.log("electrical_electrodetype changed");
		$("#electrical_otherelectrodetype").val("");
		var electricalelectrodetype = $("#electrical_electrodetype").val();
		if(electricalelectrodetype == "Other Electrode"){
			$("#electrical_otherelectrodetype").show();
		}else{
			$("#electrical_otherelectrodetype").hide();
			$("#electrical_otherelectrodetype").val("");
		}
	});

	$("#checkbox_confiningpressure").change(function () {
		if($("#checkbox_confiningpressure").is(':checked')){
			$("#confiningpressure_wrapper").show();
		}else{
			$("#confiningpressure_wrapper").hide();
		}
	});

	$("#checkbox_temperaturefurnacecryostat").change(function () {
		if($("#checkbox_temperaturefurnacecryostat").is(':checked')){
			$("#temperaturefurnacecryostat_wrapper").show();
		}else{
			$("#temperaturefurnacecryostat_wrapper").hide();
		}
	});

	$("#checkbox_displacement").change(function () {
		if($("#checkbox_displacement").is(':checked')){
			$("#displacement_wrapper").show();
		}else{
			$("#displacement_wrapper").hide();
		}
	});

	//sub-displacement:
	$("#checkbox_dispax").change(function () {
		if($("#checkbox_dispax").is(':checked')){
			$("#dispax_wrapper").show();
		}else{
			$("#dispax_wrapper").hide();
		}
	});

	$("#checkbox_disprot").change(function () {
		if($("#checkbox_disprot").is(':checked')){
			$("#disprot_wrapper").show();
		}else{
			$("#disprot_wrapper").hide();
		}
	});

	$("#checkbox_dispvol").change(function () {
		if($("#checkbox_dispvol").is(':checked')){
			$("#dispvol_wrapper").show();
		}else{
			$("#dispvol_wrapper").hide();
		}
	});

	$("#checkbox_dispshear").change(function () {
		if($("#checkbox_dispshear").is(':checked')){
			$("#dispshear_wrapper").show();
		}else{
			$("#dispshear_wrapper").hide();
		}
	});

	$("#checkbox_force").change(function () {
		if($("#checkbox_force").is(':checked')){
			$("#force_wrapper").show();
		}else{
			$("#force_wrapper").hide();
		}
	});

	$("#checkbox_torque").change(function () {
		if($("#checkbox_torque").is(':checked')){
			$("#torque_wrapper").show();
		}else{
			$("#torque_wrapper").hide();
		}
	});

	$("#checkbox_porefluids").change(function () {
		if($("#checkbox_porefluids").is(':checked')){
			$("#porefluids_wrapper").show();
		}else{
			$("#porefluids_wrapper").hide();
		}
	});

	$("#checkbox_acousticemissions").change(function () {
		if($("#checkbox_acousticemissions").is(':checked')){
			$("#acousticemissions_wrapper").show();
		}else{
			$("#acousticemissions_wrapper").hide();
		}
	});

	$("#checkbox_elasticwave").change(function () {
		if($("#checkbox_elasticwave").is(':checked')){
			$("#elasticwave_wrapper").show();
		}else{
			$("#elasticwave_wrapper").hide();
		}
	});

	$("#checkbox_electricalconductivity").change(function () {
		if($("#checkbox_electricalconductivity").is(':checked')){
			$("#electricalconductivity_wrapper").show();
		}else{
			$("#electricalconductivity_wrapper").hide();
		}
	});

	$("#apparatus_type").change(function () {

		setPossibleTestTypeChecks();

		$('#apparatus_subtype').empty();

		var selectedapparatustype = $("#apparatus_type").val();

		if(selectedapparatustype != ""){

			$('#apparatus_subtype').append('<option value="">Select...</option>');

			if(selectedapparatustype == "Hydrostatic Loading Apparatuses"){
				$('#apparatus_subtype_wrapper').show();
				$('#apparatus_subtype').append('<option value="Multi Anvil Cell">Multi Anvil Cell</option>');
				$('#apparatus_subtype').append('<option value="Diamond Anvil Cell">Diamond Anvil Cell</option>');
				$('#apparatus_subtype').append('<option value="Piston Cylinder">Piston Cylinder</option>');
				$('#apparatus_subtype').append('<option value="Other Hydrostatic Loading Apparatus">Other Hydrostatic Loading Apparatus</option>');
			}else if(selectedapparatustype == "Uniaxial Apparatus"){
				$('#apparatus_subtype_wrapper').show();
				$('#apparatus_subtype').append('<option value="Dead Load Creep Apparatus">Dead Load Creep Apparatus</option>');
				$('#apparatus_subtype').append('<option value="Other Uniaxial Apparatus">Other Uniaxial Apparatus</option>');
			}else if(selectedapparatustype == "Biaxial Apparatus"){
				$('#apparatus_subtype_wrapper').show();
				$('#apparatus_subtype').append('<option value="Biaxial Direct (and Double Direct) Shear Apparatus">Biaxial Direct (and Double Direct) Shear Apparatus</option>');
				$('#apparatus_subtype').append('<option value="Shear Box">Shear Box</option>');
				$('#apparatus_subtype').append('<option value="Other Biaxial Apparatus">Other Biaxial Apparatus</option>');
			}else if(selectedapparatustype == "Triaxial (Conventional) Apparatus"){
				$('#apparatus_subtype_wrapper').show();
				$('#apparatus_subtype').append('<option value="Paterson Apparatus">Paterson Apparatus</option>');
				$('#apparatus_subtype').append('<option value="Griggs Apparatus">Griggs Apparatus</option>');
				$('#apparatus_subtype').append('<option value="Heard Apparatus">Heard Apparatus</option>');
				$('#apparatus_subtype').append('<option value="D-DIA">D-DIA</option>');
				$('#apparatus_subtype').append('<option value="Paris-Edinburgh Rig">Paris-Edinburgh Rig</option>');
				$('#apparatus_subtype').append('<option value="Other Gas Medium Apparatus">Other Gas Medium Apparatus</option>');
				$('#apparatus_subtype').append('<option value="Other Liquid Medium Apparatus">Other Liquid Medium Apparatus</option>');
				$('#apparatus_subtype').append('<option value="Other Solid Medium Apparatus">Other Solid Medium Apparatus</option>');
			}else if(selectedapparatustype == "True Triaxial Apparatus"){
				$('#apparatus_subtype_wrapper').hide();
			}else if(selectedapparatustype == "Rotary Shear Apparatus"){
				$('#apparatus_subtype_wrapper').show();
				$('#apparatus_subtype').append('<option value="Rotary Shear Friction Apparatus">Rotary Shear Friction Apparatus</option>');
				$('#apparatus_subtype').append('<option value="Double Torsion Apparatus">Double Torsion Apparatus</option>');
				$('#apparatus_subtype').append('<option value="Large Volume Torsion Apparatus (LVT)">Large Volume Torsion Apparatus (LVT)</option>');
				$('#apparatus_subtype').append('<option value="Rheometer">Rheometer</option>');
				$('#apparatus_subtype').append('<option value="Rotational Drickamer Apparatus (RDA)">Rotational Drickamer Apparatus (RDA)</option>');
				$('#apparatus_subtype').append('<option value="Other Rotary Shear Apparatus">Other Rotary Shear Apparatus</option>');
			}else if(selectedapparatustype == "Split Hopkinson Pressure Bar"){
				$('#apparatus_subtype_wrapper').hide();
			}else if(selectedapparatustype == "Indenter"){
				$('#apparatus_subtype_wrapper').show();
				$('#apparatus_subtype').append('<option value="Vickers Hardness Tester">Vickers Hardness Tester</option>');
				$('#apparatus_subtype').append('<option value="Chevron Notch Test">Chevron Notch Test</option>');
				$('#apparatus_subtype').append('<option value="Indentation Cell">Indentation Cell</option>');
				$('#apparatus_subtype').append('<option value="Point Load">Point Load</option>');
				$('#apparatus_subtype').append('<option value="Creep Cell">Creep Cell</option>');
			}else if(selectedapparatustype == "Nanoindenter"){
				$('#apparatus_subtype_wrapper').show();
				$('#apparatus_subtype').append('<option value="Cube corner">Cube corner</option>');
				$('#apparatus_subtype').append('<option value="Bercovitch">Bercovitch</option>');
				$('#apparatus_subtype').append('<option value="Spherical">Spherical</option>');
				$('#apparatus_subtype').append('<option value="Micropillar">Micropillar</option>');
			}else if(selectedapparatustype == "Load Stamp"){
				$('#apparatus_subtype_wrapper').hide();
			}else if(selectedapparatustype == "Viscosimeter"){
				$('#apparatus_subtype_wrapper').hide();
			}

		}else{
			$('#apparatus_subtype_wrapper').hide();
		}
	});

	function setPossibleTestTypeChecks() {
		$( "#checkbox_hip" ).prop( "checked", false );
		$( "#checkbox_synthesis" ).prop( "checked", false );
		$( "#checkbox_depositionevaporation" ).prop( "checked", false );
		$( "#checkbox_indentationcreep" ).prop( "checked", false );
		$( "#checkbox_hardness" ).prop( "checked", false );
		$( "#checkbox_permeability" ).prop( "checked", false );
		$( "#checkbox_resistivity" ).prop( "checked", false );
		$( "#checkbox_simpleshear" ).prop( "checked", false );
		$( "#checkbox_rotaryshear" ).prop( "checked", false );
		$( "#checkbox_pureshear" ).prop( "checked", false );
		$( "#checkbox_constantstress" ).prop( "checked", false );
		$( "#checkbox_constantrate" ).prop( "checked", false );
		$( "#checkbox_loadingunloading" ).prop( "checked", false );
		$( "#checkbox_stepping" ).prop( "checked", false );
		$( "#checkbox_extension" ).prop( "checked", false );

		var selectedapparatustype = $("#apparatus_type").val();
		var selectedapparatussubtype = $('#apparatus_subtype').val();

		if(selectedapparatustype == "Load Stamp"){

		}else if(selectedapparatustype == "Split Hopkinson Pressure Bar"){
			$( "#checkbox_extension" ).prop( "checked", true );

		}else if(selectedapparatustype == "Viscosimeter"){

		}else if(selectedapparatussubtype == "Paterson Apparatus"){
			$( "#checkbox_hip" ).prop( "checked", true );
			$( "#checkbox_synthesis" ).prop( "checked", true );
			$( "#checkbox_permeability" ).prop( "checked", true );
			$( "#checkbox_simpleshear" ).prop( "checked", true );
			$( "#checkbox_constantstress" ).prop( "checked", true );
			$( "#checkbox_constantrate" ).prop( "checked", true );
			$( "#checkbox_loadingunloading" ).prop( "checked", true );
			$( "#checkbox_stepping" ).prop( "checked", true );
			$( "#checkbox_extension" ).prop( "checked", true );

		}else if(selectedapparatussubtype == "Other Gas Medium Apparatus"){
			$( "#checkbox_hip" ).prop( "checked", true );
			$( "#checkbox_synthesis" ).prop( "checked", true );
			$( "#checkbox_permeability" ).prop( "checked", true );
			$( "#checkbox_simpleshear" ).prop( "checked", true );
			$( "#checkbox_constantstress" ).prop( "checked", true );
			$( "#checkbox_constantrate" ).prop( "checked", true );
			$( "#checkbox_loadingunloading" ).prop( "checked", true );
			$( "#checkbox_stepping" ).prop( "checked", true );

		}else if(selectedapparatussubtype == "Other Liquid Medium Apparatus"){
			$( "#checkbox_permeability" ).prop( "checked", true );
			$( "#checkbox_resistivity" ).prop( "checked", true );
			$( "#checkbox_simpleshear" ).prop( "checked", true );
			$( "#checkbox_constantstress" ).prop( "checked", true );
			$( "#checkbox_constantrate" ).prop( "checked", true );
			$( "#checkbox_loadingunloading" ).prop( "checked", true );
			$( "#checkbox_stepping" ).prop( "checked", true );

		}else if(selectedapparatussubtype == "Permeameter"){
			$( "#checkbox_permeability" ).prop( "checked", true );
			$( "#checkbox_resistivity" ).prop( "checked", true );

		}else if(selectedapparatussubtype == "Griggs Apparatus"){
			$( "#checkbox_synthesis" ).prop( "checked", true );
			$( "#checkbox_simpleshear" ).prop( "checked", true );
			$( "#checkbox_constantstress" ).prop( "checked", true );
			$( "#checkbox_constantrate" ).prop( "checked", true );
			$( "#checkbox_loadingunloading" ).prop( "checked", true );
			$( "#checkbox_stepping" ).prop( "checked", true );

		}else if(selectedapparatussubtype == "Piston Cylinder"){
			$( "#checkbox_synthesis" ).prop( "checked", true );
			$( "#checkbox_simpleshear" ).prop( "checked", true );
			$( "#checkbox_constantstress" ).prop( "checked", true );
			$( "#checkbox_constantrate" ).prop( "checked", true );
			$( "#checkbox_loadingunloading" ).prop( "checked", true );
			$( "#checkbox_stepping" ).prop( "checked", true );

		}else if(selectedapparatussubtype == "Multi Anvil Cell"){
			$( "#checkbox_hip" ).prop( "checked", true );
			$( "#checkbox_synthesis" ).prop( "checked", true );
			$( "#checkbox_constantstress" ).prop( "checked", true );
			$( "#checkbox_constantrate" ).prop( "checked", true );
			$( "#checkbox_loadingunloading" ).prop( "checked", true );
			$( "#checkbox_stepping" ).prop( "checked", true );

		}else if(selectedapparatussubtype == "Diamond Anvil Cell"){
			$( "#checkbox_synthesis" ).prop( "checked", true );
			$( "#checkbox_constantstress" ).prop( "checked", true );
			$( "#checkbox_constantrate" ).prop( "checked", true );
			$( "#checkbox_loadingunloading" ).prop( "checked", true );
			$( "#checkbox_stepping" ).prop( "checked", true );

		}else if(selectedapparatussubtype == "Other Solid Medium Apparatus"){
			$( "#checkbox_synthesis" ).prop( "checked", true );
			$( "#checkbox_simpleshear" ).prop( "checked", true );
			$( "#checkbox_constantstress" ).prop( "checked", true );
			$( "#checkbox_constantrate" ).prop( "checked", true );
			$( "#checkbox_loadingunloading" ).prop( "checked", true );
			$( "#checkbox_stepping" ).prop( "checked", true );

		}else if(selectedapparatussubtype == "Schmidt Hammer"){
			$( "#checkbox_pureshear" ).prop( "checked", true );

		}else if(selectedapparatussubtype == "Rotary Shear Friction Apparatus"){
			$( "#checkbox_rotaryshear" ).prop( "checked", true );

		}else if(selectedapparatussubtype == "Double Torsion Apparatus"){
			$( "#checkbox_rotaryshear" ).prop( "checked", true );

		}else if(selectedapparatussubtype == "Large Volume Torsion Apparatus (LVT)"){
			$( "#checkbox_rotaryshear" ).prop( "checked", true );

		}else if(selectedapparatussubtype == "Rheometer"){
			$( "#checkbox_rotaryshear" ).prop( "checked", true );

		}else if(selectedapparatussubtype == "Rotational Drickamer Apparatus (RDA)"){
			$( "#checkbox_rotaryshear" ).prop( "checked", true );

		}else if(selectedapparatussubtype == "Shear Box"){
			$( "#checkbox_simpleshear" ).prop( "checked", true );

		}else if(selectedapparatussubtype == "1 Atm Gas Mixing Furnace"){
			$( "#checkbox_depositionevaporation" ).prop( "checked", true );

		}else if(selectedapparatussubtype == "Vacuum Furnace"){
			$( "#checkbox_depositionevaporation" ).prop( "checked", true );

		}else if(selectedapparatussubtype == "Vickers Hardness Tester"){
			$( "#checkbox_hardness" ).prop( "checked", true );

		}else if(selectedapparatussubtype == "Nanoindenter"){
			$( "#checkbox_indentationcreep" ).prop( "checked", true );

		}else if(selectedapparatussubtype == "Indentation Cell"){
			$( "#checkbox_indentationcreep" ).prop( "checked", true );

		}else if(selectedapparatussubtype == "Point Load"){
			$( "#checkbox_indentationcreep" ).prop( "checked", true );

		}else if(selectedapparatussubtype == "Creep Cell"){
			$( "#checkbox_indentationcreep" ).prop( "checked", true );
		}

	}

	$("#country").change(function () {
		var selectedcountry = $("#country").val();
		if( selectedcountry != "" ){
			if( selectedcountry == "United States" ){
				$("#stateprovincewrapper").html(stateselectorhtml);
			}else{
				$("#stateprovincewrapper").html(statetextfieldhtml);
			}
		}else{
			$("#stateprovincewrapper").html("");
		}
	});

	var statetextfieldhtml = 'State/Province: <input type="text" id="state" name="state">';

	var stateselectorhtml = 'State: <select id="state" name="state"> \
		<option value="">Select...</option> \
		<option value="Alabama">Alabama</option> \
		<option value="Alaska">Alaska</option> \
		<option value="Arizona">Arizona</option> \
		<option value="Arkansas">Arkansas</option> \
		<option value="California">California</option> \
		<option value="Colorado">Colorado</option> \
		<option value="Connecticut">Connecticut</option> \
		<option value="Delaware">Delaware</option> \
		<option value="District Of Columbia">District Of Columbia</option> \
		<option value="Florida">Florida</option> \
		<option value="Georgia">Georgia</option> \
		<option value="Hawaii">Hawaii</option> \
		<option value="Idaho">Idaho</option> \
		<option value="Illinois">Illinois</option> \
		<option value="Indiana">Indiana</option> \
		<option value="Iowa">Iowa</option> \
		<option value="Kansas">Kansas</option> \
		<option value="Kentucky">Kentucky</option> \
		<option value="Louisiana">Louisiana</option> \
		<option value="Maine">Maine</option> \
		<option value="Maryland">Maryland</option> \
		<option value="Massachusetts">Massachusetts</option> \
		<option value="Michigan">Michigan</option> \
		<option value="Minnesota">Minnesota</option> \
		<option value="Mississippi">Mississippi</option> \
		<option value="Missouri">Missouri</option> \
		<option value="Montana">Montana</option> \
		<option value="Nebraska">Nebraska</option> \
		<option value="Nevada">Nevada</option> \
		<option value="New Hampshire">New Hampshire</option> \
		<option value="New Jersey">New Jersey</option> \
		<option value="New Mexico">New Mexico</option> \
		<option value="New York">New York</option> \
		<option value="North Carolina">North Carolina</option> \
		<option value="North Dakota">North Dakota</option> \
		<option value="Ohio">Ohio</option> \
		<option value="Oklahoma">Oklahoma</option> \
		<option value="Oregon">Oregon</option> \
		<option value="Pennsylvania">Pennsylvania</option> \
		<option value="Rhode Island">Rhode Island</option> \
		<option value="South Carolina">South Carolina</option> \
		<option value="South Dakota">South Dakota</option> \
		<option value="Tennessee">Tennessee</option> \
		<option value="Texas">Texas</option> \
		<option value="Utah">Utah</option> \
		<option value="Vermont">Vermont</option> \
		<option value="Virginia">Virginia</option> \
		<option value="Washington">Washington</option> \
		<option value="West Virginia">West Virginia</option> \
		<option value="Wisconsin">Wisconsin</option> \
		<option value="Wyoming">Wyoming</option> \
	</select>';
