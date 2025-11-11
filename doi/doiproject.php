<?php
/**
 * File: doiproject.php
 * Description: Handles doiproject operations
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

include("../prepare_connections.php");

function median($arr){
	if($arr){
		$count = count($arr);
		sort($arr);
		$mid = floor(($count-1)/2);
		return ($arr[$mid]+$arr[$mid+1-$count%2])/2;
	}
	return 0;
}

//https://strabospot.org/doi/doiproject.php?u=0021a279-6e8d-4176-a9f5-94f1742fe128
//10.1080/10509585.2015.1092083



$uuid = pg_escape_string($_GET['u']);
if($uuid == "") die("No UUID provided.");

/*
stdClass Object
(
	[pkey] => 1
	[doi] => 10.1080/10509585.2015.1092083
	[uuid] => 0021a279-6e8d-4176-a9f5-94f1742fe128
	[strabo_project_id] => 17139744543733
	[user_pkey] => 3
	[date_created] => 2024-05-16 14:16:05.795357-04
)
*/

$row = $db->get_row("select

							pkey,
							doi,
							uuid,
							strabo_project_id,
							user_pkey,
							to_char(date_created, 'Mon DD, YYYY HH12:MIAM OF (TZ)') as date_created

							from dois where uuid = '$uuid'");


//$uuid = $row->uuid;

//Get Owner
$userpkey = $row->user_pkey;
$ownerrow = $db->get_row("select * from users where pkey = $userpkey");
$ownerstring = $ownerrow->firstname . " " . $ownerrow->lastname;

$doi = $row->doi;

//Gather data from DOI file:
$json = file_get_contents("doiFiles/".$uuid."/data.json");
if($json == "") die("DOI file not found.");

$json = json_decode($json);

//Keep track for envelope
$left = 999999;
$right = -999999;
$top = -999999;
$bottom = 999999;

//Create output
$out = new stdClass();

$out->projectname = $json->projectDb->project->description->project_name;
$out->uuid = $row->uuid;
$out->doi = $doi;
$out->owner = $ownerstring;
$out->datecreated = $row->date_created;

$outDatasets = [];









$ds = $json->projectDb->datasets;
foreach($ds as $d){


	$thisds = new stdClass();
	$thisds->id = $d->id;
	$thisds->name = $d->name;
	$thisds->count = count($d->spotIds);
	$thisds->owner = $ownerstring;
	$thisds->projectname = $json->projectDb->project->description->project_name;
	$thisds->doi = $doi;

	$outDatasets[] = $thisds;
}










$out->datasets = $outDatasets;


//Gather spots
$lookSpots = $dataset->spotIds;
$foundSpots = [];

foreach($json->spotsDb as $key=>$spot){

	if($spot->properties->image_basemap == "" && $spot->properties->strat_section_id == ""){

		$coords = $spot->geometry->coordinates;

		if($spot->geometry->type=="Point"){

			$lon = $coords[0];
			$lat = $coords[1];

			$medianLongitudes[] = $lon;
			$medianLatitudes[] = $lat;

			if($lon > $right) $right = $lon;
			if($lon < $left) $left = $lon;
			if($lat > $top) $top = $lat;
			if($lat < $bottom) $bottom = $lat;

		}elseif($spot->geometry->type=="LineString"){

			foreach($coords as $coord){

				$lon = $coord[0];
				$lat = $coord[1];

				$medianLongitudes[] = $lon;
				$medianLatitudes[] = $lat;

				if($lon > $right) $right = $lon;
				if($lon < $left) $left = $lon;
				if($lat > $top) $top = $lat;
				if($lat < $bottom) $bottom = $lat;
			}

		}elseif($spot->geometry->type=="Polygon"){

			foreach($coords as $outercoords){

				foreach($outercoords as $coord){

					$lon = $coord[0];
					$lat = $coord[1];

					$medianLongitudes[] = $lon;
					$medianLatitudes[] = $lat;

					if($lon > $right) $right = $lon;
					if($lon < $left) $left = $lon;
					if($lat > $top) $top = $lat;
					if($lat < $bottom) $bottom = $lat;
				}

			}

		}

	}

}




//Envelope
$out->envelope="POLYGON (($left $bottom, $left $top, $right $top, $right $bottom, $left $bottom))";

//Centroid
$medianLongitude = (string) median($medianLongitudes);
$medianLatitude = (string) median($medianLatitudes);
$outCentroid['longitude'] = $medianLongitude;
$outCentroid['latitude'] = $medianLatitude;
$out->centroid = $outCentroid;


header('Content-Type: application/json; charset=utf-8');
echo json_encode($out, JSON_PRETTY_PRINT);











































/*
stdClass Object
(
	[mapNamesDb] => stdClass Object
		(
		)

	[mapTilesDb] => stdClass Object
		(
		)

	[otherMapsDb] => stdClass Object
		(
		)

	[projectDb] => stdClass Object
		(
			[activeDatasetsIds] => Array
				(
					[0] => 17158683183764
					[1] => 17139744543873
				)

			[selectedDatasetId] => 17158683183764
			[project] => stdClass Object
				(
					[date] => 2024-04-24T16:00:54.373Z
					[modified_timestamp] => 1715868420118
					[projecttype] => app
					[preferences] => stdClass Object
						(
							[images] => 1
							[orientations] => 1
							[spot_prefix] => Unnamed
							[starting_number_for_spot] => 19
						)

					[other_maps] => Array
						(
						)

					[tags] => Array
						(
							[0] => stdClass Object
								(
									[name] => Test Tag
									[type] => concept
									[id] => 17147443969622
									[spots] => Array
										(
											[0] => 17147440612358
											[1] => 17147455198673
										)

									[color] => #FF0000
									[concept_type] => geological_structure
								)

						)

					[other_features] => Array
						(
							[0] => geomorphic
							[1] => hydrologic
							[2] => paleontological
							[3] => igneous
							[4] => metamorphic
							[5] => sedimentological
							[6] => other
						)

					[description] => stdClass Object
						(
							[project_name] => PDF work
						)

					[templates] => stdClass Object
						(
						)

					[relationship_types] => Array
						(
							[0] => cross-cuts
							[1] => mutually cross-cuts
							[2] => is cut by
							[3] => is younger than
							[4] => is older than
							[5] => is lower metamorphic grade than
							[6] => is higher metamorphic grade than
							[7] => is included within
							[8] => includes
							[9] => merges with
						)

					[useContinuousTagging] =>
					[id] => 17139744543733
					[self] => https://strabospot.org/db/project/17139744543733
				)

			[datasets] => stdClass Object
				(
					[17158683183764] => stdClass Object
						(
							[date] => 2024-05-16T14:05:18.376Z
							[centroid] => POINT (-97.68183773960307 38.57838543233221)
							[name] => dataset2
							[id] => 17158683183764
							[modified_timestamp] => 1715868420118
							[datasettype] => app
							[self] => https://strabospot.org/db/dataset/17158683183764
							[images] => stdClass Object
								(
									[neededImageIds] => Array
										(
										)

									[imageIds] => Array
										(
										)

								)

							[spotIds] => Array
								(
									[0] => 17158683923727
									[1] => 17158684045558
									[2] => 17158684138573
									[3] => 17158684174265
									[4] => 17158684201137
								)

						)

					[17139744543873] => stdClass Object
						(
							[date] => 2024-04-24T16:00:54.387Z
							[centroid] => POINT (-97.67991000000001 38.57689)
							[name] => Default
							[id] => 17139744543873
							[modified_timestamp] => 1715189470745
							[datasettype] => app
							[self] => https://strabospot.org/db/dataset/17139744543873
							[images] => stdClass Object
								(
									[neededImageIds] => Array
										(
										)

									[imageIds] => Array
										(
											[0] => 17139745071967
										)

								)

							[spotIds] => Array
								(
									[0] => 17139744793598
									[1] => 17139746137005
									[2] => 17139746859758
									[3] => 17139747369228
									[4] => 17146615764756
									[5] => 17146628981113
									[6] => 17147440612358
									[7] => 17147455198673
									[8] => 17147456823808
									[9] => 17151881742805
								)

						)

				)

			[deviceBackUpDirectoryExists] => 1
			[backupFileName] => 2024-05-16_251pm_PDF_work
			[downloadsDirectory] =>
			[isTestingMode] =>
			[selectedProject] => stdClass Object
				(
					[project] =>
					[source] =>
				)

			[selectedTag] => stdClass Object
				(
					[name] => Test Tag
					[type] => concept
					[id] => 17147443969622
					[spots] => Array
						(
							[0] => 17147440612358
							[1] => 17147455198673
						)

					[color] => #FF0000
					[concept_type] => geological_structure
				)

			[isMultipleFeaturesTaggingEnabled] =>
			[addTagToSelectedSpot] =>
			[projectTransferProgress] => 0
			[isImageTransferring] =>
			[_persist] => stdClass Object
				(
					[version] => -1
					[rehydrated] => 1
				)

		)

	[spotsDb] => stdClass Object
		(
			[17139744793598] => stdClass Object
				(
					[properties] => stdClass Object
						(
							[images] => Array
								(
									[0] => stdClass Object
										(
											[width] => 4032
											[caption] => Desc…
											[id] => 17139745071967
											[title] => Base Image 1
											[modified_timestamp] => 1715349095898
											[height] => 3024
											[image_type] => photo
											[annotated] => 1
											[self] => https://strabospot.org/db/image/17139745071967
										)

								)

							[date] => 2024-04-24T16:01:19.000Z
							[name] => Point Spot
							[time] => 2024-04-24T16:01:19.000Z
							[id] => 17139744793598
							[modified_timestamp] => 1713974597771
							[self] => https://strabospot.org/db/feature/17139744793598
						)

					[geometry] => stdClass Object
						(
							[type] => Point
							[coordinates] => Array
								(
									[0] => -97.67991
									[1] => 38.57689
								)

						)

					[type] => Feature
				)

			[17139746137005] => stdClass Object
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
													[0] => 2064.9941229881
													[1] => 1930.9306416897
												)

											[1] => Array
												(
													[0] => 2587.512720215
													[1] => 1998.7087872352
												)

											[2] => Array
												(
													[0] => 2628.4184731713
													[1] => 1506.6454224046
												)

											[3] => Array
												(
													[0] => 2120.8289829776
													[1] => 1460.9623551545
												)

											[4] => Array
												(
													[0] => 2064.9941229881
													[1] => 1930.9306416897
												)

										)

								)

						)

					[properties] => stdClass Object
						(
							[date] => 2024-04-24T16:03:33.000Z
							[lng] => -97.67991
							[surface_feature] => stdClass Object
								(
									[surface_feature_type] => contiguous_outcrop
								)

							[modified_timestamp] => 1714745780453
							[image_basemap] => 17139745071967
							[name] => Poly on Photo
							[time] => 2024-04-24T16:03:33.000Z
							[id] => 17139746137005
							[lat] => 38.57689
							[self] => https://strabospot.org/db/feature/17139746137005
						)

					[type] => Feature
				)

			[17139746859758] => stdClass Object
				(
					[geometry] => stdClass Object
						(
							[type] => Point
							[coordinates] => Array
								(
									[0] => 1925.258887
									[1] => 1171.357741
								)

						)

					[properties] => stdClass Object
						(
							[date] => 2024-04-24T16:04:45.000Z
							[lng] => -97.67991
							[orientation_data] => Array
								(
								)

							[modified_timestamp] => 1714662785866
							[image_basemap] => 17139745071967
							[name] => Point w Linear
							[time] => 2024-04-24T16:04:45.000Z
							[id] => 17139746859758
							[lat] => 38.57689
							[self] => https://strabospot.org/db/feature/17139746859758
						)

					[type] => Feature
				)

			[17139747369228] => stdClass Object
				(
					[geometry] => stdClass Object
						(
							[type] => Point
							[coordinates] => Array
								(
									[0] => 1896.819423854
									[1] => 2346.6042054565
								)

						)

					[properties] => stdClass Object
						(
							[date] => 2024-04-24T16:05:36.000Z
							[lng] => -97.67991
							[orientation_data] => Array
								(
									[0] => stdClass Object
										(
											[id] => 155a9465-6812-40d1-b0c5-7f031067ede6
											[type] => planar_orientation
											[feature_type] => bedding
											[strike] => 301
											[dip] => 41
											[unix_timestamp] => 1714659241790
											[facing] => overturned
											[modified_timestamp] => 1714660113592
										)

								)

							[modified_timestamp] => 1714660113926
							[image_basemap] => 17139745071967
							[name] => Point w Planar
							[time] => 2024-04-24T16:05:36.000Z
							[id] => 17139747369228
							[lat] => 38.57689
							[self] => https://strabospot.org/db/feature/17139747369228
						)

					[type] => Feature
				)

			[17146615764756] => stdClass Object
				(
					[geometry] => stdClass Object
						(
							[type] => Point
							[coordinates] => Array
								(
									[0] => 2514.1946217049
									[1] => 1213.0684342379
								)

						)

					[properties] => stdClass Object
						(
							[date] => 2024-05-02T14:52:56.000Z
							[viewed_timestamp] => 1714661576475
							[lng] => -97.67991
							[orientation_data] => Array
								(
									[0] => stdClass Object
										(
											[id] => 8ad18c7e-15fd-4706-9111-c9faf88776b5
											[type] => linear_orientation
											[feature_type] => slickenfibers
											[trend] => 115
											[plunge] => 0
											[unix_timestamp] => 1714661591038
										)

								)

							[modified_timestamp] => 1714661591241
							[symbology] => stdClass Object
								(
									[circleColor] => transparent
								)

							[image_basemap] => 17139745071967
							[name] => Unnamed6
							[time] => 2024-05-02T14:52:56.000Z
							[id] => 17146615764756
							[lat] => 38.57689
							[self] => https://strabospot.org/db/feature/17146615764756
						)

					[type] => Feature
				)

			[17146628981113] => stdClass Object
				(
					[geometry] => stdClass Object
						(
							[type] => Point
							[coordinates] => Array
								(
									[0] => 1731.6705148347
									[1] => 1724.1553247591
								)

						)

					[properties] => stdClass Object
						(
							[date] => 2024-05-02T15:14:58.000Z
							[viewed_timestamp] => 1714662898111
							[lng] => -97.67991
							[orientation_data] => Array
								(
									[0] => stdClass Object
										(
											[id] => 49c8b880-e788-4b68-9566-3011d7088b0f
											[type] => planar_orientation
											[feature_type] => fault
											[strike] => 100
											[dip] => 35
											[unix_timestamp] => 1714662944459
											[modified_timestamp] => 1714663041823
										)

								)

							[modified_timestamp] => 1714663042161
							[symbology] => stdClass Object
								(
									[circleColor] => transparent
								)

							[image_basemap] => 17139745071967
							[name] => Unnamed7
							[time] => 2024-05-02T15:14:58.000Z
							[id] => 17146628981113
							[lat] => 38.57689
							[self] => https://strabospot.org/db/feature/17146628981113
						)

					[type] => Feature
				)

			[17147440612358] => stdClass Object
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
													[0] => 2777.2356685641
													[1] => 2383.7020986163
												)

											[1] => Array
												(
													[0] => 3161.9579793873
													[1] => 2774.5799665014
												)

											[2] => Array
												(
													[0] => 3543.6025119027
													[1] => 2362.1576493297
												)

											[3] => Array
												(
													[0] => 3451.2691572494
													[1] => 1897.4130977651
												)

											[4] => Array
												(
													[0] => 3368.1691381906
													[1] => 2343.6909783568
												)

											[5] => Array
												(
													[0] => 3158.8802010763
													[1] => 2488.3465671749
												)

											[6] => Array
												(
													[0] => 3131.1801947233
													[1] => 2143.6353766131
												)

											[7] => Array
												(
													[0] => 3026.5357259463
													[1] => 2343.6909783568
												)

											[8] => Array
												(
													[0] => 2777.2356685641
													[1] => 2383.7020986163
												)

										)

								)

						)

					[properties] => stdClass Object
						(
							[date] => 2024-05-03T13:47:41.000Z
							[viewed_timestamp] => 1714744061235
							[symbology] => stdClass Object
								(
									[fillColor] => rgba(0, 0, 255, 0.4)
								)

							[image_basemap] => 17139745071967
							[name] => TagPoly
							[time] => 2024-05-03T13:47:41.000Z
							[id] => 17147440612358
							[modified_timestamp] => 1714744173743
							[self] => https://strabospot.org/db/feature/17147440612358
						)

					[type] => Feature
				)

			[17147455198673] => stdClass Object
				(
					[geometry] => stdClass Object
						(
							[type] => LineString
							[coordinates] => Array
								(
									[0] => Array
										(
											[0] => 2915.4678574972
											[1] => 603.74833134475
										)

									[1] => Array
										(
											[0] => 3356.0292849035
											[1] => 903.19242646172
										)

									[2] => Array
										(
											[0] => 3727.7529892629
											[1] => 631.28342047237
										)

								)

						)

					[properties] => stdClass Object
						(
							[date] => 2024-05-03T14:11:59.000Z
							[viewed_timestamp] => 1714745519868
							[trace] => stdClass Object
								(
									[trace_feature] => 1
									[trace_type] => geologic_struc
									[trace_quality] => inferred(?)
									[geologic_structure_type] => shear_zone
								)

							[modified_timestamp] => 1715187799269
							[symbology] => stdClass Object
								(
									[lineColor] => #663300
									[lineWidth] => 2
									[lineDasharray] => Array
										(
											[0] => 1
											[1] => 0
										)

								)

							[image_basemap] => 17139745071967
							[name] => LineSpot
							[time] => 2024-05-03T14:11:59.000Z
							[id] => 17147455198673
							[self] => https://strabospot.org/db/feature/17147455198673
						)

					[type] => Feature
				)

			[17147456823808] => stdClass Object
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
													[0] => 3126.2940343425
													[1] => 1232.6188107059
												)

											[1] => Array
												(
													[0] => 3387.3022984683
													[1] => 1749.0222579188
												)

											[2] => Array
												(
													[0] => 3872.8338006166
													[1] => 1176.4880011945
												)

											[3] => Array
												(
													[0] => 3126.2940343425
													[1] => 1232.6188107059
												)

										)

								)

						)

					[properties] => stdClass Object
						(
							[date] => 2024-05-03T14:14:42.000Z
							[viewed_timestamp] => 1714745682380
							[symbology] => stdClass Object
								(
									[fillColor] => rgba(0, 0, 255, 0.4)
								)

							[image_basemap] => 17139745071967
							[name] => Triangle
							[time] => 2024-05-03T14:14:42.000Z
							[id] => 17147456823808
							[modified_timestamp] => 1714745699781
							[self] => https://strabospot.org/db/feature/17147456823808
						)

					[type] => Feature
				)

			[17151881742805] => stdClass Object
				(
					[geometry] => stdClass Object
						(
							[type] => LineString
							[coordinates] => Array
								(
									[0] => Array
										(
											[0] => 589.97911911133
											[1] => 485.36170778215
										)

									[1] => Array
										(
											[0] => 953.05499978631
											[1] => 1295.1164682079
										)

								)

						)

					[properties] => stdClass Object
						(
							[date] => 2024-05-08T17:09:34.000Z
							[viewed_timestamp] => 1715188174280
							[trace] => stdClass Object
								(
									[trace_feature] => 1
									[trace_type] => geologic_struc
									[geologic_structure_type] => shear_zone
									[trace_quality] => known
								)

							[modified_timestamp] => 1715189470892
							[symbology] => stdClass Object
								(
									[lineColor] => #663300
									[lineWidth] => 2
									[lineDasharray] => Array
										(
											[0] => 1
											[1] => 0
										)

								)

							[image_basemap] => 17139745071967
							[name] => Unnamed12
							[time] => 2024-05-08T17:09:34.000Z
							[id] => 17151881742805
							[self] => https://strabospot.org/db/feature/17151881742805
						)

					[type] => Feature
				)

		)

)


*/

?>