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
	
	$("#cp_sensortype_1").change(function () {
		console.log("changed");
		$("#cp_sensorothertype_1").val("");
		var cpsensortype1 = $("#cp_sensortype_1").val();
		if(cpsensortype1=="Other"){
			$("#cp_sensorothertype_1").show();
		}else{
			$("#cp_sensorothertype_1").hide();
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
	
	$("#checkbox_rotary").change(function () {
		if($("#checkbox_rotary").is(':checked')){
			$("#rotary_wrapper").show();
		}else{
			$("#rotary_wrapper").hide();
		}
	});
	
	$("#checkbox_volumetric").change(function () {
		if($("#checkbox_volumetric").is(':checked')){
			$("#volumetric_wrapper").show();
		}else{
			$("#volumetric_wrapper").hide();
		}
	});
	
	$("#checkbox_shear").change(function () {
		if($("#checkbox_shear").is(':checked')){
			$("#shear_wrapper").show();
		}else{
			$("#shear_wrapper").hide();
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

	
	var statetextfieldhtml = 'State/Province: <input type="text" name="state">';
	
	var stateselectorhtml = 'State: <select name="state"> \
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
	