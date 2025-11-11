<?php
/**
 * File: datamodel.php
 * Description: Handles datamodel operations
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

//data model array for building orientation data.

function getOrientationType($key){

	global $datamodel;

	$returnval = "";

	foreach($datamodel as $d){

		if($d["var"]==$key){
			$returnval = $d["type"];
		}

	}

	return $returnval;
}

$datamodel = array();
$datamodel[0]["type"]="linear_orientation";$datamodel[0]["var"]="orientation_data_defined_by";
$datamodel[1]["type"]="linear_orientation";$datamodel[1]["var"]="orientation_data_feature_type";
$datamodel[2]["type"]="linear_orientation";$datamodel[2]["var"]="orientation_data_geo_age";
$datamodel[3]["type"]="linear_orientation";$datamodel[3]["var"]="orientation_data_id";
$datamodel[4]["type"]="linear_orientation";$datamodel[4]["var"]="orientation_data_label";
$datamodel[5]["type"]="linear_orientation";$datamodel[5]["var"]="orientation_data_max_age";
$datamodel[6]["type"]="linear_orientation";$datamodel[6]["var"]="orientation_data_max_age_just";
$datamodel[7]["type"]="linear_orientation";$datamodel[7]["var"]="orientation_data_min_age";
$datamodel[8]["type"]="linear_orientation";$datamodel[8]["var"]="orientation_data_min_age_just";
$datamodel[9]["type"]="linear_orientation";$datamodel[9]["var"]="orientation_data_notes";
$datamodel[10]["type"]="linear_orientation";$datamodel[10]["var"]="orientation_data_other_feature";
$datamodel[11]["type"]="linear_orientation";$datamodel[11]["var"]="orientation_data_plunge";
$datamodel[12]["type"]="linear_orientation";$datamodel[12]["var"]="orientation_data_quality";
$datamodel[13]["type"]="linear_orientation";$datamodel[13]["var"]="orientation_data_rake";
$datamodel[14]["type"]="linear_orientation";$datamodel[14]["var"]="orientation_data_rake_calculated";
$datamodel[15]["type"]="linear_orientation";$datamodel[15]["var"]="orientation_data_trend";
$datamodel[16]["type"]="linear_orientation";$datamodel[16]["var"]="orientation_data_type";
$datamodel[17]["type"]="linear_orientation";$datamodel[17]["var"]="orientation_data_vorticity";
$datamodel[18]["type"]="planar";$datamodel[18]["var"]="orientation_data_bedding_type";
$datamodel[19]["type"]="planar";$datamodel[19]["var"]="orientation_data_contact_type";
$datamodel[20]["type"]="planar";$datamodel[20]["var"]="orientation_data_dip";
$datamodel[21]["type"]="planar";$datamodel[21]["var"]="orientation_data_dip_direction";
$datamodel[22]["type"]="planar";$datamodel[22]["var"]="orientation_data_directional_indicators";
$datamodel[23]["type"]="planar";$datamodel[23]["var"]="orientation_data_facing";
$datamodel[24]["type"]="planar";$datamodel[24]["var"]="orientation_data_facing_defined_by";
$datamodel[25]["type"]="planar";$datamodel[25]["var"]="orientation_data_fault_or_sz_type";
$datamodel[26]["type"]="planar";$datamodel[26]["var"]="orientation_data_feature_type";
$datamodel[27]["type"]="planar";$datamodel[27]["var"]="orientation_data_foliation_defined_by";
$datamodel[28]["type"]="planar";$datamodel[28]["var"]="orientation_data_foliation_type";
$datamodel[29]["type"]="planar";$datamodel[29]["var"]="orientation_data_fracture_defined_by";
$datamodel[30]["type"]="planar";$datamodel[30]["var"]="orientation_data_fracture_type";
$datamodel[31]["type"]="planar";$datamodel[31]["var"]="orientation_data_geo_age";
$datamodel[32]["type"]="planar";$datamodel[32]["var"]="orientation_data_id";
$datamodel[33]["type"]="planar";$datamodel[33]["var"]="orientation_data_label";
$datamodel[34]["type"]="planar";$datamodel[34]["var"]="orientation_data_length";
$datamodel[35]["type"]="planar";$datamodel[35]["var"]="orientation_data_max_age";
$datamodel[36]["type"]="planar";$datamodel[36]["var"]="orientation_data_max_age_just";
$datamodel[37]["type"]="planar";$datamodel[37]["var"]="orientation_data_min_age";
$datamodel[38]["type"]="planar";$datamodel[38]["var"]="orientation_data_min_age_just";
$datamodel[39]["type"]="planar";$datamodel[39]["var"]="orientation_data_movement";
$datamodel[40]["type"]="planar";$datamodel[40]["var"]="orientation_data_movement_amount_m";
$datamodel[41]["type"]="planar";$datamodel[41]["var"]="orientation_data_movement_amount_qualifier";
$datamodel[42]["type"]="planar";$datamodel[42]["var"]="orientation_data_movement_justification";
$datamodel[43]["type"]="planar";$datamodel[43]["var"]="orientation_data_notes";
$datamodel[44]["type"]="planar";$datamodel[44]["var"]="orientation_data_other_contact_type";
$datamodel[45]["type"]="planar";$datamodel[45]["var"]="orientation_data_other_dep_contact_type";
$datamodel[46]["type"]="planar";$datamodel[46]["var"]="orientation_data_other_directional_indic";
$datamodel[47]["type"]="planar";$datamodel[47]["var"]="orientation_data_other_facing_defined_by";
$datamodel[48]["type"]="planar";$datamodel[48]["var"]="orientation_data_other_fault_or_sz_type";
$datamodel[49]["type"]="planar";$datamodel[49]["var"]="orientation_data_other_feature";
$datamodel[50]["type"]="planar";$datamodel[50]["var"]="orientation_data_other_foliation_type";
$datamodel[51]["type"]="planar";$datamodel[51]["var"]="orientation_data_other_fracture_type";
$datamodel[52]["type"]="planar";$datamodel[52]["var"]="orientation_data_other_ig_contact_type";
$datamodel[53]["type"]="planar";$datamodel[53]["var"]="orientation_data_other_met_contact_type";
$datamodel[54]["type"]="planar";$datamodel[54]["var"]="orientation_data_other_movement";
$datamodel[55]["type"]="planar";$datamodel[55]["var"]="orientation_data_other_movement_justification";
$datamodel[56]["type"]="planar";$datamodel[56]["var"]="orientation_data_other_vein_fill";
$datamodel[57]["type"]="planar";$datamodel[57]["var"]="orientation_data_quality";
$datamodel[58]["type"]="planar";$datamodel[58]["var"]="orientation_data_strike";
$datamodel[59]["type"]="planar";$datamodel[59]["var"]="orientation_data_thickness";
$datamodel[60]["type"]="planar";$datamodel[60]["var"]="orientation_data_type";
$datamodel[61]["type"]="planar";$datamodel[61]["var"]="orientation_data_vein_fill";
$datamodel[62]["type"]="planar";$datamodel[62]["var"]="orientation_data_vein_type";
$datamodel[63]["type"]="tabular_zone";$datamodel[63]["var"]="orientation_data_alteration_zone";
$datamodel[64]["type"]="tabular_zone";$datamodel[64]["var"]="orientation_data_associated_orientation";
$datamodel[65]["type"]="tabular_zone";$datamodel[65]["var"]="orientation_data_damage_zone";
$datamodel[66]["type"]="tabular_zone";$datamodel[66]["var"]="orientation_data_damage_zone_defined_by";
$datamodel[67]["type"]="tabular_zone";$datamodel[67]["var"]="orientation_data_defined_by";
$datamodel[68]["type"]="tabular_zone";$datamodel[68]["var"]="orientation_data_dip";
$datamodel[69]["type"]="tabular_zone";$datamodel[69]["var"]="orientation_data_dip_direction";
$datamodel[70]["type"]="tabular_zone";$datamodel[70]["var"]="orientation_data_dir_indicators";
$datamodel[71]["type"]="tabular_zone";$datamodel[71]["var"]="orientation_data_enveloping_surface";
$datamodel[72]["type"]="tabular_zone";$datamodel[72]["var"]="orientation_data_enveloping_surface_geometry";
$datamodel[73]["type"]="tabular_zone";$datamodel[73]["var"]="orientation_data_facing";
$datamodel[74]["type"]="tabular_zone";$datamodel[74]["var"]="orientation_data_facing_defined_by";
$datamodel[75]["type"]="tabular_zone";$datamodel[75]["var"]="orientation_data_fault_or_sz";
$datamodel[76]["type"]="tabular_zone";$datamodel[76]["var"]="orientation_data_feature_type";
$datamodel[77]["type"]="tabular_zone";$datamodel[77]["var"]="orientation_data_fracture_zone";
$datamodel[78]["type"]="tabular_zone";$datamodel[78]["var"]="orientation_data_fracture_zone_def_by";
$datamodel[79]["type"]="tabular_zone";$datamodel[79]["var"]="orientation_data_geo_age";
$datamodel[80]["type"]="tabular_zone";$datamodel[80]["var"]="orientation_data_id";
$datamodel[81]["type"]="tabular_zone";$datamodel[81]["var"]="orientation_data_injection_type";
$datamodel[82]["type"]="tabular_zone";$datamodel[82]["var"]="orientation_data_intrusive_body_type";
$datamodel[83]["type"]="tabular_zone";$datamodel[83]["var"]="orientation_data_label";
$datamodel[84]["type"]="tabular_zone";$datamodel[84]["var"]="orientation_data_length";
$datamodel[85]["type"]="tabular_zone";$datamodel[85]["var"]="orientation_data_max_age";
$datamodel[86]["type"]="tabular_zone";$datamodel[86]["var"]="orientation_data_max_age_just";
$datamodel[87]["type"]="tabular_zone";$datamodel[87]["var"]="orientation_data_min_age";
$datamodel[88]["type"]="tabular_zone";$datamodel[88]["var"]="orientation_data_min_age_just";
$datamodel[89]["type"]="tabular_zone";$datamodel[89]["var"]="orientation_data_movement";
$datamodel[90]["type"]="tabular_zone";$datamodel[90]["var"]="orientation_data_movement_justification";
$datamodel[91]["type"]="tabular_zone";$datamodel[91]["var"]="orientation_data_notes";
$datamodel[92]["type"]="tabular_zone";$datamodel[92]["var"]="orientation_data_other_dir_indicators";
$datamodel[93]["type"]="tabular_zone";$datamodel[93]["var"]="orientation_data_other_facing_defined_by";
$datamodel[94]["type"]="tabular_zone";$datamodel[94]["var"]="orientation_data_other_fault_or_sz";
$datamodel[95]["type"]="tabular_zone";$datamodel[95]["var"]="orientation_data_other_feature";
$datamodel[96]["type"]="tabular_zone";$datamodel[96]["var"]="orientation_data_other_intrusive_body";
$datamodel[97]["type"]="tabular_zone";$datamodel[97]["var"]="orientation_data_other_movement";
$datamodel[98]["type"]="tabular_zone";$datamodel[98]["var"]="orientation_data_other_movement_justification";
$datamodel[99]["type"]="tabular_zone";$datamodel[99]["var"]="orientation_data_other_surface_geometry";
$datamodel[100]["type"]="tabular_zone";$datamodel[100]["var"]="orientation_data_other_vein_array";
$datamodel[101]["type"]="tabular_zone";$datamodel[101]["var"]="orientation_data_other_vein_fill";
$datamodel[102]["type"]="tabular_zone";$datamodel[102]["var"]="orientation_data_quality";
$datamodel[103]["type"]="tabular_zone";$datamodel[103]["var"]="orientation_data_strat_type";
$datamodel[104]["type"]="tabular_zone";$datamodel[104]["var"]="orientation_data_strike";
$datamodel[105]["type"]="tabular_zone";$datamodel[105]["var"]="orientation_data_tabularity";
$datamodel[106]["type"]="tabular_zone";$datamodel[106]["var"]="orientation_data_thickness";
$datamodel[107]["type"]="tabular_zone";$datamodel[107]["var"]="orientation_data_type";
$datamodel[108]["type"]="tabular_zone";$datamodel[108]["var"]="orientation_data_vein_array";
$datamodel[109]["type"]="tabular_zone";$datamodel[109]["var"]="orientation_data_vein_fill";
$datamodel[110]["type"]="tabular_zone";$datamodel[110]["var"]="orientation_data_vein_type";



?>