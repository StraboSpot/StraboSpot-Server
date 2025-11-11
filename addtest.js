/*
lab_name
facility_id
website
country
state
address1
address2
city
zip
contact_first_name
contact_last_name
contact_title
contact_email
apparatus_type
apparatus_subtype

hip
synthesis
depositionevaporation
indentationcreep
hardness
permeability
resistivity
simpleshear
rotaryshear
pureshear
constantstress
constantrate
loadingunloading
stepping
extension

checkbox_hip
checkbox_synthesis
checkbox_depositionevaporation
checkbox_indentationcreep
checkbox_hardness
checkbox_permeability
checkbox_resistivity
checkbox_simpleshear
checkbox_rotaryshear
checkbox_pureshear
checkbox_constantstress
checkbox_constantrate
checkbox_loadingunloading
checkbox_stepping
checkbox_extension
*/

function checkFormData(){
	let outError = "";
	let outDelim = "";

	//if($("#").val() != "") outError += outDelim + "."; outDelim="<br>";
	if($("#institute_pkey").val() == "") outError += outDelim + "Institute must be provided."; outDelim="<br>";

	if($("#apparatus_type").val() == ""){
		outError += outDelim + "Apparatus type must be provided."; outDelim="<br>";
	}else{
	let selectedapparatustype = $("#apparatus_type").val();

		if(selectedapparatustype == "Hydrostatic Loading Apparatuses" ||
			selectedapparatustype == "Uniaxial Apparatus" ||
			selectedapparatustype == "Biaxial Apparatus" ||
			selectedapparatustype == "Triaxial (Conventional) Apparatus" ||
			selectedapparatustype == "Rotary Shear Apparatus" ||
			selectedapparatustype == "Indenter" ||
			selectedapparatustype == "Nanoindenter"
		){
			if($("#apparatus_subtype").val() == "") outError += outDelim + "Apparatus subtype must be provided."; outDelim="<br>";
		}
	}

	if($("#apparatus_name").val() == "") outError += outDelim + "Apparatus name be provided."; outDelim="<br>";


	return outError;
}

function gatherFormData(){

	let out = {};

	//if($("#").val() != "") out. = $("#").val();
	if($("#institute_pkey").val() != "") out.institute_pkey = $("#institute_pkey").val();
	if($("#lab_name").val() != "") out.lab_name = $("#lab_name").val();
	if($("#facility_id").val() != "") out.facility_id = $("#facility_id").val();
	if($("#website").val() != "") out.website = $("#website").val();
	if($("#country").val() != "") out.country = $("#country").val();
	if($("#state").val() != "") out.state = $("#state").val();
	if($("#address1").val() != "") out.address1 = $("#address1").val();
	if($("#address2").val() != "") out.address2 = $("#address2").val();
	if($("#city").val() != "") out.city = $("#city").val();
	if($("#zip").val() != "") out.zip = $("#zip").val();
	if($("#contact_first_name").val() != "") out.contact_first_name = $("#contact_first_name").val();
	if($("#contact_last_name").val() != "") out.contact_last_name = $("#contact_last_name").val();
	if($("#contact_title").val() != "") out.contact_title = $("#contact_title").val();
	if($("#contact_email").val() != "") out.contact_email = $("#contact_email").val();
	if($("#apparatus_type").val() != "") out.apparatus_type = $("#apparatus_type").val();
	if($("#apparatus_subtype").val() != "") out.apparatus_subtype = $("#apparatus_subtype").val();
	if($("#apparatus_name").val() != "") out.apparatus_name = $("#apparatus_name").val();

	let test_types = {};
	if($("#checkbox_hip").is(':checked')) test_types.hip = true;
	if($("#checkbox_synthesis").is(':checked')) test_types.synthesis = true;
	if($("#checkbox_depositionevaporation").is(':checked')) test_types.depositionevaporation = true;
	if($("#checkbox_indentationcreep").is(':checked')) test_types.indentationcreep = true;
	if($("#checkbox_hardness").is(':checked')) test_types.hardness = true;
	if($("#checkbox_permeability").is(':checked')) test_types.permeability = true;
	if($("#checkbox_resistivity").is(':checked')) test_types.resistivity = true;
	if($("#checkbox_simpleshear").is(':checked')) test_types.simpleshear = true;
	if($("#checkbox_rotaryshear").is(':checked')) test_types.rotaryshear = true;
	if($("#checkbox_pureshear").is(':checked')) test_types.pureshear = true;
	if($("#checkbox_constantstress").is(':checked')) test_types.constantstress = true;
	if($("#checkbox_constantrate").is(':checked')) test_types.constantrate = true;
	if($("#checkbox_loadingunloading").is(':checked')) test_types.loadingunloading = true;
	if($("#checkbox_stepping").is(':checked')) test_types.stepping = true;
	if($("#checkbox_extension").is(':checked')) test_types.extension = true;
	out.test_types = test_types;

	if($("#checkbox_confiningpressure").is(':checked')){
	let confining_pressure = {};

		if($("#cp_servo_controlled").is(':checked')) confining_pressure.servo_controlled = true;
		if($("#cp_confiningmediumone").val() != "") confining_pressure.confiningmediumone = $("#cp_confiningmediumone").val();
		if($("#cp_confiningmediumtwo").val() != "" && $("#cp_confiningmediumtwo").val() != null) confining_pressure.confiningmediumtwo = $("#cp_confiningmediumtwo").val();
		if($("#cp_confiningmediumother").val() != "") confining_pressure.confiningmediumother = $("#cp_confiningmediumother").val();
		if($("#cp_minconfiningpressure").val() != "") confining_pressure.minconfiningpressure = $("#cp_minconfiningpressure").val();
		if($("#cp_minconfiningpressure_unix").val() != "") confining_pressure.minconfiningpressureunit = $("#cp_minconfiningpressure_unit").val();
		if($("#cp_maxconfiningpressure").val() != "") confining_pressure.maxconfiningpressure = $("#cp_maxconfiningpressure").val();
		if($("#cp_maxconfiningpressure_unix").val() != "") confining_pressure.maxconfiningpressureunit = $("#cp_maxconfiningpressure_unit").val();
	let cp_sensors = [];

		//switch to sensor num from php
		for(i = 1; i <= 5; i++ ){
			if($("#cp_sensortype_"+i).val() != "" ){
	let thissensor = {};
				thissensor.sensornum = i;
				thissensor.sensortype = $("#cp_sensortype_"+i).val();
				if($("#cp_sensorothertype_"+i).val() != "" ) thissensor.sensorothertype = $("#cp_sensorothertype_"+i).val();
				if($("#cp_sensorlocation_"+i).val() != "" ) thissensor.sensorlocation = $("#cp_sensorlocation_"+i).val();
				if($("#cp_sensorcalibration_"+i).val() != "" ) thissensor.sensorcalibration = $("#cp_sensorcalibration_"+i).val();
				if($("#cp_sensornotes_"+i).val() != "" ) thissensor.sensornotes = $("#cp_sensornotes_"+i).val();

				cp_sensors.push(thissensor);
			}
		}

		confining_pressure.sensors = cp_sensors;

		out.confining_pressure = confining_pressure;
	}

	if($("#checkbox_temperaturefurnacecryostat").is(':checked')){

	let temp = {};

		if($("#temp_furnace").is(':checked')) temp.furnace = true;
		if($("#temp_cryostat").is(':checked')) temp.cryostat = true;
		if($("#temp_servo_controlled").is(':checked')) temp.servocontrolled = true;

		if($("#temp_mintemperature").val() != "") temp.mintemperature = $("#temp_mintemperature").val();
		if($("#temp_mintemperature_unit").val() != "") temp.mintemperatureunit = $("#temp_mintemperature_unit").val();

		if($("#temp_maxtemperature").val() != "") temp.maxtemperature = $("#temp_maxtemperature").val();
		if($("#temp_maxtemperature_unit").val() != "") temp.maxtemperatureunit = $("#temp_maxtemperature_unit").val();

	let temp_sensors = [];

		//switch to sensor num from php
		for(i = 1; i <= 5; i++ ){
			if($("#temp_sensortype_"+i).val() != "" ){
	let thissensor = {};
				thissensor.sensornum = i;
				thissensor.sensortype = $("#temp_sensortype_"+i).val();

				if($("#temp_thermocoupletype_"+i).val() != "" ) thissensor.thermocoupletype = $("#temp_thermocoupletype_"+i).val();
				if($("#temp_thermocouplesubtype_"+i).val() != "" ) thissensor.thermocouplesubtype = $("#temp_thermocouplesubtype_"+i).val();
				if($("#temp_rtdtype_"+i).val() != "" ) thissensor.rtdtype= $("#temp_rtdtype_"+i).val();
				if($("#temp_thermistortype_"+i).val() != "" ) thissensor.thermistortype = $("#temp_thermistortype_"+i).val();

				if($("#temp_othersensortype_"+i).val() != "" ) thissensor.othersensortype = $("#temp_othersensortype_"+i).val();
				if($("#temp_sensorlocation_"+i).val() != "" ) thissensor.sensorlocation = $("#temp_sensorlocation_"+i).val();
				if($("#temp_sensorcalibration_"+i).val() != "" ) thissensor.sensorcalibration = $("#temp_sensorcalibration_"+i).val();
				if($("#temp_sensornotes_"+i).val() != "" ) thissensor.sensornotes = $("#temp_sensornotes_"+i).val();

				temp_sensors.push(thissensor);
			}
		}

		temp.sensors = temp_sensors;

		out.temp = temp;

	}

	if($("#checkbox_displacement").is(':checked')){

	let displacement = {};

		if($("#checkbox_dispax").is(':checked')){
	let dispax = {};
			if($("#dispax_mindisplacement").val() != "") dispax.mindisplacement = $("#dispax_mindisplacement").val();
			if($("#dispax_mindisplacement_unit").val() != "") dispax.mindisplacementunit = $("#dispax_mindisplacement_unit").val();
			if($("#dispax_maxdisplacement").val() != "") dispax.maxdisplacement = $("#dispax_maxdisplacement").val();
			if($("#dispax_maxdisplacement_unit").val() != "") dispax.maxdisplacementunit = $("#dispax_maxdisplacement_unit").val();

			if($("#dispax_mindisplacementrate").val() != "") dispax.mindisplacementrate = $("#dispax_mindisplacementrate").val();
			if($("#dispax_mindisplacementrate_unit").val() != "") dispax.mindisplacementrateunit = $("#dispax_mindisplacementrate_unit").val();
			if($("#dispax_maxdisplacementrate").val() != "") dispax.maxdisplacementrate = $("#dispax_maxdisplacementrate").val();
			if($("#dispax_maxdisplacementrate_unit").val() != "") dispax.maxdisplacementrateunit = $("#dispax_maxdisplacementrate_unit").val();

	let dispax_sensors = [];

			for(i = 1; i <= 5; i++ ){
				if($("#dispax_sensortype_"+i).val() != "" ){
	let thissensor = {};
					thissensor.sensornum = i;
					thissensor.sensortype = $("#dispax_sensortype_"+i).val();

					if($("#dispax_othersensortype_"+i).val() != "" ) thissensor.othersensortype = $("#dispax_othersensortype_"+i).val();
					if($("#dispax_sensorlocation_"+i).val() != "" ) thissensor.sensorlocation = $("#dispax_sensorlocation_"+i).val();
					if($("#dispax_sensorcalibration_"+i).val() != "" ) thissensor.sensorcalibration = $("#dispax_sensorcalibration_"+i).val();
					if($("#dispax_sensornotes_"+i).val() != "" ) thissensor.sensornotes = $("#dispax_sensornotes_"+i).val();

					dispax_sensors.push(thissensor);
				}
			}

			dispax.sensors = dispax_sensors;


			displacement.axial = dispax;
		}


		if($("#checkbox_disprot").is(':checked')){
	let disprot = {};
			if($("#disprot_solid_cylinder").is(':checked')) disprot.solidcylinder = true;
			if($("#disprot_annular").is(':checked')) disprot.annular = true;
			if($("#disprot_servo_controlled").is(':checked')) disprot.servocontrolled = true;

			if($("#disprot_minsamplediameter").val() != "") disprot.minsamplediameter = $("#disprot_minsamplediameter").val();
			if($("#disprot_minsamplediameter_unit").val() != "") disprot.minsamplediameterunit = $("#disprot_minsamplediameter_unit").val();
			if($("#disprot_maxsamplediameter").val() != "") disprot.maxsamplediameter = $("#disprot_maxsamplediameter").val();
			if($("#disprot_maxsamplediameter_unit").val() != "") disprot.maxsamplediameterunit = $("#disprot_maxsamplediameter_unit").val();

			if($("#disprot_minsamplethickness").val() != "") disprot.minsamplethickness = $("#disprot_minsamplethickness").val();
			if($("#disprot_minsamplethickness_unit").val() != "") disprot.minsamplethicknessunit = $("#disprot_minsamplethickness_unit").val();
			if($("#disprot_maxsamplethickness").val() != "") disprot.maxsamplethickness = $("#disprot_maxsamplethickness").val();
			if($("#disprot_maxsamplethickness_unit").val() != "") disprot.maxsamplethicknessunit = $("#disprot_maxsamplethickness_unit").val();

			if($("#disprot_mindisplacement").val() != "") disprot.mindisplacement = $("#disprot_mindisplacement").val();
			if($("#disprot_mindisplacement_unit").val() != "") disprot.mindisplacementunit = $("#disprot_mindisplacement_unit").val();
			if($("#disprot_maxdisplacement").val() != "") disprot.maxdisplacement = $("#disprot_maxdisplacement").val();
			if($("#disprot_maxdisplacement_unit").val() != "") disprot.maxdisplacementunit = $("#disprot_maxdisplacement_unit").val();

			if($("#disprot_infinitemaxdisplacement").is(':checked')) disprot.infinitemaxdisplacement = true;

			if($("#disprot_minrotationrate").val() != "") disprot.minrotationrate = $("#disprot_minrotationrate").val();
			if($("#disprot_minrotation_unit").val() != "") disprot.minrotationunit = $("#disprot_minrotation_unit").val();
			if($("#disprot_maxrotationrate").val() != "") disprot.maxrotationrate = $("#disprot_maxrotationrate").val();
			if($("#disprot_maxrotation_unit").val() != "") disprot.maxrotationunit = $("#disprot_maxrotation_unit").val();

	let disprot_sensors = [];

			for(i = 1; i <= 5; i++ ){
				if($("#disprot_sensortype_"+i).val() != "" ){
	let thissensor = {};
					thissensor.sensornum = i;
					thissensor.sensortype = $("#disprot_sensortype_"+i).val();

					if($("#disprot_othersensortype_"+i).val() != "" ) thissensor.othersensortype = $("#disprot_othersensortype_"+i).val();
					if($("#disprot_sensorlocation_"+i).val() != "" ) thissensor.sensorlocation = $("#disprot_sensorlocation_"+i).val();
					if($("#disprot_sensorcalibration_"+i).val() != "" ) thissensor.sensorcalibration = $("#disprot_sensorcalibration_"+i).val();
					if($("#disprot_sensornotes_"+i).val() != "" ) thissensor.sensornotes = $("#disprot_sensornotes_"+i).val();

					disprot_sensors.push(thissensor);
				}
			}

			disprot.sensors = disprot_sensors;

			displacement.rotary = disprot;
		}

		if($("#checkbox_dispvol").is(':checked')){

	let dispvol = {};

	let dispvol_sensors = [];

			for(i = 1; i <= 5; i++ ){
				if($("#dispvol_sensortype_"+i).val() != "" ){
	let thissensor = {};
					thissensor.sensornum = i;
					thissensor.sensortype = $("#dispvol_sensortype_"+i).val();

					if($("#dispvol_othersensortype_"+i).val() != "" ) thissensor.othersensortype = $("#dispvol_othersensortype_"+i).val();
					if($("#dispvol_sensorlocation_"+i).val() != "" ) thissensor.sensorlocation = $("#dispvol_sensorlocation_"+i).val();
					if($("#dispvol_sensorcalibration_"+i).val() != "" ) thissensor.sensorcalibration = $("#dispvol_sensorcalibration_"+i).val();
					if($("#dispvol_sensornotes_"+i).val() != "" ) thissensor.sensornotes = $("#dispvol_sensornotes_"+i).val();

					dispvol_sensors.push(thissensor);
				}
			}

			dispvol.sensors = dispvol_sensors;

			displacement.volumetric = dispvol;

		}

		if($("#checkbox_dispshear").is(':checked')){

	let dispshear = {};

	let dispshear_sensors = [];

			for(i = 1; i <= 5; i++ ){
				if($("#dispshear_sensortype_"+i).val() != "" ){
	let thissensor = {};
					thissensor.sensornum = i;
					thissensor.sensortype = $("#dispshear_sensortype_"+i).val();

					if($("#dispshear_othersensortype_"+i).val() != "" ) thissensor.othersensortype = $("#dispshear_othersensortype_"+i).val();
					if($("#dispshear_sensorlocation_"+i).val() != "" ) thissensor.sensorlocation = $("#dispshear_sensorlocation_"+i).val();
					if($("#dispshear_sensorcalibration_"+i).val() != "" ) thissensor.sensorcalibration = $("#dispshear_sensorcalibration_"+i).val();
					if($("#dispshear_sensornotes_"+i).val() != "" ) thissensor.sensornotes = $("#dispshear_sensornotes_"+i).val();

					dispshear_sensors.push(thissensor);
				}
			}

			dispshear.sensors = dispshear_sensors;

			displacement.shear = dispshear;

		}

		out.displacement = displacement;

	}

	if($("#checkbox_force").is(':checked')){

	let force = {};

		if($("#force_maxforce").val() != "") force.maxforce = $("#force_maxforce").val();
		if($("#force_maxforce_unit").val() != "") force.maxforceunit = $("#force_maxforce_unit").val();

	let force_sensors = [];

		for(i = 1; i <= 5; i++ ){
			if($("#force_sensortype_"+i).val() != "" ){
	let thissensor = {};
				thissensor.sensornum = i;
				thissensor.sensortype = $("#force_sensortype_"+i).val();

				if($("#force_othersensortype_"+i).val() != "" ) thissensor.othersensortype = $("#force_othersensortype_"+i).val();
				if($("#force_sensorlocation_"+i).val() != "" ) thissensor.sensorlocation = $("#force_sensorlocation_"+i).val();
				if($("#force_sensorcalibration_"+i).val() != "" ) thissensor.sensorcalibration = $("#force_sensorcalibration_"+i).val();
				if($("#force_sensornotes_"+i).val() != "" ) thissensor.sensornotes = $("#force_sensornotes_"+i).val();

				force_sensors.push(thissensor);
			}
		}

		force.sensors = force_sensors;

		out.force = force;

	}

	if($("#checkbox_torque").is(':checked')){

	let torque = {};

		if($("#torque_maxtorque").val() != "") torque.maxtorque = $("#torque_maxtorque").val();
		if($("#torque_maxtorque_unit").val() != "") torque.maxtorqueunit = $("#torque_maxtorque_unit").val();

	let torque_sensors = [];

		for(i = 1; i <= 5; i++ ){
			if($("#torque_geometryofelasticelement_"+i).val() != "" ){
	let thissensor = {};
				thissensor.sensornum = i;
				thissensor.geometryofelasticelement = $("#torque_geometryofelasticelement_"+i).val();

				if($("#torque_numberofstraingauges_"+i).val() != "" ) thissensor.numberofstraingauges = $("#torque_numberofstraingauges_"+i).val()
				if($("#torque_othergeometryofelasticelement_"+i).val() != "" ) thissensor.othergeometryofelasticelement = $("#torque_othergeometryofelasticelement_"+i).val();
				if($("#torque_sensortype_"+i).val() != "" ) thissensor.sensortype = $("#torque_sensortype_"+i).val();
				if($("#torque_othersensortype_"+i).val() != "" ) thissensor.othersensortype = $("#torque_othersensortype_"+i).val();




				if($("#torque_sensorlocation_"+i).val() != "" ) thissensor.sensorlocation = $("#torque_sensorlocation_"+i).val();
				if($("#torque_sensorcalibration_"+i).val() != "" ) thissensor.sensorcalibration = $("#torque_sensorcalibration_"+i).val();
				if($("#torque_sensornotes_"+i).val() != "" ) thissensor.sensornotes = $("#torque_sensornotes_"+i).val();

				torque_sensors.push(thissensor);
			}
		}

		torque.sensors = torque_sensors;

		out.torque = torque;

	}

	if($("#checkbox_porefluids").is(':checked')){

	let pore = {};

		if($("#pore_undrainedonly").is(':checked')) pore.undrainedonly = true;
		if($("#pore_measured").is(':checked')) pore.measured = true;
		if($("#pore_servocontrolled").is(':checked')) pore.servocontrolled = true;
		if($("#pore_flowthrough").is(':checked')) pore.flowthrough = true;


		if($("#pore_minporefluidpressure").val() != "") pore.minporefluidpressure = $("#pore_minporefluidpressure").val();
		if($("#pore_minporefluidpressureunit").val() != "") pore.minporefluidpressureunit = $("#pore_minporefluidpressureunit").val();
		if($("#pore_maxporefluidpressure").val() != "") pore.maxporefluidpressure = $("#pore_maxporefluidpressure").val();
		if($("#pore_maxporefluidpressureunit").val() != "") pore.maxporefluidpressureunit = $("#pore_maxporefluidpressureunit").val();


	let pore_sensors = [];

		for(i = 1; i <= 5; i++ ){
			if($("#pore_sensortype_"+i).val() != "" ){
	let thissensor = {};
				thissensor.sensornum = i;
				thissensor.sensortype = $("#pore_sensortype_"+i).val();

				if($("#pore_othersensortype_"+i).val() != "" ) thissensor.othersensortype = $("#pore_othersensortype_"+i).val();
				if($("#pore_sensorlocation_"+i).val() != "" ) thissensor.sensorlocation = $("#pore_sensorlocation_"+i).val();
				if($("#pore_sensorcalibration_"+i).val() != "" ) thissensor.sensorcalibration = $("#pore_sensorcalibration_"+i).val();
				if($("#pore_sensornotes_"+i).val() != "" ) thissensor.sensornotes = $("#pore_sensornotes_"+i).val();

				pore_sensors.push(thissensor);
			}
		}

		pore.sensors = pore_sensors;

		out.pore = pore;

	}

	if($("#checkbox_acousticemissions").is(':checked')){

	let acoustic = {};

		if($("#acoustic_continuousstreaming").is(':checked')) acoustic.continuousstreaming = true;
		if($("#acoustic_triggeredrecording").is(':checked')) acoustic.triggeredrecording = true;
		if($("#acoustic_momentanalysiscalibratedamplitude").is(':checked')) acoustic.momentanalysiscalibratedamplitude = true;
		if($("#acoustic_transmissivityreflectivity").is(':checked')) acoustic.transmissivityreflectivity = true;
		if($("#acoustic_aecount").is(':checked')) acoustic.aecount = true;
		if($("#acoustic_tomography").is(':checked')) acoustic.tomography = true;
		if($("#acoustic_momentanalysisrelativeamplitude").is(':checked')) acoustic.momentanalysisrelativeamplitude = true;




		if($("#acoustic_minfrequencyrange").val() != "") acoustic.minfrequencyrange = $("#acoustic_minfrequencyrange").val();
		if($("#acoustic_minfrequencyrangeunit").val() != "") acoustic.minfrequencyrangeunit = $("#acoustic_minfrequencyrangeunit").val();
		if($("#acoustic_maxfrequencyrange").val() != "") acoustic.maxfrequencyrange = $("#acoustic_maxfrequencyrange").val();
		if($("#acoustic_maxfrequencyrangeunit").val() != "") acoustic.maxfrequencyrangeunit = $("#acoustic_maxfrequencyrangeunit").val();

		if($("#acoustic_sensornotes").val() != "") acoustic.sensornotes = $("#acoustic_sensornotes").val();

		out.acoustic = acoustic;

	}

	if($("#checkbox_elasticwave").is(':checked')){

	let elastic = {};

		if($("#elastic_piezoelectricsensors").is(':checked')) elastic.piezoelectricsensors = true;

		if($("#elastic_piezoelectricyransduceryype").val() != "") elastic.piezoelectricyransduceryype = $("#elastic_piezoelectricyransduceryype").val();

		if($("#elastic_pwave").is(':checked')) elastic.pwave = true;
		if($("#elastic_swave").is(':checked')) elastic.swave = true;
		if($("#elastic_s1wave").is(':checked')) elastic.s1wave = true;
		if($("#elastic_s2wave").is(':checked')) elastic.s2wave = true;



		if($("#elastic_minfrequencyrange").val() != "") elastic.minfrequencyrange = $("#elastic_minfrequencyrange").val();
		if($("#elastic_minfrequencyrangeunit").val() != "") elastic.minfrequencyrangeunit = $("#elastic_minfrequencyrangeunit").val();
		if($("#elastic_maxfrequencyrange").val() != "") elastic.maxfrequencyrange = $("#elastic_maxfrequencyrange").val();
		if($("#elastic_maxfrequencyrangeunit").val() != "") elastic.maxfrequencyrangeunit = $("#elastic_maxfrequencyrangeunit").val();

		if($("#elastic_sensornotes").val() != "") elastic.sensornotes = $("#elastic_sensornotes").val();

		out.elastic = elastic;

	}

	if($("#checkbox_electricalconductivity").is(':checked')){

	let electrical = {};

		if($("#electrical_frequencydependent").is(':checked')) electrical.frequencydependent = true;

		if($("#electrical_amplitude").val() != "") electrical.amplitude = $("#electrical_amplitude").val();
		if($("#electrical_electrodetype").val() != "") electrical.electrodetype = $("#electrical_electrodetype").val();
		if($("#electrical_otherelectrodetype").val() != "") electrical.otherelectrodetype = $("#electrical_otherelectrodetype").val();
		if($("#electrical_numberofelectrodes").val() != "") electrical.numberofelectrodes = $("#electrical_numberofelectrodes").val();
		if($("#electrical_sensorcalibration").val() != "") electrical.sensorcalibration = $("#electrical_sensorcalibration").val();
		if($("#electrical_sensornotes").val() != "") electrical.sensornotes = $("#electrical_sensornotes").val();

		out.electrical = electrical;

	}

	if($("#apparatus_description_notes").val() != "") out.apparatusdescriptionnotes = $("#apparatus_description_notes").val();


	return out;

}
