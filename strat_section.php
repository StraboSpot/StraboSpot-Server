<?php

include("logincheck.php");
include("prepare_connections.php");
include_once("includes/straboSVG.php");

$strat_spot_id = $_GET['id'];
$dataset_id = $_GET['did'];

if($strat_spot_id == "" || $dataset_id == ""){
	exit("no parameters given");
}

$parent_spot = $strabo->getSingleSpot($strat_spot_id);

//$strabo->dumpVar($parent_spot);exit();

/*
stdClass Object
(
    [properties] => stdClass Object
        (
            [date] => 2020-05-11T17:50:19.000Z
            [sed] => stdClass Object
                (
                    [strat_section] => stdClass Object
                        (
                            [strat_section_id] => 15892194458512
                            [column_profile] => clastic
                            [column_y_axis_units] => m
                            [misc_labels] => 1
                            [section_well_name] => Sec
                            [display_lithology_patterns] => 1
                            [section_type] => core
                            [what_core_repository] => Qwwe
                            [type_of_corer] => Ert
                            [depth_from_surface_to_start_of] => Dfg
                            [total_core_length] => Fdr
                            [location_locality] => Fghedfr
                            [basin] => Rfgt
                            [age] => Www
                            [purpose] => Array
                                (
                                    [0] => geochronology
                                )

                        )

                )

            [name] => 15892194197775
            [time] => 2020-05-11T17:50:19.000Z
            [id] => 15892194197775
            [geometrytype] => Point
            [modified_timestamp] => 1589219552118
            [self] => https://strabospot.org/db/feature/15892194197775
        )

    [geometry] => stdClass Object
        (
            [type] => Point
            [coordinates] => Array
                (
                    [0] => -97.678746
                    [1] => 38.576741
                )

        )

    [type] => Feature
)

*/



$strat_section_id = $parent_spot->properties->sed->strat_section->strat_section_id;
$strat_section = $parent_spot->properties->sed->strat_section;

$spots = $strabo->getDatasetSpots($dataset_id);
$spots = $spots['features'];

$allspots = [];
foreach($spots as $spot){
	if($spot['properties']['strat_section_id']==$strat_section_id){
		array_push($allspots,$spot);
	}
}

//$strabo->dumpVar($allspots);exit();

/*
Array
(
    [0] => Array
        (
            [geometry] => stdClass Object
                (
                    [type] => GeometryCollection
                    [geometries] => Array
                        (
                            [0] => stdClass Object
                                (
                                    [type] => Polygon
                                    [coordinates] => Array
                                        (
                                            [0] => Array
                                                (
                                                    [0] => Array
                                                        (
                                                            [0] => 0
                                                            [1] => 20
                                                        )

                                                    [1] => Array
                                                        (
                                                            [0] => 0
                                                            [1] => 20
                                                        )

                                                    [2] => Array
                                                        (
                                                            [0] => 20
                                                            [1] => 20
                                                        )

                                                    [3] => Array
                                                        (
                                                            [0] => 20
                                                            [1] => 20
                                                        )

                                                    [4] => Array
                                                        (
                                                            [0] => 0
                                                            [1] => 20
                                                        )

                                                )

                                        )

                                )

                            [1] => stdClass Object
                                (
                                    [type] => Polygon
                                    [coordinates] => Array
                                        (
                                            [0] => Array
                                                (
                                                    [0] => Array
                                                        (
                                                            [0] => 0
                                                            [1] => 20
                                                        )

                                                    [1] => Array
                                                        (
                                                            [0] => 0
                                                            [1] => 60
                                                        )

                                                    [2] => Array
                                                        (
                                                            [0] => 83.3
                                                            [1] => 60
                                                        )

                                                    [3] => Array
                                                        (
                                                            [0] => 83.3
                                                            [1] => 20
                                                        )

                                                    [4] => Array
                                                        (
                                                            [0] => 0
                                                            [1] => 20
                                                        )

                                                )

                                        )

                                )

                        )

                )

            [properties] => Array
                (
                    [date] => 2020-05-11T17:54:37.000Z
                    [altitude] => 404.7
                    [lng] => -97.679223
                    [strat_section_id] => 15892194458512
                    [surface_feature] => stdClass Object
                        (
                            [surface_feature_type] => strat_interval
                        )

                    [modified_timestamp] => 1589219677640
                    [sed] => stdClass Object
                        (
                            [character] => interbedded
                            [interval] => stdClass Object
                                (
                                    [interval_thickness] => 2
                                    [thickness_units] => m
                                )

                            [bedding] => stdClass Object
                                (
                                    [beds] => Array
                                        (
                                            [0] => stdClass Object
                                                (
                                                    [interbed_thickness_units] => m
                                                    [max_thickness] => 3
                                                    [min_thickness] => 2
                                                )

                                            [1] => stdClass Object
                                                (
                                                    [max_thickness] => 4
                                                    [min_thickness] => 3
                                                )

                                        )

                                    [interbed_proportion_change] => decrease
                                    [interbed_proportion] => 25
                                )

                            [lithologies] => Array
                                (
                                    [0] => stdClass Object
                                        (
                                            [primary_lithology] => siliciclastic
                                            [siliciclastic_type] => shale
                                            [mud_silt_grain_size] => silt
                                            [fresh_color] => Dd
                                            [weathered_color] => Fgh
                                        )

                                    [1] => stdClass Object
                                        (
                                            [primary_lithology] => limestone
                                            [dunham_classification] => boundstone
                                        )

                                )

                        )

                    [name] => 15892196775969
                    [time] => 2020-05-11T17:54:37.000Z
                    [id] => 15892196775969
                    [lat] => 38.576763
                    [self] => https://strabospot.org/db/feature/15892196775969
                )

            [type] => Feature
        )

    [1] => Array
        (
            [geometry] => stdClass Object
                (
                    [type] => Polygon
                    [coordinates] => Array
                        (
                            [0] => Array
                                (
                                    [0] => Array
                                        (
                                            [0] => 0
                                            [1] => 0
                                        )

                                    [1] => Array
                                        (
                                            [0] => 0
                                            [1] => 20
                                        )

                                    [2] => Array
                                        (
                                            [0] => 80
                                            [1] => 20
                                        )

                                    [3] => Array
                                        (
                                            [0] => 80
                                            [1] => 0
                                        )

                                    [4] => Array
                                        (
                                            [0] => 0
                                            [1] => 0
                                        )

                                )

                        )

                )

            [properties] => Array
                (
                    [date] => 2020-05-11T17:53:28.000Z
                    [altitude] => 404.7
                    [lng] => -97.679319
                    [strat_section_id] => 15892194458512
                    [surface_feature] => stdClass Object
                        (
                            [surface_feature_type] => strat_interval
                        )

                    [modified_timestamp] => 1589219608705
                    [sed] => stdClass Object
                        (
                            [character] => bed
                            [interval] => stdClass Object
                                (
                                    [interval_thickness] => 1
                                    [thickness_units] => m
                                )

                            [lithologies] => Array
                                (
                                    [0] => stdClass Object
                                        (
                                            [primary_lithology] => siliciclastic
                                            [siliciclastic_type] => sandstone
                                            [sand_grain_size] => sand_coarse_l
                                            [fresh_color] => Edf
                                            [weathered_color] => Frg
                                        )

                                )

                        )

                    [name] => 15892196086553
                    [time] => 2020-05-11T17:53:28.000Z
                    [id] => 15892196086553
                    [lat] => 38.576804
                    [self] => https://strabospot.org/db/feature/15892196086553
                )

            [type] => Feature
        )

)


*/

$svg = new straboSVG($allspots,$strat_section);

$svg->outToBrowser();



?>