<?
$cvvals = new stdClass();


unset($cvarray);
$cvarray=array();

$cvarray['Spot:']='selectgroup';
$cvarray['Name']='spot_name';
$cvarray['Notes']='spot_notes';
$cvvals->spot = $cvarray;


unset($cvarray);
$cvarray=array();

$cvarray['3d Structures:']='selectgroup';
$cvarray['Approximate Scale (m)']='_3d_structures_approximate_scale_m_lobate';
$cvarray['Approximate Scale of Boudinage (m)']='_3d_structures_approximate_scale_of_boudinage';
$cvarray['Approximate Scale of Mullions (m)']='_3d_structures_approximate_scale_of_mullions';
$cvarray['Average Width of Boudin Neck (m)']='_3d_structures_average_width_of_boudin_neck';
$cvarray['Linear Measurement Quality']='_3d_structures_boudin_linear_measure_quality';
$cvarray['Plunge']='_3d_structures_boudinage_2nd_plunge';
$cvarray['Trend']='_3d_structures_boudinage_2nd_trend';
$cvarray['Uncertainty']='_3d_structures_boudinage_2nd_trend_uncertaint';
$cvarray['Competent Material']='_3d_structures_boudinage_competent';
$cvarray['Dip']='_3d_structures_boudinage_dip';
$cvarray['Dip Direction']='_3d_structures_boudinage_dip_direction';
$cvarray['Boudinage Geometry']='_3d_structures_boudinage_geometry';
$cvarray['Incompetent Material']='_3d_structures_boudinage_incompetent';
$cvarray['Plunge']='_3d_structures_boudinage_plunge';
$cvarray['Boudinage Shape']='_3d_structures_boudinage_shape';
$cvarray['Strike']='_3d_structures_boudinage_strike';
$cvarray['Trend']='_3d_structures_boudinage_trend';
$cvarray['Measured Uncertainty']='_3d_structures_boudinage_trend_uncertainty';
$cvarray['Wavelength (m)']='_3d_structures_boudinage_wavelength_m';
$cvarray['Boudinaged Layer Thickness (m)']='_3d_structures_boudinaged_layer_thickness_m';
$cvarray['Competent Material']='_3d_structures_competent_material_fold';
$cvarray['Ellipse Type']='_3d_structures_ellipse_type';
$cvarray['Orientation Uncertainty']='_3d_structures_ellipsoid_int_orient_uncertain';
$cvarray['Plunge']='_3d_structures_ellipsoid_int_plunge';
$cvarray['Uncertainty']='_3d_structures_ellipsoid_int_plunge_uncertain';
$cvarray['Trend']='_3d_structures_ellipsoid_int_trend';
$cvarray['Uncertainty']='_3d_structures_ellipsoid_int_trend_uncertaint';
$cvarray['Value']='_3d_structures_ellipsoid_int_value';
$cvarray['Uncertainty']='_3d_structures_ellipsoid_int_value_uncertaint';
$cvarray['Orientation Uncertainty']='_3d_structures_ellipsoid_max_orient_uncertain';
$cvarray['Plunge']='_3d_structures_ellipsoid_max_plunge';
$cvarray['Uncertainty']='_3d_structures_ellipsoid_max_plunge_uncertain';
$cvarray['Trend']='_3d_structures_ellipsoid_max_trend';
$cvarray['Uncertainty']='_3d_structures_ellipsoid_max_trend_uncertaint';
$cvarray['Uncertainty']='_3d_structures_ellipsoid_max_uncertainty';
$cvarray['Value']='_3d_structures_ellipsoid_max_value';
$cvarray['Orientation Uncertainty']='_3d_structures_ellipsoid_min_orient_uncertain';
$cvarray['Plunge']='_3d_structures_ellipsoid_min_plunge';
$cvarray['Uncertainty']='_3d_structures_ellipsoid_min_plunge_uncertain';
$cvarray['Trend']='_3d_structures_ellipsoid_min_trend';
$cvarray['Uncertainty']='_3d_structures_ellipsoid_min_trend_uncertaint';
$cvarray['Value']='_3d_structures_ellipsoid_min_value';
$cvarray['Uncertainty']='_3d_structures_ellipsoid_min_value_uncertaint';
$cvarray['Quality of Measurement']='_3d_structures_ellipsoid_quality';
$cvarray['Ellipsoid Type']='_3d_structures_ellipsoid_type';
$cvarray['Ellipsoidal Tensor Notes']='_3d_structures_ellipsoidal_tensor_notes';
$cvarray['Dip']='_3d_structures_elliptical_dip';
$cvarray['Dip Direction']='_3d_structures_elliptical_dip_direction';
$cvarray['Elliptical Data Notes']='_3d_structures_elliptical_notes';
$cvarray['Orientation Uncertainty']='_3d_structures_elliptical_orientation_uncerta';
$cvarray['Quality']='_3d_structures_elliptical_quality';
$cvarray['Quality of Measurement']='_3d_structures_elliptical_quality_of_measurem';
$cvarray['Rake']='_3d_structures_elliptical_rake';
$cvarray['Elliptical Ratio']='_3d_structures_elliptical_ratio';
$cvarray['Strike']='_3d_structures_elliptical_strike';
$cvarray['Value Uncertainty']='_3d_structures_elliptical_value_uncertainty';
$cvarray['Fabric Type']='_3d_structures_feature_type';
$cvarray['Dominant Fold Attitude']='_3d_structures_fold_attitude';
$cvarray['Dip']='_3d_structures_fold_fol_dip';
$cvarray['Dip Direction']='_3d_structures_fold_fol_dip_direction';
$cvarray['Orientation Quality']='_3d_structures_fold_fol_quality';
$cvarray['Strike']='_3d_structures_fold_fol_strike';
$cvarray['Foliation Type']='_3d_structures_fold_foliation_Type';
$cvarray['Description of Foliation']='_3d_structures_fold_foliation_description';
$cvarray['Fold Notes']='_3d_structures_fold_notes';
$cvarray['Dominant Fold Shape']='_3d_structures_fold_shape';
$cvarray['Plunge']='_3d_structures_fold_shortening_dir_plunge';
$cvarray['Trend']='_3d_structures_fold_shortening_dir_trend';
$cvarray['Uncertainty']='_3d_structures_fold_shortening_dir_uncertaint';
$cvarray['Folded Layer Thickness (m)']='_3d_structures_folded_layer_thickness_m';
$cvarray['Id']='_3d_structures_id';
$cvarray['Igneous/Migmatite Features Description']='_3d_structures_igneous_migmatite_feat_descrip';
$cvarray['Incompetent Material']='_3d_structures_incompetent_material_fold';
$cvarray['Label; Hint: If a label is not specified a default label will be given.']='_3d_structures_label';
$cvarray['Competent Material']='_3d_structures_lobate_competent_material';
$cvarray['Incompetent Material']='_3d_structures_lobate_incompetent_material';
$cvarray['Movement']='_3d_structures_movement';
$cvarray['Competent Material']='_3d_structures_mullion_competent_material';
$cvarray['Dip']='_3d_structures_mullion_dip';
$cvarray['Dip Direction']='_3d_structures_mullion_dip_direction';
$cvarray['Mullion Geometry']='_3d_structures_mullion_geometry';
$cvarray['Incompetent Material']='_3d_structures_mullion_incompetent_material';
$cvarray['Mullion Layer Thickness (m)']='_3d_structures_mullion_layer_thickness_m';
$cvarray['Linear Measurement Quality']='_3d_structures_mullion_linear_measure_quality';
$cvarray['Plunge']='_3d_structures_mullion_plunge';
$cvarray['Strike']='_3d_structures_mullion_strike';
$cvarray['Mullion Symmetry']='_3d_structures_mullion_symmetry';
$cvarray['Trend']='_3d_structures_mullion_trend';
$cvarray['Uncertainty']='_3d_structures_mullion_uncertainty';
$cvarray['Wavelength (m)']='_3d_structures_mullion_wavelength_m';
$cvarray['Non-ellipsoidal Tensor Notes']='_3d_structures_non_ellipsoidal_tensor_notes';
$cvarray['Non-ellipsoidal Type']='_3d_structures_non_ellipsoidal_type';
$cvarray['Orientation Uncertainty']='_3d_structures_nonellipsoidal_int_orient_unce';
$cvarray['Plunge']='_3d_structures_nonellipsoidal_int_plunge';
$cvarray['Uncertainty']='_3d_structures_nonellipsoidal_int_plunge_unce';
$cvarray['Trend']='_3d_structures_nonellipsoidal_int_trend';
$cvarray['Uncertainty']='_3d_structures_nonellipsoidal_int_uncertainty';
$cvarray['Value']='_3d_structures_nonellipsoidal_int_value';
$cvarray['Uncertainty']='_3d_structures_nonellipsoidal_int_value_uncer';
$cvarray['Orientation Uncertainty']='_3d_structures_nonellipsoidal_max_orient_Unce';
$cvarray['Plunge']='_3d_structures_nonellipsoidal_max_plunge';
$cvarray['Uncertainty']='_3d_structures_nonellipsoidal_max_plunge_unce';
$cvarray['Trend']='_3d_structures_nonellipsoidal_max_trend';
$cvarray['Uncertainty']='_3d_structures_nonellipsoidal_max_trend_uncer';
$cvarray['Value']='_3d_structures_nonellipsoidal_max_value';
$cvarray['Uncertainty']='_3d_structures_nonellipsoidal_max_value_uncer';
$cvarray['Orientation Uncertainty']='_3d_structures_nonellipsoidal_min_orient_Unce';
$cvarray['Plunge']='_3d_structures_nonellipsoidal_min_plunge';
$cvarray['Uncertainty']='_3d_structures_nonellipsoidal_min_plunge_unce';
$cvarray['Trend']='_3d_structures_nonellipsoidal_min_trend';
$cvarray['Uncertainty']='_3d_structures_nonellipsoidal_min_trend_uncer';
$cvarray['Value']='_3d_structures_nonellipsoidal_min_value';
$cvarray['Uncertainty']='_3d_structures_nonellipsoidal_min_value_uncer';
$cvarray['Quality of Measurement']='_3d_structures_nonellipsoidal_quality_of_meas';
$cvarray['Number of Necks Measured']='_3d_structures_number_of_necks_measured';
$cvarray['Other Fold Type']='_3d_structures_other_dominant_fold_geometry';
$cvarray['Other Ellipse Type']='_3d_structures_other_ellipse_type';
$cvarray['Other Ellipsoid Type']='_3d_structures_other_ellipsoid_type';
$cvarray['Other Fabric Description']='_3d_structures_other_fabric_description';
$cvarray['Other Non-ellipsoidal Type']='_3d_structures_other_non_ellipsoidal_type';
$cvarray['Other Structure Description']='_3d_structures_other_structure_description';
$cvarray['Soft Sediment Deformation Description']='_3d_structures_soft_sediment_def_description';
$cvarray['Fabric Notes']='_3d_structures_struct_notes';
$cvarray['Tectonite Character']='_3d_structures_tectonite_character';
$cvarray['Tectonite Type']='_3d_structures_tectonite_type';
$cvarray['Tightness / Interlimb Angle']='_3d_structures_tightness';
$cvarray['Type']='_3d_structures_type';
$cvarray['Vergence']='_3d_structures_vergence';
$cvarray['Wavelength (m)']='_3d_structures_wavelength_m';
$cvvals->_3d_structures = $cvarray;


unset($cvarray);
$cvarray=array();

$cvarray['Inferences:']='selectgroup';
$cvarray['Description Of Outcrop']='inferences_description_of_outcrop';
$cvarray['Notes']='inferences_notes';
$cvarray['Outcrop In Place']='inferences_outcrop_in_place';
$cvarray['Related Rosetta Outcrop']='inferences_related_rosetta_outcrop';
$cvarray['Rosetta Outcrop']='inferences_rosetta_outcrop';
$cvvals->inferences = $cvarray;


unset($cvarray);
$cvarray=array();

$cvarray['Orientation Data:']='selectgroup';
$cvarray['Alteration Zone Type']='orientation_data_alteration_zone';
$cvarray['Bedding Type; Hint: Bedding defined by? change in lithology or sedimentary feature']='orientation_data_bedding_type';
$cvarray['Contact Type; Hint: Specific contacts types under depositional, igneous and metamorphic']='orientation_data_contact_type';
$cvarray['Damage Zone Type']='orientation_data_damage_zone';
$cvarray['Damage Zone defined by']='orientation_data_damage_zone_defined_by';
$cvarray['Lineation Defined by']='orientation_data_defined_by';
$cvarray['Dip']='orientation_data_dip';
$cvarray['Dip Direction']='orientation_data_dip_direction';
$cvarray['Directional Indicators']='orientation_data_dir_indicators';
$cvarray['Directional Indicators; Hint: Specific sense of shear indicator, such as Riedel shears, S-C fabrics, drag folds, etc.']='orientation_data_directional_indicators';
$cvarray['Enveloping Surface Features']='orientation_data_enveloping_surface';
$cvarray['Enveloping Surface Features Geometry']='orientation_data_enveloping_surface_geometry';
$cvarray['Plane Facing; Hint: Orientation of plane relative to original position e.g., upright, overturned, vertical']='orientation_data_facing';
$cvarray['Plane Facing Defined By; Hint: Criteria for facing direction: e.g., stratigraphy, facing iindicators, other']='orientation_data_facing_defined_by';
$cvarray['Fault Zone or Shear Zone Type']='orientation_data_fault_or_sz';
$cvarray['Type of Fault or Shear Zone Boundary; Hint: Specific type: e.g., dextral, sinistral, normal, reverse, oblique']='orientation_data_fault_or_sz_type';
$cvarray['Linear Feature Type; Hint: Specific lineation type: e.g., striation, groove mark, intersection, fold hinge, alignment, many others']='orientation_data_feature_type';
$cvarray['Foliation Defined by']='orientation_data_foliation_defined_by';
$cvarray['Foliation Type; Hint: Specific type of foliation defined by planar and/or linear elements']='orientation_data_foliation_type';
$cvarray['Fracture Defined by']='orientation_data_fracture_defined_by';
$cvarray['Fracture Type; Hint: Specific type of fracture: e.g., joint, shear or others']='orientation_data_fracture_type';
$cvarray['Fracture Zone Type']='orientation_data_fracture_zone';
$cvarray['Fracture Zone Boundary Define By']='orientation_data_fracture_zone_def_by';
$cvarray['Geologic Age of Structure']='orientation_data_geo_age';
$cvarray['Injection Structure Type']='orientation_data_injection_type';
$cvarray['Intrusive Body Type']='orientation_data_intrusive_body_type';
$cvarray['Label; Hint: If a label is not specified a default label will be given.']='orientation_data_label';
$cvarray['Planar Feature Length (m)']='orientation_data_length';
$cvarray['Maximum Age of Structure (Ma)']='orientation_data_max_age';
$cvarray['Justification of Maximum Age']='orientation_data_max_age_just';
$cvarray['Minimum Age of Structure (Ma)']='orientation_data_min_age';
$cvarray['Justification of Minimum Age']='orientation_data_min_age_just';
$cvarray['Movement; Hint: Relative movement across structure']='orientation_data_movement';
$cvarray['Movement Amount (m); Hint: How much movement?']='orientation_data_movement_amount_m';
$cvarray['Movement Amount Qualifier']='orientation_data_movement_amount_qualifier';
$cvarray['Movement Justification; Hint: Offset feature types used to determine relative movement']='orientation_data_movement_justification';
$cvarray['Line Notes']='orientation_data_notes';
$cvarray['Other Contact Type']='orientation_data_other_contact_type';
$cvarray['Other Depositional Contact Type']='orientation_data_other_dep_contact_type';
$cvarray['Other Directional Indicator']='orientation_data_other_dir_indicators';
$cvarray['Other Directional Indicator']='orientation_data_other_directional_indic';
$cvarray['Other Plane Facing Defined By']='orientation_data_other_facing_defined_by';
$cvarray['Other Fault Zone or Shear Zone Type']='orientation_data_other_fault_or_sz';
$cvarray['Other Fault or Shear Zone Boundary Type']='orientation_data_other_fault_or_sz_type';
$cvarray['Other Linear Feature']='orientation_data_other_feature';
$cvarray['Other Foliation Type']='orientation_data_other_foliation_type';
$cvarray['Other Fracture Type']='orientation_data_other_fracture_type';
$cvarray['Other Igneous Contact Type']='orientation_data_other_ig_contact_type';
$cvarray['Other Intrusive Body']='orientation_data_other_intrusive_body';
$cvarray['Other Metamorphic Contact Type']='orientation_data_other_met_contact_type';
$cvarray['Other Movement']='orientation_data_other_movement';
$cvarray['Other Movement Justification']='orientation_data_other_movement_justification';
$cvarray['Other Surface Geometry Type']='orientation_data_other_surface_geometry';
$cvarray['Other Vein Array']='orientation_data_other_vein_array';
$cvarray['Other Vein Mineral']='orientation_data_other_vein_fill';
$cvarray['Plunge']='orientation_data_plunge';
$cvarray['Linear Measurement Quality; Hint: Quality of linear feature or of measurement']='orientation_data_quality';
$cvarray['Rake; Hint: down dip angle from strike on plane (0-180)?']='orientation_data_rake';
$cvarray['Rake Calculated?']='orientation_data_rake_calculated';
$cvarray['Stratigraphic Type']='orientation_data_strat_type';
$cvarray['Strike; Hint: Azimuth in degrees']='orientation_data_strike';
$cvarray['Tabular Thickness Type/Tabularity']='orientation_data_tabularity';
$cvarray['Planar Feature Thickness (m)']='orientation_data_thickness';
$cvarray['Trend; Hint: Azimuth in degrees']='orientation_data_trend';
$cvarray['Type']='orientation_data_type';
$cvarray['Vein Array']='orientation_data_vein_array';
$cvarray['Vein Mineral Fill; Hint: Mineral filling veins: calcite, quartz or other']='orientation_data_vein_fill';
$cvarray['Vein Type; Hint: Specific type: e.g., antitaxial, syntaxial, normal, oblique']='orientation_data_vein_type';
$cvarray['Vorticity Type; Hint: Clockwise or counterclockwise looking down plunge']='orientation_data_vorticity';
$cvvals->orientation_data = $cvarray;


unset($cvarray);
$cvarray=array();

$cvarray['Samples:']='selectgroup';
$cvarray['Degree of Weathering']='samples_degree_of_weathering';
$cvarray['Inplaceness of Sample']='samples_inplaceness_of_sample';
$cvarray['Label; Hint: If a label is not specified a default label will be given.']='samples_label';
$cvarray['Main Sampling Purpose']='samples_main_sampling_purpose';
$cvarray['Material Type']='samples_material_type';
$cvarray['Oriented Sample']='samples_oriented_sample';
$cvarray['Other Material Type']='samples_other_material_type';
$cvarray['Other Sampling Purpose']='samples_other_sampling_purpose';
$cvarray['Sample Description']='samples_sample_description';
$cvarray['Sample Specific ID/Name']='samples_sample_id_name';
$cvarray['Sample Notes']='samples_sample_notes';
$cvarray['Sample Orientation Notes']='samples_sample_orientation_notes';
$cvarray['Sample Size']='samples_sample_size';
$cvvals->samples = $cvarray;


unset($cvarray);
$cvarray=array();

$cvarray['Trace:']='selectgroup';
$cvarray['Antropogenic Feature']='trace_antropogenic_feature';
$cvarray['Contact Type']='trace_contact_type';
$cvarray['Depositional Contact Type']='trace_depositional_contact_type';
$cvarray['Fold Attitude']='trace_fold_attitude';
$cvarray['Fold Type; Hint: What is the shape of the fold when looking down-plunge?']='trace_fold_type';
$cvarray['Geologic Structure Type']='trace_geologic_structure_type';
$cvarray['Geomorphic Feature']='trace_geomorphic_feature';
$cvarray['Intrusive Contact Type']='trace_intrusive_contact_type';
$cvarray['Marker Layer Details']='trace_marker_layer_details';
$cvarray['Metamorphic Contact Type']='trace_metamorphic_contact_type';
$cvarray['Other Anthropogenic Feature']='trace_other_anthropogenic_feature';
$cvarray['Other Contact Type']='trace_other_contact_type';
$cvarray['Other Depositional Contact']='trace_other_depositional_type';
$cvarray['Other Feature']='trace_other_feature';
$cvarray['Other Fold Attitude']='trace_other_fold_attitude';
$cvarray['Other Fold Type']='trace_other_fold_type';
$cvarray['Other Geomorphic Feature']='trace_other_geomorphic_feature';
$cvarray['Other Intrusive Contact']='trace_other_intrusive_contact';
$cvarray['Other Metamorphic Contact']='trace_other_metamorphic_contact';
$cvarray['Other Other Features']='trace_other_other_feature';
$cvarray['Other Other Structural Zone']='trace_other_other_structural_zone';
$cvarray['Other Shear Sense']='trace_other_shear_sense';
$cvarray['Other Structural Zones']='trace_other_structural_zones';
$cvarray['Other Trace Character']='trace_other_trace_character';
$cvarray['Other Trace Quality']='trace_other_trace_quality';
$cvarray['Shear Sense']='trace_shear_sense';
$cvarray['Trace Character']='trace_trace_character';
$cvarray['Trace Feature']='trace_trace_feature';
$cvarray['Trace Notes']='trace_trace_notes';
$cvarray['Trace Quality']='trace_trace_quality';
$cvarray['Trace Type']='trace_trace_type';
$cvvals->trace = $cvarray;











































































/*
$cvvals = new stdClass();

unset($cvarray);
$cvarray=array();

$cvarray['Sample Information:']='selectgroup';
$cvarray['Sample specific ID / Name']='SAMPLE_sample_id_name';
$cvarray['Oriented Sample']='SAMPLE_oriented_sample';
$cvarray['Sample Orientation Notes']='SAMPLE_sample_orientation_notes';
$cvarray['Sample Description']='SAMPLE_sample_description';
$cvarray['Material Type']='SAMPLE_material_type';
$cvarray['Other Material Type']='SAMPLE_other_material_type';
$cvarray['Sample Size']='SAMPLE_sample_size';
$cvarray['Main Sampling Purpose']='SAMPLE_main_sampling_purpose';
$cvarray['Other Sampling Purpose']='SAMPLE_other_sampling_purpose';
$cvarray['Intactness of Sample']='SAMPLE_intactness_of_sample';
$cvarray['Sample Notes']='SAMPLE_sample_notes';

$cvvals->sample = $cvarray;

unset($cvarray);
$cvarray=array();

$cvarray['Planar Orientation:']='selectgroup';
$cvarray['Strike']='PLANARORIENTATION_strike';
$cvarray['Dip Direction']='PLANARORIENTATION_dip_direction';
$cvarray['Dip']='PLANARORIENTATION_dip';
$cvarray['Plane Facing']='PLANARORIENTATION_facing';
$cvarray['Plane Facing Defined By']='PLANARORIENTATION_facing_defined_by';
$cvarray['Other Plane Facing Defined By']='PLANARORIENTATION_other_facing_defined_by';
$cvarray['Planar Measurement Quality']='PLANARORIENTATION_quality';
$cvarray['Planar Feature Type']='PLANARORIENTATION_feature_type';
$cvarray['Bedding Type']='PLANARORIENTATION_bedding_type';
$cvarray['Contact Type']='PLANARORIENTATION_contact_type';
$cvarray['Other Contact Type']='PLANARORIENTATION_other_contact_type';
$cvarray['Other Depositional Contact Type']='PLANARORIENTATION_other_dep_contact_type';
$cvarray['Other Igneous Contact Type']='PLANARORIENTATION_other_ig_contact_type';
$cvarray['Other Metamorphic Contact Type']='PLANARORIENTATION_other_met_contact_type';
$cvarray['Foliation Type']='PLANARORIENTATION_foliation_type';
$cvarray['Other Foliation Type']='PLANARORIENTATION_other_foliation_type';
$cvarray['Foliation Defined by']='PLANARORIENTATION_foliation_defined_by';
$cvarray['Fracture Type']='PLANARORIENTATION_fracture_type';
$cvarray['Other Fracture Type']='PLANARORIENTATION_other_fracture_type';
$cvarray['Fracture Defined by']='PLANARORIENTATION_fracture_defined_by';
$cvarray['Vein Type']='PLANARORIENTATION_vein_type';
$cvarray['Vein Mineral Fill']='PLANARORIENTATION_vein_fill';
$cvarray['Other Vein Mineral']='PLANARORIENTATION_other_vein_fill';
$cvarray['Type of Fault or Shear Zone Boundary']='PLANARORIENTATION_fault_or_sz_type';
$cvarray['Other Fault or Shear Zone Boundary Type']='PLANARORIENTATION_other_fault_or_sz_type';
$cvarray['Movement']='PLANARORIENTATION_movement';
$cvarray['Other Movement']='PLANARORIENTATION_other_movement';
$cvarray['Movement Justification']='PLANARORIENTATION_movement_justification';
$cvarray['Other Movement Justification']='PLANARORIENTATION_other_movement_justification';
$cvarray['Directional Indicators']='PLANARORIENTATION_directional_indicators';
$cvarray['Other Directional Indicator']='PLANARORIENTATION_other_directional_indic';
$cvarray['Other Geological Structures']='PLANARORIENTATION_other_structures';
$cvarray['Planar Feature Thickness (m)']='PLANARORIENTATION_thickness';
$cvarray['Planar Feature Length (m)']='PLANARORIENTATION_length';
$cvarray['Planar Feature Notes']='PLANARORIENTATION_notes';
$cvarray['Minimum Age of Structure (Ma)']='PLANARORIENTATION_min_age';
$cvarray['Justification of Minimum Age']='PLANARORIENTATION_min_age_just';
$cvarray['Maximum Age of Structure (Ma)']='PLANARORIENTATION_max_age';
$cvarray['Justification of Maximum Age']='PLANARORIENTATION_max_age_just';
$cvarray['Geologic Age of Structure']='PLANARORIENTATION_geo_age';

$cvvals->planarorientation = $cvarray;

unset($cvarray);
$cvarray=array();

$cvarray['Linear Orientation:']='selectgroup';
$cvarray['Trend']='LINEARORIENTATION_trend';
$cvarray['Plunge']='LINEARORIENTATION_plunge';
$cvarray['Rake']='LINEARORIENTATION_rake';
$cvarray['Rake Calculated?']='LINEARORIENTATION_rake_calculated';
$cvarray['Linear Measurement Quality']='LINEARORIENTATION_quality';
$cvarray['Linear Feature Type']='LINEARORIENTATION_feature_type';
$cvarray['Vorticity Type']='LINEARORIENTATION_vorticity';
$cvarray['Other Linear Feature']='LINEARORIENTATION_other_feature';
$cvarray['Lineation Defined by']='LINEARORIENTATION_defined_by';
$cvarray['Minimum Age of Structure (Ma)']='LINEARORIENTATION_min_age';
$cvarray['Justification of Minimum Age']='LINEARORIENTATION_min_age_just';
$cvarray['Maximum Age of Structure (Ma)']='LINEARORIENTATION_max_age';
$cvarray['Justification of Maximum Age']='LINEARORIENTATION_max_age_just';
$cvarray['Geologic Age of Structure']='LINEARORIENTATION_geo_age';
$cvarray['Line Notes']='LINEARORIENTATION_notes';

$cvvals->linearorientation = $cvarray;

unset($cvarray);
$cvarray=array();

$cvarray['Tabular Orientation:']='selectgroup';
$cvarray['Strike']='TABULARORIENTATION_strike';
$cvarray['Dip Direction']='TABULARORIENTATION_dip_direction';
$cvarray['Dip']='TABULARORIENTATION_dip';
$cvarray['Feature Facing']='TABULARORIENTATION_facing';
$cvarray['Facing Direction Defined By']='TABULARORIENTATION_facing_defined_by';
$cvarray['Other Facing Defined By']='TABULARORIENTATION_other_facing_defined_by';
$cvarray['Tabular Feature Orientation Quality']='TABULARORIENTATION_quality';
$cvarray['Tabular Feature Type']='TABULARORIENTATION_feature_type';
$cvarray['Other Tabular Feature']='TABULARORIENTATION_other_feature';
$cvarray['Stratigraphic Type']='TABULARORIENTATION_strat_type';
$cvarray['Intrusive Body Type']='TABULARORIENTATION_intrusive_body_type';
$cvarray['Other Intrusive Body']='TABULARORIENTATION_other_intrusive_body';
$cvarray['Injection Structure Type']='TABULARORIENTATION_injection_type';
$cvarray['Vein Type']='TABULARORIENTATION_vein_type';
$cvarray['Vein Mineral Fill']='TABULARORIENTATION_vein_fill';
$cvarray['Other Vein Mineral']='TABULARORIENTATION_other_vein_fill';
$cvarray['Vein Array']='TABULARORIENTATION_vein_array';
$cvarray['Other Vein Array']='TABULARORIENTATION_other_vein_array';
$cvarray['Fracture Zone Type']='TABULARORIENTATION_fracture_zone';
$cvarray['Fracture Zone Boundary Define By']='TABULARORIENTATION_fracture_zone_def_by';
$cvarray['Fault Zone or Shear Zone Type']='TABULARORIENTATION_fault_or_sz';
$cvarray['Other Fault Zone or Shear Zone Type']='TABULARORIENTATION_other_fault_or_sz';
$cvarray['Movement']='TABULARORIENTATION_movement';
$cvarray['Other Movement']='TABULARORIENTATION_other_movement';
$cvarray['Movement Justification']='TABULARORIENTATION_movement_justification';
$cvarray['Other Movement Justification']='TABULARORIENTATION_other_movement_justification';
$cvarray['Directional Indicators']='TABULARORIENTATION_dir_indicators';
$cvarray['Other Directional Indicator']='TABULARORIENTATION_other_dir_indicators';
$cvarray['Damage Zone Type']='TABULARORIENTATION_damage_zone';
$cvarray['Damage Zone defined by']='TABULARORIENTATION_damage_zone_defined_by';
$cvarray['Alteration Zone Type']='TABULARORIENTATION_alteration_zone';
$cvarray['Enveloping Surface Features']='TABULARORIENTATION_enveloping_surface';
$cvarray['Enveloping Surface Features Geometry']='TABULARORIENTATION_enveloping_surface_geometry';
$cvarray['Other Surface Geometry Type']='TABULARORIENTATION_other_surface_geometry';
$cvarray['Tabular Feature Thickness (m)']='TABULARORIENTATION_thickness';
$cvarray['Tabular Thickness Type/Tabularity']='TABULARORIENTATION_tabularity';
$cvarray['Tabular Feature Length (m)']='TABULARORIENTATION_length';
$cvarray['Tabular Feature Defined By']='TABULARORIENTATION_defined_by';
$cvarray['Tabular Feature Notes']='TABULARORIENTATION_notes';
$cvarray['Minimum Age of Structure (Ma)']='TABULARORIENTATION_min_age';
$cvarray['Justification of Minimum Age']='TABULARORIENTATION_min_age_just';
$cvarray['Maximum Age of Structure (Ma)']='TABULARORIENTATION_max_age';
$cvarray['Justification of Maximum Age']='TABULARORIENTATION_max_age_just';
$cvarray['Geologic Age of Structure']='TABULARORIENTATION_geo_age';

$cvvals->tabularorientation = $cvarray;

unset($cvarray);
$cvarray=array();

$cvarray['Rock Unit:']='selectgroup';
$cvarray['Unit Label (abbreviation)']='ROCKUNIT_unit_label_abbreviation';
$cvarray['Unit or Formation Name']='ROCKUNIT_map_unit_name';
$cvarray['Member Name']='ROCKUNIT_member_name';
$cvarray['Submember Name']='ROCKUNIT_submember_name';
$cvarray['Absolute Age of Geologic Unit (Ma)']='ROCKUNIT_absolute_age_of_geologic_unit';
$cvarray['Age Uncertainty (Ma)']='ROCKUNIT_age_uncertainty';
$cvarray['Unit Type']='ROCKUNIT_group_unit_type';
$cvarray['Rock Type']='ROCKUNIT_rock_type';
$cvarray['Sediment Type']='ROCKUNIT_sediment_type';
$cvarray['Other Sediment Type']='ROCKUNIT_other_sediment_type';
$cvarray['Sedimentary Rock Type']='ROCKUNIT_sedimentary_rock_type';
$cvarray['Other Sedimentary Rock Type']='ROCKUNIT_other_sedimentary_rock_type';
$cvarray['Igneous Rock Class']='ROCKUNIT_igneous_rock_class';
$cvarray['Volcanic Rock Type']='ROCKUNIT_volcanic_rock_type';
$cvarray['Other Volcanic Rock Type']='ROCKUNIT_other_volcanic_rock_type';
$cvarray['Plutonic Rock Types']='ROCKUNIT_plutonic_rock_types';
$cvarray['Other Plutonic Rock Type']='ROCKUNIT_other_plutonic_rock_type';
$cvarray['Metamorphic Rock Types']='ROCKUNIT_metamorphic_rock_types';
$cvarray['Other Metamorphic Rock Type']='ROCKUNIT_other_metamorphic_rock_type';
$cvarray['Geologic Age']='ROCKUNIT_group_geologic_age';
$cvarray['Epoch']='ROCKUNIT_epoch';
$cvarray['Other Epoch']='ROCKUNIT_other_epoch';
$cvarray['Period']='ROCKUNIT_period';
$cvarray['Era']='ROCKUNIT_era';
$cvarray['Eon']='ROCKUNIT_eon';
$cvarray['Age Modifier']='ROCKUNIT_age_modifier';
$cvarray['Description']='ROCKUNIT_description';
$cvarray['Notes']='ROCKUNIT_Notes';

$cvvals->rockunit = $cvarray;

unset($cvarray);
$cvarray=array();

$cvarray['Trace/Line:']='selectgroup';
$cvarray['Trace/Line Feature']='TRACELINE_Trace_Line_Feature';
$cvarray['Trace Type']='TRACELINE_Trace_Type';
$cvarray['Contact Type']='TRACELINE_contact_type';
$cvarray['Other Contact Type']='TRACELINE_other_contact_type';
$cvarray['Depositional Contact Type']='TRACELINE_depositional_contact_type';
$cvarray['Other Depositional Contact']='TRACELINE_other_depositional_type';
$cvarray['Intrusive Contact Type']='TRACELINE_intrusive_contact_type';
$cvarray['Other Intrusive Contact']='TRACELINE_other_intrusive_contact';
$cvarray['Metamorphic Contact Type']='TRACELINE_metamorphic_contact_type';
$cvarray['Other Metamorphic Contact']='TRACELINE_other_metamorphic_contact';
$cvarray['Marker Layer Details']='TRACELINE_marker_layer_details';
$cvarray['Geologic Structure Type']='TRACELINE_geologic_structure_type';
$cvarray['Shear Sense']='TRACELINE_shear_sense';
$cvarray['Other Shear Sense']='TRACELINE_other_shear_sense';
$cvarray['Other Structural Zones']='TRACELINE_other_structural_zones';
$cvarray['Other Other Structural Zone']='TRACELINE_other_other_structural_zone';
$cvarray['Fold Type']='TRACELINE_fold_type';
$cvarray['Other Fold Type']='TRACELINE_other_fold_type';
$cvarray['Fold Attitude']='TRACELINE_fold_attitude';
$cvarray['Other Fold Attitude']='TRACELINE_other_fold_attitude';
$cvarray['Geomorphic Feature']='TRACELINE_geomorphic_feature';
$cvarray['Other Geomorphic Feature']='TRACELINE_other_geomorphic_feature';
$cvarray['Antropogenic Feature']='TRACELINE_antropogenic_feature';
$cvarray['Other Anthropogenic Feature']='TRACELINE_other_anthropogenic_feature';
$cvarray['Other Feature']='TRACELINE_other_feature';
$cvarray['Other Other Features']='TRACELINE_other_other_feature';
$cvarray['Trace Quality']='TRACELINE_trace_quality';
$cvarray['Other Trace Quality']='TRACELINE_other_trace_quality';
$cvarray['Trace Character']='TRACELINE_trace_character';
$cvarray['Other Trace Character']='TRACELINE_other_trace_character';
$cvarray['Trace Notes']='TRACELINE_trace_notes';

$cvvals->traceline = $cvarray;




















































$cvarray['Sample Information:']='selectgroup';
$cvarray['SAMPLE_sample_id_name']='Sample specific ID / Name';
$cvarray['SAMPLE_oriented_sample']='Oriented Sample';
$cvarray['SAMPLE_sample_orientation_notes']='Sample Orientation Notes';
$cvarray['SAMPLE_sample_description']='Sample Description';
$cvarray['SAMPLE_material_type']='Material Type';
$cvarray['SAMPLE_other_material_type']='Other Material Type';
$cvarray['SAMPLE_sample_size']='Sample Size';
$cvarray['SAMPLE_main_sampling_purpose']='Main Sampling Purpose';
$cvarray['SAMPLE_other_sampling_purpose']='Other Sampling Purpose';
$cvarray['SAMPLE_intactness_of_sample']='Intactness of Sample';
$cvarray['SAMPLE_sample_notes']='Sample Notes';

$cvvals->sample = $cvarray;

unset($cvarray);
$cvarray=array();

$cvarray['Planar Orientation:']='selectgroup';
$cvarray['PLANARORIENTATION_strike']='Strike';
$cvarray['PLANARORIENTATION_dip_direction']='Dip Direction';
$cvarray['PLANARORIENTATION_dip']='Dip';
$cvarray['PLANARORIENTATION_facing']='Plane Facing';
$cvarray['PLANARORIENTATION_facing_defined_by']='Plane Facing Defined By';
$cvarray['PLANARORIENTATION_other_facing_defined_by']='Other Plane Facing Defined By';
$cvarray['PLANARORIENTATION_quality']='Planar Measurement Quality';
$cvarray['PLANARORIENTATION_feature_type']='Planar Feature Type';
$cvarray['PLANARORIENTATION_bedding_type']='Bedding Type';
$cvarray['PLANARORIENTATION_contact_type']='Contact Type';
$cvarray['PLANARORIENTATION_other_contact_type']='Other Contact Type';
$cvarray['PLANARORIENTATION_other_dep_contact_type']='Other Depositional Contact Type';
$cvarray['PLANARORIENTATION_other_ig_contact_type']='Other Igneous Contact Type';
$cvarray['PLANARORIENTATION_other_met_contact_type']='Other Metamorphic Contact Type';
$cvarray['PLANARORIENTATION_foliation_type']='Foliation Type';
$cvarray['PLANARORIENTATION_other_foliation_type']='Other Foliation Type';
$cvarray['PLANARORIENTATION_foliation_defined_by']='Foliation Defined by';
$cvarray['PLANARORIENTATION_fracture_type']='Fracture Type';
$cvarray['PLANARORIENTATION_other_fracture_type']='Other Fracture Type';
$cvarray['PLANARORIENTATION_fracture_defined_by']='Fracture Defined by';
$cvarray['PLANARORIENTATION_vein_type']='Vein Type';
$cvarray['PLANARORIENTATION_vein_fill']='Vein Mineral Fill';
$cvarray['PLANARORIENTATION_other_vein_fill']='Other Vein Mineral';
$cvarray['PLANARORIENTATION_fault_or_sz_type']='Type of Fault or Shear Zone Boundary';
$cvarray['PLANARORIENTATION_other_fault_or_sz_type']='Other Fault or Shear Zone Boundary Type';
$cvarray['PLANARORIENTATION_movement']='Movement';
$cvarray['PLANARORIENTATION_other_movement']='Other Movement';
$cvarray['PLANARORIENTATION_movement_justification']='Movement Justification';
$cvarray['PLANARORIENTATION_other_movement_justification']='Other Movement Justification';
$cvarray['PLANARORIENTATION_directional_indicators']='Directional Indicators';
$cvarray['PLANARORIENTATION_other_directional_indic']='Other Directional Indicator';
$cvarray['PLANARORIENTATION_other_structures']='Other Geological Structures';
$cvarray['PLANARORIENTATION_thickness']='Planar Feature Thickness (m)';
$cvarray['PLANARORIENTATION_length']='Planar Feature Length (m)';
$cvarray['PLANARORIENTATION_notes']='Planar Feature Notes';
$cvarray['PLANARORIENTATION_min_age']='Minimum Age of Structure (Ma)';
$cvarray['PLANARORIENTATION_min_age_just']='Justification of Minimum Age';
$cvarray['PLANARORIENTATION_max_age']='Maximum Age of Structure (Ma)';
$cvarray['PLANARORIENTATION_max_age_just']='Justification of Maximum Age';
$cvarray['PLANARORIENTATION_geo_age']='Geologic Age of Structure';

$cvvals->planarorientation = $cvarray;

unset($cvarray);
$cvarray=array();

$cvarray['Linear Orientation:']='selectgroup';
$cvarray['LINEARORIENTATION_trend']='Trend';
$cvarray['LINEARORIENTATION_plunge']='Plunge';
$cvarray['LINEARORIENTATION_rake']='Rake';
$cvarray['LINEARORIENTATION_rake_calculated']='Rake Calculated?';
$cvarray['LINEARORIENTATION_quality']='Linear Measurement Quality';
$cvarray['LINEARORIENTATION_feature_type']='Linear Feature Type';
$cvarray['LINEARORIENTATION_vorticity']='Vorticity Type';
$cvarray['LINEARORIENTATION_other_feature']='Other Linear Feature';
$cvarray['LINEARORIENTATION_defined_by']='Lineation Defined by';
$cvarray['LINEARORIENTATION_min_age']='Minimum Age of Structure (Ma)';
$cvarray['LINEARORIENTATION_min_age_just']='Justification of Minimum Age';
$cvarray['LINEARORIENTATION_max_age']='Maximum Age of Structure (Ma)';
$cvarray['LINEARORIENTATION_max_age_just']='Justification of Maximum Age';
$cvarray['LINEARORIENTATION_geo_age']='Geologic Age of Structure';
$cvarray['LINEARORIENTATION_notes']='Line Notes';

$cvvals->linearorientation = $cvarray;

unset($cvarray);
$cvarray=array();

$cvarray['Tabluar Orientation:']='selectgroup';
$cvarray['TABULARORIENTATION_strike']='Strike';
$cvarray['TABULARORIENTATION_dip_direction']='Dip Direction';
$cvarray['TABULARORIENTATION_dip']='Dip';
$cvarray['TABULARORIENTATION_facing']='Feature Facing';
$cvarray['TABULARORIENTATION_facing_defined_by']='Facing Direction Defined By';
$cvarray['TABULARORIENTATION_other_facing_defined_by']='Other Facing Defined By';
$cvarray['TABULARORIENTATION_quality']='Tabular Feature Orientation Quality';
$cvarray['TABULARORIENTATION_feature_type']='Tabular Feature Type';
$cvarray['TABULARORIENTATION_other_feature']='Other Tabular Feature';
$cvarray['TABULARORIENTATION_strat_type']='Stratigraphic Type';
$cvarray['TABULARORIENTATION_intrusive_body_type']='Intrusive Body Type';
$cvarray['TABULARORIENTATION_other_intrusive_body']='Other Intrusive Body';
$cvarray['TABULARORIENTATION_injection_type']='Injection Structure Type';
$cvarray['TABULARORIENTATION_vein_type']='Vein Type';
$cvarray['TABULARORIENTATION_vein_fill']='Vein Mineral Fill';
$cvarray['TABULARORIENTATION_other_vein_fill']='Other Vein Mineral';
$cvarray['TABULARORIENTATION_vein_array']='Vein Array';
$cvarray['TABULARORIENTATION_other_vein_array']='Other Vein Array';
$cvarray['TABULARORIENTATION_fracture_zone']='Fracture Zone Type';
$cvarray['TABULARORIENTATION_fracture_zone_def_by']='Fracture Zone Boundary Define By';
$cvarray['TABULARORIENTATION_fault_or_sz']='Fault Zone or Shear Zone Type';
$cvarray['TABULARORIENTATION_other_fault_or_sz']='Other Fault Zone or Shear Zone Type';
$cvarray['TABULARORIENTATION_movement']='Movement';
$cvarray['TABULARORIENTATION_other_movement']='Other Movement';
$cvarray['TABULARORIENTATION_movement_justification']='Movement Justification';
$cvarray['TABULARORIENTATION_other_movement_justification']='Other Movement Justification';
$cvarray['TABULARORIENTATION_dir_indicators']='Directional Indicators';
$cvarray['TABULARORIENTATION_other_dir_indicators']='Other Directional Indicator';
$cvarray['TABULARORIENTATION_damage_zone']='Damage Zone Type';
$cvarray['TABULARORIENTATION_damage_zone_defined_by']='Damage Zone defined by';
$cvarray['TABULARORIENTATION_alteration_zone']='Alteration Zone Type';
$cvarray['TABULARORIENTATION_enveloping_surface']='Enveloping Surface Features';
$cvarray['TABULARORIENTATION_enveloping_surface_geometry']='Enveloping Surface Features Geometry';
$cvarray['TABULARORIENTATION_other_surface_geometry']='Other Surface Geometry Type';
$cvarray['TABULARORIENTATION_thickness']='Tabular Feature Thickness (m)';
$cvarray['TABULARORIENTATION_tabularity']='Tabular Thickness Type/Tabularity';
$cvarray['TABULARORIENTATION_length']='Tabular Feature Length (m)';
$cvarray['TABULARORIENTATION_defined_by']='Tabular Feature Defined By';
$cvarray['TABULARORIENTATION_notes']='Tabular Feature Notes';
$cvarray['TABULARORIENTATION_min_age']='Minimum Age of Structure (Ma)';
$cvarray['TABULARORIENTATION_min_age_just']='Justification of Minimum Age';
$cvarray['TABULARORIENTATION_max_age']='Maximum Age of Structure (Ma)';
$cvarray['TABULARORIENTATION_max_age_just']='Justification of Maximum Age';
$cvarray['TABULARORIENTATION_geo_age']='Geologic Age of Structure';

$cvvals->tabularorientation = $cvarray;

unset($cvarray);
$cvarray=array();

$cvarray['Rock Unit:']='selectgroup';
$cvarray['ROCKUNIT_unit_label_abbreviation']='Unit Label (abbreviation)';
$cvarray['ROCKUNIT_map_unit_name']='Unit or Formation Name';
$cvarray['ROCKUNIT_member_name']='Member Name';
$cvarray['ROCKUNIT_submember_name']='Submember Name';
$cvarray['ROCKUNIT_absolute_age_of_geologic_unit']='Absolute Age of Geologic Unit (Ma)';
$cvarray['ROCKUNIT_age_uncertainty']='Age Uncertainty (Ma)';
$cvarray['ROCKUNIT_group_unit_type']='Unit Type';
$cvarray['ROCKUNIT_rock_type']='Rock Type';
$cvarray['ROCKUNIT_sediment_type']='Sediment Type';
$cvarray['ROCKUNIT_other_sediment_type']='Other Sediment Type';
$cvarray['ROCKUNIT_sedimentary_rock_type']='Sedimentary Rock Type';
$cvarray['ROCKUNIT_other_sedimentary_rock_type']='Other Sedimentary Rock Type';
$cvarray['ROCKUNIT_igneous_rock_class']='Igneous Rock Class';
$cvarray['ROCKUNIT_volcanic_rock_type']='Volcanic Rock Type';
$cvarray['ROCKUNIT_other_volcanic_rock_type']='Other Volcanic Rock Type';
$cvarray['ROCKUNIT_plutonic_rock_types']='Plutonic Rock Types';
$cvarray['ROCKUNIT_other_plutonic_rock_type']='Other Plutonic Rock Type';
$cvarray['ROCKUNIT_metamorphic_rock_types']='Metamorphic Rock Types';
$cvarray['ROCKUNIT_other_metamorphic_rock_type']='Other Metamorphic Rock Type';
$cvarray['ROCKUNIT_group_geologic_age']='Geologic Age';
$cvarray['ROCKUNIT_epoch']='Epoch';
$cvarray['ROCKUNIT_other_epoch']='Other Epoch';
$cvarray['ROCKUNIT_period']='Period';
$cvarray['ROCKUNIT_era']='Era';
$cvarray['ROCKUNIT_eon']='Eon';
$cvarray['ROCKUNIT_age_modifier']='Age Modifier';
$cvarray['ROCKUNIT_description']='Description';
$cvarray['ROCKUNIT_Notes']='Notes';

$cvvals->rockunit = $cvarray;

unset($cvarray);
$cvarray=array();

$cvarray['Trace/Line:']='selectgroup';
$cvarray['TRACELINE_Trace_Line_Feature']='Trace/Line Feature';
$cvarray['TRACELINE_Trace_Type']='Trace Type';
$cvarray['TRACELINE_contact_type']='Contact Type';
$cvarray['TRACELINE_other_contact_type']='Other Contact Type';
$cvarray['TRACELINE_depositional_contact_type']='Depositional Contact Type';
$cvarray['TRACELINE_other_depositional_type']='Other Depositional Contact';
$cvarray['TRACELINE_intrusive_contact_type']='Intrusive Contact Type';
$cvarray['TRACELINE_other_intrusive_contact']='Other Intrusive Contact';
$cvarray['TRACELINE_metamorphic_contact_type']='Metamorphic Contact Type';
$cvarray['TRACELINE_other_metamorphic_contact']='Other Metamorphic Contact';
$cvarray['TRACELINE_marker_layer_details']='Marker Layer Details';
$cvarray['TRACELINE_geologic_structure_type']='Geologic Structure Type';
$cvarray['TRACELINE_shear_sense']='Shear Sense';
$cvarray['TRACELINE_other_shear_sense']='Other Shear Sense';
$cvarray['TRACELINE_other_structural_zones']='Other Structural Zones';
$cvarray['TRACELINE_other_other_structural_zone']='Other Other Structural Zone';
$cvarray['TRACELINE_fold_type']='Fold Type';
$cvarray['TRACELINE_other_fold_type']='Other Fold Type';
$cvarray['TRACELINE_fold_attitude']='Fold Attitude';
$cvarray['TRACELINE_other_fold_attitude']='Other Fold Attitude';
$cvarray['TRACELINE_geomorphic_feature']='Geomorphic Feature';
$cvarray['TRACELINE_other_geomorphic_feature']='Other Geomorphic Feature';
$cvarray['TRACELINE_antropogenic_feature']='Antropogenic Feature';
$cvarray['TRACELINE_other_anthropogenic_feature']='Other Anthropogenic Feature';
$cvarray['TRACELINE_other_feature']='Other Feature';
$cvarray['TRACELINE_other_other_feature']='Other Other Features';
$cvarray['TRACELINE_trace_quality']='Trace Quality';
$cvarray['TRACELINE_other_trace_quality']='Other Trace Quality';
$cvarray['TRACELINE_trace_character']='Trace Character';
$cvarray['TRACELINE_other_trace_character']='Other Trace Character';
$cvarray['TRACELINE_trace_notes']='Trace Notes';

$cvvals->traceline = $cvarray;


























































//$cvarray['Plane type']='plane_type';
$cvarray['Planar Feature Type']='planar_feature_type';
$cvarray['Strike']='strike';
$cvarray['Dip']='dip';
$cvarray['Planar Surface Quality']='planar_surface_quality';
$cvarray['Planar Measurement Quality']='planar_measurement_quality';
$cvarray['Specific Strike Type']='specific_strike_type';
$cvarray['Marker Layer Details']='marker_layer_details';
$cvarray['PlanesEndSelectGroup']='endselectgroup';


$cvarray['Deformation']='selectgroup';
$cvarray['Tectonite Label']='tectonite_label';
$cvarray['Gneissic Band Spacing (cm)']='gneissic_band_spacing_cm';
$cvarray['Average Grain Size (mm) in Gneissic Bands']='average_grain_size_mm_in_gne';
$cvarray['Vein mineral']='vein_mineral';
$cvarray['Sense of shear']='sense_of_shear';
$cvarray['Movement Justification']='movement_justification';
$cvarray['Offset markers']='offset_marker';
$cvarray['Offset (m)']='offset_m';
$cvarray['Directional indicators']='directional_indicators';
$cvarray['Hanging wall unit label']='hanging_wall_unit_label';
$cvarray['Footwall unit label']='footwall_unit_label';
$cvarray['Thickness of Fault or Shear Zone (m)']='thickness_of_fault_or_shear_zo';
$cvarray['Minimum Age of Deformation (Ma)']='minimum_age_of_deformation_ma';
$cvarray['Maximum Age of Deformation (Ma)']='maximum_age_of_deformation_ma';
$cvarray['Specific Foliation Element being Measured']='specific_foliation_element';
$cvarray['Plane Facing']='plane_facing';
$cvarray['Facing Direction']='facing_direction';
$cvarray['DeformationEndSelectGroup']='endselectgroup';


$cvarray['Lines']='selectgroup';
//$cvarray['Lineation type']='lineation_type';
$cvarray['Linear Feature Type']='linear_feature_type';
$cvarray['Trend']='trend';
$cvarray['Plunge']='plunge';
$cvarray['Rake']='rake';
$cvarray['Rake Calculated?']='rake_calculated';
$cvarray['Linear Orientation Quality']='linear_orientation_quality';
$cvarray['Linear Measurement Quality']='linear_measurement_quality';
$cvarray['Specific Trend type']='specific_trend_type';
$cvarray['LinesEndSelectGroup']='endselectgroup';


$cvarray['Folds']='selectgroup';
$cvarray['Approximate Amplitude Scale of Related Folding']='approx_amplitude';
$cvarray['Dominant Fold Geometry']='fold_geometry';
$cvarray['Dominant Fold Shape']='fold_shape';
$cvarray['Dominant Fold Attitude']='fold_attitude';
$cvarray['Tightness / Interlimb Angle']='tightness';
$cvarray['Vergence']='vergence';
$cvarray['Vector Magnitude (meters)']='vector_magnitude_meters';
$cvarray['Structure Notes']='structure_notes';
$cvarray['FoldsEndSelectGroup']='endselectgroup';


$cvvals->measurements_and_observations = $cvarray;


unset($cvarray);
$cvarray=array();

$cvarray['Rock Description']='selectgroup';
$cvarray['Unit Label (abbreviation)']='unit_label_abbreviation';
$cvarray['Unit Label Age Symbol']='unit_label_age_symbol';
$cvarray['Formation Name']='formation_name';
$cvarray['Member Name']='member_name';
$cvarray['Submember Name']='submember_name';
$cvarray['Rock Type']='rock_type';
$cvarray['Specific rock type']='specific_rock_type';
$cvarray['Igneous Rock Class']='igneous_rock_class';
$cvarray['Description / Lithology']='description_lithology';
$cvarray['Absolute Age of Geologic Unit (Ma)']='absolute_age_of_geologic_unit_';
$cvarray['Eon']='eon';
$cvarray['Era']='era';
$cvarray['Period']='period';
$cvarray['Epoch']='epoch';
$cvarray['Age Modifier']='age_modifier';
$cvarray['RockDescriptionEndSelectGroup']='endselectgroup';

$cvvals->rock_description = $cvarray;


unset($cvarray);
$cvarray=array();

$cvarray['Sample']='selectgroup';
$cvarray['Sample ID']='sample_id';
$cvarray['Sample Orientation Strike']='sample_orientation_strike';
$cvarray['Sample Orientation Dip']='sample_orientation_dip';
$cvarray['Material Type']='material_type';
$cvarray['Material Details']='material_details';
$cvarray['Sample Size (cm)']='sample_size_cm';
$cvarray['Main Sampling Purpose']='main_sampling_purpose';
$cvarray['Sample Description']='sample_description';
$cvarray['Other Comments About Sampling']='other_comments_about_sampling';
$cvarray['Inferred Age of Sample (Ma)']='inferred_age_of_sample_ma';
$cvarray['SampleEndSelectGroup']='endselectgroup';


$cvvals->sample_locality = $cvarray;


unset($cvarray);
$cvarray=array();


$cvarray['Contacts']='selectgroup';
$cvarray['Contact Type']='contact_type';
$cvarray['Contact Quality']='contact_quality';
$cvarray['Contact Character']='contact_character';
$cvarray['Specific type']='specific_type';
$cvarray['Marker Layer Details']='marker_layer_details';
$cvarray['Sense of Shear']='sense_of_shear';
$cvarray['Movement Justification']='movement_justification';
$cvarray['Offset Markers']='offset_markers';
$cvarray['Offset (m)']='offset_m';
$cvarray['Directional Indicators']='directional_indicators';
$cvarray['Thickness of Fault or Shear Zone (m)']='thickness_of_fault_or_shear_zo';
$cvarray['Minimum Age of Deformation (Ma)']='minimum_age_of_deformation_ma';
$cvarray['Maximum Age of Deformation (Ma)']='maximum_age_of_deformation_ma';
$cvarray['Juxtaposes __________ rocks in the hanging wall....']='juxtaposes_rocks';
$cvarray['... against ________ rocks in the footwall.']='against_rocks';
$cvarray['Dominant Fold Geometry']='fold_geometry';
$cvarray['Dominant Fold Shape']='fold_shape';
$cvarray['Dominant Fold Attitude']='fold_attitude';
$cvarray['Tightness / Interlimb Angle']='tightness';
$cvarray['Vergence']='vergence';
$cvarray['ContactsEndSelectGroup']='endselectgroup';



$cvvals->contacts_and_traces = $cvarray;






































$cvvals = new stdClass();

unset($cvarray);
$cvarray=array();


$cvarray['Planes']='selectgroup';
//$cvarray['Plane type']='plane_type';
$cvarray['Planar Feature Type']='planar_feature_type';
$cvarray['Strike']='strike';
$cvarray['Dip']='dip';
$cvarray['Planar Surface Quality']='planar_surface_quality';
$cvarray['Planar Measurement Quality']='planar_measurement_quality';
$cvarray['Specific Strike Type']='specific_strike_type';
$cvarray['Marker Layer Details']='marker_layer_details';
$cvarray['PlanesEndSelectGroup']='endselectgroup';


$cvarray['Deformation']='selectgroup';
$cvarray['Tectonite Label']='tectonite_label';
$cvarray['Gneissic Band Spacing (cm)']='gneissic_band_spacing_cm';
$cvarray['Average Grain Size (mm) in Gneissic Bands']='average_grain_size_mm_in_gne';
$cvarray['Vein mineral']='vein_mineral';
$cvarray['Sense of shear']='sense_of_shear';
$cvarray['Movement Justification']='movement_justification';
$cvarray['Offset markers']='offset_marker';
$cvarray['Offset (m)']='offset_m';
$cvarray['Directional indicators']='directional_indicators';
$cvarray['Hanging wall unit label']='hanging_wall_unit_label';
$cvarray['Footwall unit label']='footwall_unit_label';
$cvarray['Thickness of Fault or Shear Zone (m)']='thickness_of_fault_or_shear_zo';
$cvarray['Minimum Age of Deformation (Ma)']='minimum_age_of_deformation_ma';
$cvarray['Maximum Age of Deformation (Ma)']='maximum_age_of_deformation_ma';
$cvarray['Specific Foliation Element being Measured']='specific_foliation_element';
$cvarray['Plane Facing']='plane_facing';
$cvarray['Facing Direction']='facing_direction';
$cvarray['DeformationEndSelectGroup']='endselectgroup';


$cvarray['Lines']='selectgroup';
//$cvarray['Lineation type']='lineation_type';
$cvarray['Linear Feature Type']='linear_feature_type';
$cvarray['Trend']='trend';
$cvarray['Plunge']='plunge';
$cvarray['Rake']='rake';
$cvarray['Rake Calculated?']='rake_calculated';
$cvarray['Linear Orientation Quality']='linear_orientation_quality';
$cvarray['Linear Measurement Quality']='linear_measurement_quality';
$cvarray['Specific Trend type']='specific_trend_type';
$cvarray['LinesEndSelectGroup']='endselectgroup';


$cvarray['Folds']='selectgroup';
$cvarray['Approximate Amplitude Scale of Related Folding']='approx_amplitude';
$cvarray['Dominant Fold Geometry']='fold_geometry';
$cvarray['Dominant Fold Shape']='fold_shape';
$cvarray['Dominant Fold Attitude']='fold_attitude';
$cvarray['Tightness / Interlimb Angle']='tightness';
$cvarray['Vergence']='vergence';
$cvarray['Vector Magnitude (meters)']='vector_magnitude_meters';
$cvarray['Structure Notes']='structure_notes';
$cvarray['FoldsEndSelectGroup']='endselectgroup';


$cvvals->measurements_and_observations = $cvarray;


unset($cvarray);
$cvarray=array();

$cvarray['Rock Description']='selectgroup';
$cvarray['Unit Label (abbreviation)']='unit_label_abbreviation';
$cvarray['Unit Label Age Symbol']='unit_label_age_symbol';
$cvarray['Formation Name']='formation_name';
$cvarray['Member Name']='member_name';
$cvarray['Submember Name']='submember_name';
$cvarray['Rock Type']='rock_type';
$cvarray['Specific rock type']='specific_rock_type';
$cvarray['Igneous Rock Class']='igneous_rock_class';
$cvarray['Description / Lithology']='description_lithology';
$cvarray['Absolute Age of Geologic Unit (Ma)']='absolute_age_of_geologic_unit_';
$cvarray['Eon']='eon';
$cvarray['Era']='era';
$cvarray['Period']='period';
$cvarray['Epoch']='epoch';
$cvarray['Age Modifier']='age_modifier';
$cvarray['RockDescriptionEndSelectGroup']='endselectgroup';

$cvvals->rock_description = $cvarray;


unset($cvarray);
$cvarray=array();

$cvarray['Sample']='selectgroup';
$cvarray['Sample ID']='sample_id';
$cvarray['Sample Orientation Strike']='sample_orientation_strike';
$cvarray['Sample Orientation Dip']='sample_orientation_dip';
$cvarray['Material Type']='material_type';
$cvarray['Material Details']='material_details';
$cvarray['Sample Size (cm)']='sample_size_cm';
$cvarray['Main Sampling Purpose']='main_sampling_purpose';
$cvarray['Sample Description']='sample_description';
$cvarray['Other Comments About Sampling']='other_comments_about_sampling';
$cvarray['Inferred Age of Sample (Ma)']='inferred_age_of_sample_ma';
$cvarray['SampleEndSelectGroup']='endselectgroup';


$cvvals->sample_locality = $cvarray;


unset($cvarray);
$cvarray=array();


$cvarray['Contacts']='selectgroup';
$cvarray['Contact Type']='contact_type';
$cvarray['Contact Quality']='contact_quality';
$cvarray['Contact Character']='contact_character';
$cvarray['Specific type']='specific_type';
$cvarray['Marker Layer Details']='marker_layer_details';
$cvarray['Sense of Shear']='sense_of_shear';
$cvarray['Movement Justification']='movement_justification';
$cvarray['Offset Markers']='offset_markers';
$cvarray['Offset (m)']='offset_m';
$cvarray['Directional Indicators']='directional_indicators';
$cvarray['Thickness of Fault or Shear Zone (m)']='thickness_of_fault_or_shear_zo';
$cvarray['Minimum Age of Deformation (Ma)']='minimum_age_of_deformation_ma';
$cvarray['Maximum Age of Deformation (Ma)']='maximum_age_of_deformation_ma';
$cvarray['Juxtaposes __________ rocks in the hanging wall....']='juxtaposes_rocks';
$cvarray['... against ________ rocks in the footwall.']='against_rocks';
$cvarray['Dominant Fold Geometry']='fold_geometry';
$cvarray['Dominant Fold Shape']='fold_shape';
$cvarray['Dominant Fold Attitude']='fold_attitude';
$cvarray['Tightness / Interlimb Angle']='tightness';
$cvarray['Vergence']='vergence';
$cvarray['ContactsEndSelectGroup']='endselectgroup';



$cvvals->contacts_and_traces = $cvarray;















//****************************************************************************************




$cvvals = new stdClass();

unset($cvarray);
$cvarray=array();
$cvarray['Strike']='strike';
$cvarray['Dip']='dip';
$cvarray['Planar Surface Quality']='planar_surface_quality';
$cvarray['Planar Measurement Quality']='planar_measurement_quality';
$cvarray['Strike type']='strike_type';
$cvarray['Specific Strike Type']='specific_strike_type';
$cvarray['Marker Layer Details']='marker_layer_details';
$cvarray['Tectonite Label']='tectonite_label';
$cvarray['Gneissic Band Spacing (cm)']='gneissic_band_spacing_cm';
$cvarray['Average Grain Size (mm) in Gneissic Bands']='average_grain_size_mm_in_gne';
$cvarray['Vein mineral']='vein_mineral';
$cvarray['Sense of shear']='sense_of_shear';
$cvarray['Movement Justification']='movement_justification';
$cvarray['Offset markers']='offset_marker';
$cvarray['Offset (m)']='offset_m';
$cvarray['Directional indicators']='directional_indicators';
$cvarray['Hanging wall unit label']='hanging_wall_unit_label';
$cvarray['Footwall unit label']='footwall_unit_label';
$cvarray['Thickness of Fault or Shear Zone (m)']='thickness_of_fault_or_shear_zo';
$cvarray['Minimum Age of Deformation (Ma)']='minimum_age_of_deformation_ma';
$cvarray['Maximum Age of Deformation (Ma)']='maximum_age_of_deformation_ma';
$cvarray['Specific Foliation Element being Measured']='specific_foliation_element';
$cvarray['Plane Facing']='plane_facing';
$cvarray['Facing Direction']='facing_direction';
$cvarray['Trend']='trend';
$cvarray['Plunge']='plunge';
$cvarray['Rake']='rake';
$cvarray['Rake Calculated?']='rake_calculated';
$cvarray['Linear Orientation Quality']='linear_orientation_quality';
$cvarray['Linear Measurement Quality']='linear_measurement_quality';
$cvarray['Trend type']='trend_type';
$cvarray['Specific Trend type']='specific_trend_type';
$cvarray['Approximate Amplitude Scale of Related Folding']='approx_amplitude';
$cvarray['Dominant Fold Geometry']='fold_geometry';
$cvarray['Dominant Fold Shape']='fold_shape';
$cvarray['Dominant Fold Attitude']='fold_attitude';
$cvarray['Tightness / Interlimb Angle']='tightness';
$cvarray['Vergence']='vergence';
$cvarray['Vector Magnitude (meters)']='vector_magnitude_meters';
$cvarray['Structure Notes']='structure_notes';

$cvvals->measurements_and_observations = $cvarray;


unset($cvarray);
$cvarray=array();
$cvarray['Unit Label (abbreviation)']='unit_label_abbreviation';
$cvarray['Unit Label Age Symbol']='unit_label_age_symbol';
$cvarray['Formation Name']='formation_name';
$cvarray['Member Name']='member_name';
$cvarray['Submember Name']='submember_name';
$cvarray['Rock Type']='rock_type';
$cvarray['Specific rock type']='specific_rock_type';
$cvarray['Igneous Rock Class']='igneous_rock_class';
$cvarray['Description / Lithology']='description_lithology';
$cvarray['Absolute Age of Geologic Unit (Ma)']='absolute_age_of_geologic_unit_';
$cvarray['Eon']='eon';
$cvarray['Era']='era';
$cvarray['Period']='period';
$cvarray['Epoch']='epoch';
$cvarray['Age Modifier']='age_modifier';

$cvvals->rock_description = $cvarray;


unset($cvarray);
$cvarray=array();
$cvarray['Sample ID']='sample_id';
$cvarray['Sample Orientation Strike']='sample_orientation_strike';
$cvarray['Sample Orientation Dip']='sample_orientation_dip';
$cvarray['Material Type']='material_type';
$cvarray['Material Details']='material_details';
$cvarray['Sample Size (cm)']='sample_size_cm';
$cvarray['Main Sampling Purpose']='main_sampling_purpose';
$cvarray['Sample Description']='sample_description';
$cvarray['Other Comments About Sampling']='other_comments_about_sampling';
$cvarray['Inferred Age of Sample (Ma)']='inferred_age_of_sample_ma';


$cvvals->sample_locality = $cvarray;


unset($cvarray);
$cvarray=array();
$cvarray['Contact Type']='contact_type';
$cvarray['Specific type']='specific_type';
$cvarray['Marker Layer Details']='marker_layer_details';
$cvarray['Sense of Shear']='sense_of_shear';
$cvarray['Movement Justification']='movement_justification';
$cvarray['Offset Markers']='offset_markers';
$cvarray['Offset (m)']='offset_m';
$cvarray['Directional Indicators']='directional_indicators';
$cvarray['Thickness of Fault or Shear Zone (m)']='thickness_of_fault_or_shear_zo';
$cvarray['Minimum Age of Deformation (Ma)']='minimum_age_of_deformation_ma';
$cvarray['Maximum Age of Deformation (Ma)']='maximum_age_of_deformation_ma';
$cvarray['Juxtaposes __________ rocks in the hanging wall....']='juxtaposes_rocks';
$cvarray['... against ________ rocks in the footwall.']='against_rocks';
$cvarray['Dominant Fold Geometry']='fold_geometry';
$cvarray['Dominant Fold Shape']='fold_shape';
$cvarray['Dominant Fold Attitude']='fold_attitude';
$cvarray['Tightness / Interlimb Angle']='tightness';
$cvarray['Vergence']='vergence';
$cvarray['Contact Quality']='contact_quality';
$cvarray['Contact Character']='contact_character';


$cvvals->contacts_and_traces = $cvarray;











$cvjson='{"contacts_and_traces":{"typelabel":"Contacts and Traces (polylines)","columns":[{"name":"contact_type","label":"Contact Type","required":"true","cv":["depositional","intrusive","metamorphic","fault","shear_zone","option_11","marker_layer","edge_of_mappin","temporary","unknown","other"]},{"name":"Other_Contact_Type","label":"Other Contact Type","required":"true"},{"name":"depositional_contact_type","label":"Depositional Contact Type","required":"false","cv":["stratigraphic","alluvial","unconformity","volcanic","unknown","other"]},{"name":"Other_Depositional_Type","label":"Other Depositional Type","required":"true"},{"name":"unconformity_type","label":"Unconformity Type","required":"false","cv":["angular_unconf","nonconformity","disconformity","unknown"]},{"name":"intruding_feature","label":"Intruding Feature","required":"false","cv":["dike","sill","pluton","migmatite","injectite","unknown"]},{"name":"metamorphic_contact_type","label":"Metamorphic Contact Type","required":"false","cv":["between_two_di","isograd","other"]},{"name":"metamorphic_contact_other_det","label":"Other Metamorphic Contact","required":"true"},{"name":"marker_layer_details","label":"Marker Layer Details","required":"false"},{"name":"fault_geometry","label":"Type of Fault or Shear Zone","required":"false","cv":["not_specified","strike_slip","dip_slip","oblique"]},{"name":"strike_slip_movement","label":"Strike-Slip Movement","required":"false","cv":["not_specified","dextral","sinistral"]},{"name":"dip_slip_movement","label":"Dip-Slip Movement","required":"false","cv":["not_specified","normal","reverse","thrust","low_angle_norm"]},{"name":"oblique_movement","label":"Oblique Movement","required":"false","cv":["not_specified","dextral_reverse","dextral_normal","sinistral_reverse","sinistral_normal","dextral","sinistral","reverse","normal"]},{"name":"movement_justification","label":"Movement Justification","required":"false","cv":["not_specified","offset","directional_indicator"]},{"name":"Fault_Offset_Markers","label":"Fault Offset Markers","required":"true","cv":["not_specified","bedding","intrusion","metamorphic_fo","compositional_","geomorphic_fea","other"]},{"name":"offset_markers_001","label":"Shear Zone Offset Markers","required":"true","cv":["not_specified","bedding","intrusion","metamorphic_foliation","compositional_banding","other_marker"]},{"name":"marker_detail","label":"Other Offset Marker and Detail","required":"false"},{"name":"Offset_m","label":"Offset (m)","required":"false"},{"name":"directional_indicators","label":"Fault Slip Directional Indicators","required":"true","cv":["riedel_shears","gouge_fill","crescentic_fractures","slickenfibers","tension_gashes","oblique_foliation","drag_folds","asymmetric_folds","rotated_clasts","domino_clasts","other"]},{"name":"Shear_Zone_Directional_indicat","label":"Shear Zone Directional indicators","required":"true","cv":["oblique_foliat","drag","asymmetric_fol","domino_texture","rotated_clasts","rotated_porphy","delta_clasts","s_c_fabric","s_c__fabric","c_c__fabric","mica_fish","boudinage","other"]},{"name":"Other_Directional_Indicator","label":"Other Directional Indicator","required":"true"},{"name":"Thickness_of_Fault_or_Shear_Zo","label":"Thickness of Fault or Shear Zone (m)","required":"false"},{"name":"Minimum_Age_of_Deformation_Ma","label":"Minimum Age of Deformation (Ma)","required":"false"},{"name":"Maximum_Age_of_Deformation_Ma","label":"Maximum Age of Deformation (Ma)","required":"false"},{"name":"juxtaposes_rocks","label":"Juxtaposes __________ rocks....","required":"false","cv":["this_is_a_list","more_in_the_li","not_specified"]},{"name":"against_rocks","label":"... against ________ rocks.","required":"false","cv":["this_is_a_list","more_in_the_li","not_specified"]},{"name":"fold_geometry","label":"Dominant Fold Geometry","required":"false","cv":["syncline","anticline","monocline","synform","antiform","s_fold","z_fold","m_fold","sheath","unknown"]},{"name":"fold_shape","label":"Dominant Fold Shape","required":"false","cv":["chevron","cuspate","circular","elliptical","unknown"]},{"name":"fold_attitude","label":"Dominant Fold Attitude","required":"false","cv":["upright","overturned","vertical","horizontal","recumbent","inclined","unknown"]},{"name":"tightness","label":"Tightness \/ Interlimb Angle","required":"false","cv":["gentle","open","close","tight","isoclinal"]},{"name":"vergence","label":"Vergence","required":"false","cv":["option_9","north","ne","east","se","south","sw","west","nw"]},{"name":"Contact_Quality","label":"Contact Quality","required":"true","cv":["known","approximate","inferred","questionable_a","questionable_i","concealed"]},{"name":"Contact_Character","label":"Contact Character","required":"false","cv":["sharp","gradational","chilled","brecciated","unknown"]}]},"general":{"typelabel":"General (Common Fields)","columns":[{"name":"spot_name","label":"Spot Name","required":"true"},{"name":"id","label":"ID","required":"true"},{"name":"date","label":"Date","required":"true"},{"name":"time","label":"Time","required":"true"},{"name":"location","label":"Location","required":"true"},{"name":"photos","label":"Photos","required":"false"},{"name":"notes","label":"Notes","required":"false"}]},"measurements_and_observations":{"typelabel":"Measurements and Observations (point)","columns":[{"name":"measured_plane","label":"MEASURED PLANE?","required":"false"},{"name":"strike","label":"Strike","required":"true"},{"name":"dip","label":"Dip","required":"true"},{"name":"planar_surface_quality","label":"Planar Surface Quality","required":"false","cv":["accurate","approximate","irregular"]},{"name":"planar_measurement_quality","label":"Planar Measurement Quality","required":"false","cv":["1","2","3","4","5"]},{"name":"planar_feature_type","label":"Planar Feature Type","required":"true","cv":["bedding","contact","foliation","axial_planar_s","fracture","joint","fault_plane","shear_fracture","shear_zone","other"]},{"name":"contact_type","label":"Contact Type","required":"false","cv":["depositional","intrusive","metamorphic","marker_layer","edge_of_mappin","unknown","other"]},{"name":"other_contact_type","label":"Other Contact Type","required":"false"},{"name":"depositional_contact_type","label":"Depositional Contact Type","required":"false","cv":["stratigraphic","alluvial","unconformity","volcanic","unknown","other"]},{"name":"other_depositional_type","label":"Other Depositional Type","required":"false"},{"name":"unconformity_type","label":"Unconformity Type","required":"false","cv":["angular_unconf","nonconformity","disconformity","unknown"]},{"name":"intruding_feature","label":"Intruding Feature","required":"false","cv":["dike","sill","pluton","migmatite","injectite","unknown"]},{"name":"metamorphic_contact_type","label":"Metamorphic Contact Type","required":"false","cv":["btwn_diff_meta","isograd","other"]},{"name":"ohter_metamorphic_contact","label":"Other Metamorphic Contact","required":"false"},{"name":"marker_layer_details","label":"Marker Layer Details","required":"false"},{"name":"foliation_type","label":"Foliation Type","required":"false","cv":["not_specified","solid_state","magmatic","migmatitic","cleavage","lava_flow","compaction","other"]},{"name":"other_foliation_type","label":"Other Foliation Type","required":"false"},{"name":"solid_state_foliation_type","label":"Solid-state Foliation Type","required":"false","cv":["not_specified","cataclastic","mylonitic","schistosity","lenticular","gneissic_banding","strain_marker","other"]},{"name":"other_solid_state_foliation_an","label":"Other Solid-State Foliation and Description","required":"false"},{"name":"tectonite_label","label":"Tectonite Label","required":"false","cv":["not_specified","s_tectonite","s_l_tectonite","l_s_tectonite","l_tectonite"]},{"name":"gneissic_band_spacing_cm","label":"Gneissic Band Spacing (cm)","required":"false"},{"name":"average_grain_size_mm_in_gne","label":"Average Grain Size (mm) in Gneissic Bands","required":"false"},{"name":"cleavage_type","label":"Cleavage Type","required":"false","cv":["not_specified","slatey","phyllitic","crenulation","phacoidal","other_new"]},{"name":"other_cleavage","label":"Other Cleavage","required":"false"},{"name":"axial_planar_cleavage","label":"Axial Planar Cleavage?","required":"false"},{"name":"vein_mineral_fill","label":"Vein Mineral Fill","required":"true","cv":["quartz","calcite","other"]},{"name":"other_vein_mineral","label":"Other Vein Mineral","required":"true"},{"name":"shear_fracture_type","label":"Shear Fracture Type","required":"false","cv":["r","r_1","p"]},{"name":"type_of_fault_or_shear_zone","label":"Type of Fault or Shear Zone","required":"true","cv":["not_specified","strike_slip","dip_slip","oblique"]},{"name":"strike_slip_movement","label":"Strike-Slip Movement","required":"true","cv":["not_specified","dextral","sinistral"]},{"name":"dip_slip_movement","label":"Dip-Slip Movement","required":"true","cv":["not_specified","reverse","normal"]},{"name":"oblique_movement","label":"Oblique Movement","required":"true","cv":["not_specified","dextral_reverse","dextral_normal","sinistral_reverse","sinistral_normal","dextral","sinistral","reverse","normal"]},{"name":"movement_justification","label":"Movement Justification","required":"true","cv":["not_specified","offset","directional_indicator"]},{"name":"fault_offset_markers_0","label":"Fault Offset Markers","required":"true","cv":["not_specified","bedding","intrusion","metamorphic_fo","compositional_","geomorphic_fea"]},{"name":"fault_offset_markers_1","label":"Fault Offset Markers","required":"true","cv":["not_specified","bedding","intrusion","metamorphic_fo","compositional_","geomorphic_fea"]},{"name":"shear_zone_offset_markers","label":"Shear Zone Offset Markers","required":"false","cv":["not_specified","bedding","intrusion","metamorphic_foliation","compositional_banding","other_marker"]},{"name":"offset_marker_detail","label":"Offset Marker Detail","required":"true"},{"name":"offset_m","label":"Offset (m)","required":"false"},{"name":"fault_directional_indicators_0","label":"Fault Directional Indicators","required":"true","cv":["riedel_shear","gouge_fill","crescentic_fra","slickenfibers","oblique_gouge_","drag_folds","rotated_clasts","domino_texture","tension_gashes","asymmetric_fol","other"]},{"name":"fault_directional_indicators_1","label":"Fault Directional Indicators","required":"true","cv":["riedel_shear","gouge_fill","crescentic_fra","slickenfibers","oblique_gouge_","drag_folds","rotated_clasts","domino_texture","tension_gashes","asymmetric_fol","other"]},{"name":"two_shear_zone_directional_ind","label":"Two Shear Zone Directional Indicators","required":"true","cv":["oblique_foliation","drag_folds","asymmetric_folds","domino_clasts","rotated_clasts","rotated_porphyroblasts","delta_porphyroclasts","s_c_fabric","s_c__fabric","c_c__fabric","mica_fish","boudinage","other"]},{"name":"other_directional_indicator","label":"Other Directional Indicator","required":"false"},{"name":"juxtaposes_rocks","label":"Juxtaposes __________ rocks....","required":"false","cv":["not_specified","this_is_a_list","more_in_the_li"]},{"name":"against_rocks","label":"... against ________ rocks.","required":"false","cv":["not_specified","this_is_a_list","more_in_the_li"]},{"name":"thickness_of_fault_or_shear_zo","label":"Thickness of Fault or Shear Zone (m)","required":"false"},{"name":"minimum_age_of_deformation_ma","label":"Minimum Age of Deformation (Ma)","required":"false"},{"name":"maximum_age_of_deformation_ma","label":"Maximum Age of Deformation (Ma)","required":"false"},{"name":"specific_foliation_element","label":"Specific Foliation Element being Measured","required":"false","cv":["not_specified","s","c","c_1"]},{"name":"other_planar_feature","label":"Other Planar Feature","required":"true"},{"name":"plane_facing","label":"Plane Facing","required":"false","cv":["upright","overturned","vertical","unknown"]},{"name":"facing_direction","label":"Facing Direction","required":"false"},{"name":"measured_line","label":"MEASURED LINE?","required":"false"},{"name":"trend","label":"Trend","required":"true"},{"name":"plunge","label":"Plunge","required":"true"},{"name":"Rake","label":"Rake","required":"false"},{"name":"rake_calculated","label":"Rake Calculated?","required":"true"},{"name":"linear_orientation_quality","label":"Linear Orientation Quality","required":"true","cv":["accurate","approximate","irregular"]},{"name":"linear_measurement_quality","label":"Linear Measurement Quality","required":"true","cv":["1","2","3","4","5"]},{"name":"linear_feature_type","label":"Linear Feature Type","required":"true","cv":["fault","solid_state","fold_hinge","intersection","flow","vector","other"]},{"name":"other_linear_feature","label":"Other Linear Feature","required":"true"},{"name":"fault_lineations","label":"Fault Lineations","required":"false","cv":["striations","mullions","slickenfibers","mineral_streak","assymetric_fol","other"]},{"name":"other_fault_lineation","label":"Other Fault Lineation","required":"true"},{"name":"solid_state_lineations","label":"Solid-State Lineation","required":"true","cv":["stretching","rodding","boudin","other"]},{"name":"other_solid_state_lineation","label":"Other Solid-State Lineation","required":"true"},{"name":"pencil_cleavage","label":"Pencil Cleavage?","required":"true"},{"name":"intersection_lineation_descrip","label":"Intersection Lineation Description","required":"false"},{"name":"crenulation","label":"Crenulation?","required":"false"},{"name":"approx_amplitude","label":"Approximate Amplitude Scale of Related Folding","required":"false","cv":["centimeter_sca","meter_scale","kilometer_scal"]},{"name":"dominant_fold_geometry","label":"Dominant Fold Geometry","required":"false","cv":["syncline","anticline","monocline","synform","antiform","s_fold","z_fold","m_fold","sheath","unknown"]},{"name":"dominant_fold_shape","label":"Dominant Fold Shape","required":"false","cv":["chevron","cuspate","circular","elliptical","unknown"]},{"name":"dominant_fold_attitude","label":"Dominant Fold Attitude","required":"false","cv":["upright","overturned","vertical","horizontal","recumbent","inclined","unknown"]},{"name":"tightness_interlimb_angle","label":"Tightness \/ Interlimb Angle","required":"false","cv":["gentle","open","close","tight","isoclinal"]},{"name":"vergence","label":"Vergence","required":"false","cv":["north","ne","east","se","south","sw","west","nw"]},{"name":"vector_magnitude_meters","label":"Vector Magnitude (meters)","required":"false"},{"name":"structure_notes","label":"Structure Notes","required":"false"}]},"other_notes":{"typelabel":"Other Notes (taken at a point on a map)","columns":[{"name":"picture","label":"Picture?","required":"false"},{"name":"notes","label":"Notes","required":"false"},{"name":"files","label":"Files","required":"false"},{"name":"hotlink","label":"Hotlink","required":"false"},{"name":"label","label":"Label?","required":"true"},{"name":"tags","label":"Tags","required":"false"}]},"rock_description":{"typelabel":" Rock Description (point and polygon)","columns":[{"name":"unit_label_abbreviation","label":"Unit Label (abbreviation)","required":"false"},{"name":"map_unit_name","label":"Map Unit Name","required":"false"},{"name":"rock_type","label":"Rock Type","required":"false","cv":["igneous","metamorphic","sedimentary","sediment"]},{"name":"sediment_type","label":"Sediment Type","required":"false","cv":["alluvium","older_alluvium","colluvium","lake_deposit","eolian","talus","breccia","gravel","sand","silt","clay","moraine","till","loess","other"]},{"name":"other_sediment_type","label":"Other Sediment Type","required":"true"},{"name":"sedimentary_rock_type","label":"Sedimentary Rock Type","required":"false","cv":["limestone","dolostone","travertine","evaporite","chert","conglomerate","breccia","sandstone","siltstone","mudstone","shale","claystone","coal","other"]},{"name":"other_sedimentary_rock_type","label":"Other Sedimentary Rock Type","required":"true"},{"name":"igneous_rock_class","label":"Igneous Rock Class","required":"false","cv":["volcanic","plutonic","hypabyssal"]},{"name":"volcanic_rock_type","label":"Volcanic Rock Type","required":"false","cv":["basalt","basaltic_andes","andesite","latite","dacite","rhyolite","tuff","ash_fall_tuff","ash_flow_tuff","vitrophyre","trachyte","trachyandesite","tuff_breccia","lapilli_tuff","other"]},{"name":"other_volcanic_rock_type","label":"Other Volcanic Rock Type","required":"true"},{"name":"plutonic_rock_types","label":"Plutonic Rock Types","required":"false","cv":["granite","alkali_feldspa","quartz_monzoni","syenite","granodiorite","monzonite","tonalite","diorite","gabbro","anorthosite","other"]},{"name":"other_plutonic_rock_type","label":"Other Plutonic Rock Type","required":"true"},{"name":"metamorphic_rock_types","label":"Metamorphic Rock Types","required":"false","cv":["low_grade","medium_grade","high_grade","slate","phyllite","schist","gneiss","marble","quartzite","amphibolite","serpentinite","hornfels","metavolcanic","calc_silicate","mylonite","other"]},{"name":"other_metamorphic_rock_type","label":"Other Metamorphic Rock Type","required":"true"},{"name":"description_lithology","label":"Description \/ Lithology","required":"false"},{"name":"absolute_age_of_geologic_unit_","label":"Absolute Age of Geologic Unit (Ma)","required":"false"},{"name":"eon","label":"Eon","required":"false","cv":["phanerozoic","proterozoic","archean","hadean"]},{"name":"phanerozoic_era","label":"Phanerozoic Era","required":"false","cv":["cenozoic","mesozoic","paleozoic"]},{"name":"proterozoic_era","label":"Proterozoic Era","required":"false","cv":["neoproterozoic","mesoproterozoi","paleoproterozo"]},{"name":"archean_era","label":"Archean Era","required":"false","cv":["neoarchean","mesoarchean","paleoarchean","eoarchean"]},{"name":"cenozoic_period","label":"Cenozoic Period","required":"false","cv":["quaternary","neogene","paleogene"]},{"name":"mesozoic_period","label":"Mesozoic Period","required":"false","cv":["cretaceous","jurassic","triassic"]},{"name":"paleozoic_period","label":"Paleozoic Period","required":"false","cv":["permian","carboniferous","pennsylvanian","mississippian","devonian","silurian","ordovician","cambrian"]},{"name":"proterozoic_and_archean_period","label":"Proterozoic and Archean Period","required":"false","cv":["ediacaran","crygenian","tonian","stenian","ectasian","calymmian","statherian","orosirian","rhyacian","siderian"]},{"name":"quaternary_epoch","label":"Quaternary Epoch","required":"false","cv":["holocene","pleistocene"]},{"name":"neogene_epoch","label":"Neogene Epoch","required":"false","cv":["pliocene","miocene"]},{"name":"paleogene_epoch","label":"Paleogene Epoch","required":"false","cv":["oligocene","eocene","paleocene"]},{"name":"age_modifier","label":"Age Modifier","required":"false","cv":["late","middle","early"]}]},"sample_locality":{"typelabel":"Sample Locality (point)","columns":[{"name":"sample_id_name","label":"Sample specific ID \/ Name","required":"true"},{"name":"oriented_sample","label":"Oriented Sample","required":"false","cv":["yes","no"]},{"name":"sample_orientation_strike","label":"Sample Orientation Strike","required":"true"},{"name":"sample_orientation_dip","label":"Sample Orientation Dip","required":"true"},{"name":"material_type","label":"Material Type","required":"true","cv":["intact_rock","fragmented_roc","sediment","other"]},{"name":"Other_Material_Type","label":"Other Material Type","required":"true"},{"name":"material_details","label":"Material Details","required":"false"},{"name":"sample_size_cm","label":"Sample Size (cm)","required":"false"},{"name":"main_sampling_purpose","label":"Main Sampling Purpose","required":"true","cv":["fabric___micro","petrology","geochronology","geochemistry","other"]},{"name":"sample_description","label":"Sample Description","required":"false"},{"name":"other_comments_about_sampling","label":"Other Comments About Sampling","required":"false"},{"name":"inferred_age_of_sample_ma","label":"Inferred Age of Sample (Ma)","required":"false"}]}}';

$cvvals=json_decode($cvjson);


//print_r($cvvals);















$cvjson='{"contacts_and_traces":{"typelabel":"Contacts and Traces (polylines)","columns":[{"name":"contact_type","label":"Contact Type","required":"true","cv":["depositional","intrusive","metamorphic","fault","shear_zone","option_11","marker_layer","edge_of_mappin","temporary","unknown","other"]},{"name":"Other_Contact_Type","label":"Other Contact Type","required":"true"},{"name":"depositional_contact_type","label":"Depositional Contact Type","required":"false","cv":["stratigraphic","alluvial","unconformity","volcanic","unknown","other"]},{"name":"Other_Depositional_Type","label":"Other Depositional Type","required":"true"},{"name":"unconformity_type","label":"Unconformity Type","required":"false","cv":["angular_unconf","nonconformity","disconformity","unknown"]},{"name":"intruding_feature","label":"Intruding Feature","required":"false","cv":["dike","sill","pluton","migmatite","injectite","unknown"]},{"name":"metamorphic_contact_type","label":"Metamorphic Contact Type","required":"false","cv":["between_two_di","isograd","other"]},{"name":"metamorphic_contact_other_det","label":"Other Metamorphic Contact","required":"true"},{"name":"marker_layer_details","label":"Marker Layer Details","required":"false"},{"name":"fault_geometry","label":"Type of Fault or Shear Zone","required":"false","cv":["not_specified","strike_slip","dip_slip","oblique"]},{"name":"strike_slip_movement","label":"Strike-Slip Movement","required":"false","cv":["not_specified","dextral","sinistral"]},{"name":"dip_slip_movement","label":"Dip-Slip Movement","required":"false","cv":["not_specified","normal","reverse","thrust","low_angle_norm"]},{"name":"oblique_movement","label":"Oblique Movement","required":"false","cv":["not_specified","dextral_reverse","dextral_normal","sinistral_reverse","sinistral_normal","dextral","sinistral","reverse","normal"]},{"name":"movement_justification","label":"Movement Justification","required":"false","cv":["not_specified","offset","directional_indicator"]},{"name":"Fault_Offset_Markers","label":"Fault Offset Markers","required":"true","cv":["not_specified","bedding","intrusion","metamorphic_fo","compositional_","geomorphic_fea","other"]},{"name":"offset_markers_001","label":"Shear Zone Offset Markers","required":"true","cv":["not_specified","bedding","intrusion","metamorphic_foliation","compositional_banding","other_marker"]},{"name":"marker_detail","label":"Other Offset Marker and Detail","required":"false"},{"name":"Offset_m","label":"Offset (m)","required":"false"},{"name":"directional_indicators","label":"Fault Slip Directional Indicators","required":"true","cv":["riedel_shears","gouge_fill","crescentic_fractures","slickenfibers","tension_gashes","oblique_foliation","drag_folds","asymmetric_folds","rotated_clasts","domino_clasts","other"]},{"name":"Shear_Zone_Directional_indicat","label":"Shear Zone Directional indicators","required":"true","cv":["oblique_foliat","drag","asymmetric_fol","domino_texture","rotated_clasts","rotated_porphy","delta_clasts","s_c_fabric","s_c__fabric","c_c__fabric","mica_fish","boudinage","other"]},{"name":"Other_Directional_Indicator","label":"Other Directional Indicator","required":"true"},{"name":"Thickness_of_Fault_or_Shear_Zo","label":"Thickness of Fault or Shear Zone (m)","required":"false"},{"name":"Minimum_Age_of_Deformation_Ma","label":"Minimum Age of Deformation (Ma)","required":"false"},{"name":"Maximum_Age_of_Deformation_Ma","label":"Maximum Age of Deformation (Ma)","required":"false"},{"name":"juxtaposes_rocks","label":"Juxtaposes __________ rocks....","required":"false","cv":["this_is_a_list","more_in_the_li","not_specified"]},{"name":"against_rocks","label":"... against ________ rocks.","required":"false","cv":["this_is_a_list","more_in_the_li","not_specified"]},{"name":"fold_geometry","label":"Dominant Fold Geometry","required":"false","cv":["syncline","anticline","monocline","synform","antiform","s_fold","z_fold","m_fold","sheath","unknown"]},{"name":"fold_shape","label":"Dominant Fold Shape","required":"false","cv":["chevron","cuspate","circular","elliptical","unknown"]},{"name":"fold_attitude","label":"Dominant Fold Attitude","required":"false","cv":["upright","overturned","vertical","horizontal","recumbent","inclined","unknown"]},{"name":"tightness","label":"Tightness \/ Interlimb Angle","required":"false","cv":["gentle","open","close","tight","isoclinal"]},{"name":"vergence","label":"Vergence","required":"false","cv":["option_9","north","ne","east","se","south","sw","west","nw"]},{"name":"Contact_Quality","label":"Contact Quality","required":"true","cv":["known","approximate","inferred","questionable_a","questionable_i","concealed"]},{"name":"Contact_Character","label":"Contact Character","required":"false","cv":["sharp","gradational","chilled","brecciated","unknown"]}]},"general":{"typelabel":"General (Common Fields)","columns":[{"name":"spot_name","label":"Spot Name","required":"true"},{"name":"id","label":"ID","required":"true"},{"name":"date","label":"Date","required":"true"},{"name":"time","label":"Time","required":"true"},{"name":"location","label":"Location","required":"true"},{"name":"photos","label":"Photos","required":"false"},{"name":"notes","label":"Notes","required":"false"}]},"measurements_and_observations":{"typelabel":"Measurements and Observations (point)","columns":[{"name":"measured_plane","label":"MEASURED PLANE?","required":"false"},{"name":"strike","label":"Strike","required":"true"},{"name":"dip","label":"Dip","required":"true"},{"name":"planar_surface_quality","label":"Planar Surface Quality","required":"false","cv":["accurate","approximate","irregular"]},{"name":"planar_measurement_quality","label":"Planar Measurement Quality","required":"false","cv":["1","2","3","4","5"]},{"name":"planar_feature_type","label":"Planar Feature Type","required":"true","cv":["bedding","contact","foliation","axial_planar_s","fracture","joint","fault_plane","shear_fracture","shear_zone","other"]},{"name":"contact_type","label":"Contact Type","required":"false","cv":["depositional","intrusive","metamorphic","marker_layer","edge_of_mappin","unknown","other"]},{"name":"other_contact_type","label":"Other Contact Type","required":"false"},{"name":"depositional_contact_type","label":"Depositional Contact Type","required":"false","cv":["stratigraphic","alluvial","unconformity","volcanic","unknown","other"]},{"name":"other_depositional_type","label":"Other Depositional Type","required":"false"},{"name":"unconformity_type","label":"Unconformity Type","required":"false","cv":["angular_unconf","nonconformity","disconformity","unknown"]},{"name":"intruding_feature","label":"Intruding Feature","required":"false","cv":["dike","sill","pluton","migmatite","injectite","unknown"]},{"name":"metamorphic_contact_type","label":"Metamorphic Contact Type","required":"false","cv":["btwn_diff_meta","isograd","other"]},{"name":"ohter_metamorphic_contact","label":"Other Metamorphic Contact","required":"false"},{"name":"marker_layer_details","label":"Marker Layer Details","required":"false"},{"name":"foliation_type","label":"Foliation Type","required":"false","cv":["not_specified","solid_state","magmatic","migmatitic","cleavage","lava_flow","compaction","other"]},{"name":"other_foliation_type","label":"Other Foliation Type","required":"false"},{"name":"solid_state_foliation_type","label":"Solid-state Foliation Type","required":"false","cv":["not_specified","cataclastic","mylonitic","schistosity","lenticular","gneissic_banding","strain_marker","other"]},{"name":"other_solid_state_foliation_an","label":"Other Solid-State Foliation and Description","required":"false"},{"name":"tectonite_label","label":"Tectonite Label","required":"false","cv":["not_specified","s_tectonite","s_l_tectonite","l_s_tectonite","l_tectonite"]},{"name":"gneissic_band_spacing_cm","label":"Gneissic Band Spacing (cm)","required":"false"},{"name":"average_grain_size_mm_in_gne","label":"Average Grain Size (mm) in Gneissic Bands","required":"false"},{"name":"cleavage_type","label":"Cleavage Type","required":"false","cv":["not_specified","slatey","phyllitic","crenulation","phacoidal","other_new"]},{"name":"other_cleavage","label":"Other Cleavage","required":"false"},{"name":"axial_planar_cleavage","label":"Axial Planar Cleavage?","required":"false"},{"name":"vein_mineral_fill","label":"Vein Mineral Fill","required":"true","cv":["quartz","calcite","other"]},{"name":"other_vein_mineral","label":"Other Vein Mineral","required":"true"},{"name":"shear_fracture_type","label":"Shear Fracture Type","required":"false","cv":["r","r_1","p"]},{"name":"type_of_fault_or_shear_zone","label":"Type of Fault or Shear Zone","required":"true","cv":["not_specified","strike_slip","dip_slip","oblique"]},{"name":"strike_slip_movement","label":"Strike-Slip Movement","required":"true","cv":["not_specified","dextral","sinistral"]},{"name":"dip_slip_movement","label":"Dip-Slip Movement","required":"true","cv":["not_specified","reverse","normal"]},{"name":"oblique_movement","label":"Oblique Movement","required":"true","cv":["not_specified","dextral_reverse","dextral_normal","sinistral_reverse","sinistral_normal","dextral","sinistral","reverse","normal"]},{"name":"movement_justification","label":"Movement Justification","required":"true","cv":["not_specified","offset","directional_indicator"]},{"name":"fault_offset_markers_0","label":"Fault Offset Markers","required":"true","cv":["not_specified","bedding","intrusion","metamorphic_fo","compositional_","geomorphic_fea"]},{"name":"fault_offset_markers_1","label":"Fault Offset Markers","required":"true","cv":["not_specified","bedding","intrusion","metamorphic_fo","compositional_","geomorphic_fea"]},{"name":"shear_zone_offset_markers","label":"Shear Zone Offset Markers","required":"false","cv":["not_specified","bedding","intrusion","metamorphic_foliation","compositional_banding","other_marker"]},{"name":"offset_marker_detail","label":"Offset Marker Detail","required":"true"},{"name":"offset_m","label":"Offset (m)","required":"false"},{"name":"fault_directional_indicators_0","label":"Fault Directional Indicators","required":"true","cv":["riedel_shear","gouge_fill","crescentic_fra","slickenfibers","oblique_gouge_","drag_folds","rotated_clasts","domino_texture","tension_gashes","asymmetric_fol","other"]},{"name":"fault_directional_indicators_1","label":"Fault Directional Indicators","required":"true","cv":["riedel_shear","gouge_fill","crescentic_fra","slickenfibers","oblique_gouge_","drag_folds","rotated_clasts","domino_texture","tension_gashes","asymmetric_fol","other"]},{"name":"two_shear_zone_directional_ind","label":"Two Shear Zone Directional Indicators","required":"true","cv":["oblique_foliation","drag_folds","asymmetric_folds","domino_clasts","rotated_clasts","rotated_porphyroblasts","delta_porphyroclasts","s_c_fabric","s_c__fabric","c_c__fabric","mica_fish","boudinage","other"]},{"name":"other_directional_indicator","label":"Other Directional Indicator","required":"false"},{"name":"juxtaposes_rocks","label":"Juxtaposes __________ rocks....","required":"false","cv":["not_specified","this_is_a_list","more_in_the_li"]},{"name":"against_rocks","label":"... against ________ rocks.","required":"false","cv":["not_specified","this_is_a_list","more_in_the_li"]},{"name":"thickness_of_fault_or_shear_zo","label":"Thickness of Fault or Shear Zone (m)","required":"false"},{"name":"minimum_age_of_deformation_ma","label":"Minimum Age of Deformation (Ma)","required":"false"},{"name":"maximum_age_of_deformation_ma","label":"Maximum Age of Deformation (Ma)","required":"false"},{"name":"specific_foliation_element","label":"Specific Foliation Element being Measured","required":"false","cv":["not_specified","s","c","c_1"]},{"name":"other_planar_feature","label":"Other Planar Feature","required":"true"},{"name":"plane_facing","label":"Plane Facing","required":"false","cv":["upright","overturned","vertical","unknown"]},{"name":"facing_direction","label":"Facing Direction","required":"false"},{"name":"measured_line","label":"MEASURED LINE?","required":"false"},{"name":"trend","label":"Trend","required":"true"},{"name":"plunge","label":"Plunge","required":"true"},{"name":"Rake","label":"Rake","required":"false"},{"name":"rake_calculated","label":"Rake Calculated?","required":"true"},{"name":"linear_orientation_quality","label":"Linear Orientation Quality","required":"true","cv":["accurate","approximate","irregular"]},{"name":"linear_measurement_quality","label":"Linear Measurement Quality","required":"true","cv":["1","2","3","4","5"]},{"name":"linear_feature_type","label":"Linear Feature Type","required":"true","cv":["fault","solid_state","fold_hinge","intersection","flow","vector","other"]},{"name":"other_linear_feature","label":"Other Linear Feature","required":"true"},{"name":"fault_lineations","label":"Fault Lineations","required":"false","cv":["striations","mullions","slickenfibers","mineral_streak","assymetric_fol","other"]},{"name":"other_fault_lineation","label":"Other Fault Lineation","required":"true"},{"name":"solid_state_lineations","label":"Solid-State Lineation","required":"true","cv":["stretching","rodding","boudin","other"]},{"name":"other_solid_state_lineation","label":"Other Solid-State Lineation","required":"true"},{"name":"pencil_cleavage","label":"Pencil Cleavage?","required":"true"},{"name":"intersection_lineation_descrip","label":"Intersection Lineation Description","required":"false"},{"name":"crenulation","label":"Crenulation?","required":"false"},{"name":"approx_amplitude","label":"Approximate Amplitude Scale of Related Folding","required":"false","cv":["centimeter_sca","meter_scale","kilometer_scal"]},{"name":"dominant_fold_geometry","label":"Dominant Fold Geometry","required":"false","cv":["syncline","anticline","monocline","synform","antiform","s_fold","z_fold","m_fold","sheath","unknown"]},{"name":"dominant_fold_shape","label":"Dominant Fold Shape","required":"false","cv":["chevron","cuspate","circular","elliptical","unknown"]},{"name":"dominant_fold_attitude","label":"Dominant Fold Attitude","required":"false","cv":["upright","overturned","vertical","horizontal","recumbent","inclined","unknown"]},{"name":"tightness_interlimb_angle","label":"Tightness \/ Interlimb Angle","required":"false","cv":["gentle","open","close","tight","isoclinal"]},{"name":"vergence","label":"Vergence","required":"false","cv":["north","ne","east","se","south","sw","west","nw"]},{"name":"vector_magnitude_meters","label":"Vector Magnitude (meters)","required":"false"},{"name":"structure_notes","label":"Structure Notes","required":"false"}]},"other_notes":{"typelabel":"Other Notes (taken at a point on a map)","columns":[{"name":"picture","label":"Picture?","required":"false"},{"name":"notes","label":"Notes","required":"false"},{"name":"files","label":"Files","required":"false"},{"name":"hotlink","label":"Hotlink","required":"false"},{"name":"label","label":"Label?","required":"true"},{"name":"tags","label":"Tags","required":"false"}]},"rock_description":{"typelabel":" Rock Description (point and polygon)","columns":[{"name":"unit_label_abbreviation","label":"Unit Label (abbreviation)","required":"false"},{"name":"map_unit_name","label":"Map Unit Name","required":"false"},{"name":"rock_type","label":"Rock Type","required":"false","cv":["igneous","metamorphic","sedimentary","sediment"]},{"name":"sediment_type","label":"Sediment Type","required":"false","cv":["alluvium","older_alluvium","colluvium","lake_deposit","eolian","talus","breccia","gravel","sand","silt","clay","moraine","till","loess","other"]},{"name":"other_sediment_type","label":"Other Sediment Type","required":"true"},{"name":"sedimentary_rock_type","label":"Sedimentary Rock Type","required":"false","cv":["limestone","dolostone","travertine","evaporite","chert","conglomerate","breccia","sandstone","siltstone","mudstone","shale","claystone","coal","other"]},{"name":"other_sedimentary_rock_type","label":"Other Sedimentary Rock Type","required":"true"},{"name":"igneous_rock_class","label":"Igneous Rock Class","required":"false","cv":["volcanic","plutonic","hypabyssal"]},{"name":"volcanic_rock_type","label":"Volcanic Rock Type","required":"false","cv":["basalt","basaltic_andes","andesite","latite","dacite","rhyolite","tuff","ash_fall_tuff","ash_flow_tuff","vitrophyre","trachyte","trachyandesite","tuff_breccia","lapilli_tuff","other"]},{"name":"other_volcanic_rock_type","label":"Other Volcanic Rock Type","required":"true"},{"name":"plutonic_rock_types","label":"Plutonic Rock Types","required":"false","cv":["granite","alkali_feldspa","quartz_monzoni","syenite","granodiorite","monzonite","tonalite","diorite","gabbro","anorthosite","other"]},{"name":"other_plutonic_rock_type","label":"Other Plutonic Rock Type","required":"true"},{"name":"metamorphic_rock_types","label":"Metamorphic Rock Types","required":"false","cv":["low_grade","medium_grade","high_grade","slate","phyllite","schist","gneiss","marble","quartzite","amphibolite","serpentinite","hornfels","metavolcanic","calc_silicate","mylonite","other"]},{"name":"other_metamorphic_rock_type","label":"Other Metamorphic Rock Type","required":"true"},{"name":"description_lithology","label":"Description \/ Lithology","required":"false"},{"name":"absolute_age_of_geologic_unit_","label":"Absolute Age of Geologic Unit (Ma)","required":"false"},{"name":"eon","label":"Eon","required":"false","cv":["phanerozoic","proterozoic","archean","hadean"]},{"name":"phanerozoic_era","label":"Phanerozoic Era","required":"false","cv":["cenozoic","mesozoic","paleozoic"]},{"name":"proterozoic_era","label":"Proterozoic Era","required":"false","cv":["neoproterozoic","mesoproterozoi","paleoproterozo"]},{"name":"archean_era","label":"Archean Era","required":"false","cv":["neoarchean","mesoarchean","paleoarchean","eoarchean"]},{"name":"cenozoic_period","label":"Cenozoic Period","required":"false","cv":["quaternary","neogene","paleogene"]},{"name":"mesozoic_period","label":"Mesozoic Period","required":"false","cv":["cretaceous","jurassic","triassic"]},{"name":"paleozoic_period","label":"Paleozoic Period","required":"false","cv":["permian","carboniferous","pennsylvanian","mississippian","devonian","silurian","ordovician","cambrian"]},{"name":"proterozoic_and_archean_period","label":"Proterozoic and Archean Period","required":"false","cv":["ediacaran","crygenian","tonian","stenian","ectasian","calymmian","statherian","orosirian","rhyacian","siderian"]},{"name":"quaternary_epoch","label":"Quaternary Epoch","required":"false","cv":["holocene","pleistocene"]},{"name":"neogene_epoch","label":"Neogene Epoch","required":"false","cv":["pliocene","miocene"]},{"name":"paleogene_epoch","label":"Paleogene Epoch","required":"false","cv":["oligocene","eocene","paleocene"]},{"name":"age_modifier","label":"Age Modifier","required":"false","cv":["late","middle","early"]}]},"sample_locality":{"typelabel":"Sample Locality (point)","columns":[{"name":"sample_id_name","label":"Sample specific ID \/ Name","required":"true"},{"name":"oriented_sample","label":"Oriented Sample","required":"false","cv":["yes","no"]},{"name":"sample_orientation_strike","label":"Sample Orientation Strike","required":"true"},{"name":"sample_orientation_dip","label":"Sample Orientation Dip","required":"true"},{"name":"material_type","label":"Material Type","required":"true","cv":["intact_rock","fragmented_roc","sediment","other"]},{"name":"Other_Material_Type","label":"Other Material Type","required":"true"},{"name":"material_details","label":"Material Details","required":"false"},{"name":"sample_size_cm","label":"Sample Size (cm)","required":"false"},{"name":"main_sampling_purpose","label":"Main Sampling Purpose","required":"true","cv":["fabric___micro","petrology","geochronology","geochemistry","other"]},{"name":"sample_description","label":"Sample Description","required":"false"},{"name":"other_comments_about_sampling","label":"Other Comments About Sampling","required":"false"},{"name":"inferred_age_of_sample_ma","label":"Inferred Age of Sample (Ma)","required":"false"}]}}';

$cvvals=json_decode($cvjson);
*/

//print_r($cvvals);

?>