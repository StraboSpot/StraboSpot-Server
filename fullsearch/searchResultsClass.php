<?php
/**
 * File: searchResultsClass.php
 * Description: Search interface for querying and filtering data for querying data
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

class searchResults
{

 	public function searchResults($db,$fieldquerybuilder,$microquerybuilder,$expquerybuilder,$json,$userpkey,$searchType){
 		$this->db=$db;
 		$this->fieldquerybuilder = $fieldquerybuilder;
 		$this->microquerybuilder = $microquerybuilder;
 		$this->expquerybuilder = $expquerybuilder;
 		$this->json=$json;
 		$this->userpkey=(int)$userpkey;
 		$this->searchType=$searchType;
 		$this->testing = false;

 		$data = json_decode($json);
 		if($data->searchType != "") $this->searchType = $data->searchType;

 	}

 	public function setuserpkey($userpkey){
 		$this->userpkey=$userpkey;
 	}

 	public function setjson($json){
 		$this->json=$json;
 		$data = json_decode($json);
 		if($json->searchType != "") $this->searchType = $json->searchType;
 	}

 	public function setSearchType($searchType){
 		$this->searchType=$searchType;
 	}

	public function logToFile($string){
		if(file_exists($_SERVER['DOCUMENT_ROOT']."/fullsearch/log.txt")){
			file_put_contents ($_SERVER['DOCUMENT_ROOT']."/fullsearch/log.txt", "\n\n************************************************************************************************************************\n\n", FILE_APPEND);
			file_put_contents ($_SERVER['DOCUMENT_ROOT']."/fullsearch/log.txt", "\n".$string, FILE_APPEND);
		}
	}

	public function getResults(){

		$out['searchType'] = $this->searchType;

		//StraboField
		//******************************************************************************************
		if(1==1){
		$this->fieldsearchrows = $this->fieldquerybuilder->buildSearchQueryRows($this->json);

		$fieldquery = "select
					users.pkey as userpkey,
					users.firstname,
					users.lastname,
					project.strabo_project_id,
					project.project_name,
					project.notes,
					dataset.strabo_dataset_id,
					dataset.dataset_name,
					to_char ( last_modified, 'MM/DD/YYYY' ) as last_modified,
					ST_AsText(dataset.location) as dataset_wkt,
					ST_AsText(project.location) as project_wkt,

					count(distinct(spot.spot_pkey)) as spotcount,
					count(distinct(image.image_pkey)) as imagecount,

					(select count(*) from spot where project_pkey = project.project_pkey) as rawspotcount,
					(select count(*) from image where project_pkey = project.project_pkey) as rawimagecount,
					(select count(*) from dataset where project_pkey = project.project_pkey) as rawdatasetcount


					from
					users
					FULL OUTER JOIN project ON users.pkey = project.user_pkey
					FULL OUTER JOIN dataset on project.project_pkey = dataset.project_pkey
					FULL OUTER JOIN spot on dataset.dataset_pkey = spot.dataset_pkey
					FULL OUTER JOIN image on spot.spot_pkey = image.spot_pkey
					FULL OUTER JOIN sample on spot.spot_pkey = sample.spot_pkey
					FULL OUTER JOIN rock_type on spot.spot_pkey = rock_type.spot_pkey
					where
					(
						(project.ispublic = true or project.user_pkey = $this->userpkey)
					) and (
					1 = 1
					and spot.location is not null
					$this->fieldsearchrows
					)
					group by
					userpkey,
					firstname,
					lastname,
					strabo_project_id,
					project_name,
					strabo_dataset_id,
					dataset_name,
					dataset_wkt,
					project_wkt,
					last_modified,
					notes,
					rawspotcount,
					rawimagecount,
					rawdatasetcount
					order by userpkey, strabo_project_id
					";


		$this->logToFile($fieldquery);



		//Now do query
		$rows = $this->db->get_results($fieldquery);

		$allprojects = [];
		$currentprojectid = "";
		$projectcount=0;
		$datasetcount=0;
		$spotcount=0;


		foreach($rows as $row){
			$uniqid = $row->userpkey . "-" . $row->strabo_project_id;
			if($uniqid != $currentprojectid){
				if($currentprojectid!=""){
					//$currentproject['dataset_count'] = count($projectdatasets);
					//$currentproject['spot_count'] = $projectspotcount;
					$currentproject['datasets'] = $projectdatasets;
					$allprojects[]=$currentproject;
					unset($currentproject);
					unset($projectdatasets);
					$projectspotcount = 0;
				}
				$currentprojectid = $uniqid;
				$projectcount++;
			}

			$currentproject['owner_firstname'] = $row->firstname;
			$currentproject['owner_lastname'] = $row->lastname;
			$currentproject['owner_id'] = $row->userpkey;
			$currentproject['project_id'] = $row->strabo_project_id;
			$currentproject['project_name'] = $row->project_name;
			$currentproject['notes'] = $row->notes;
			$currentproject['project_location'] = $row->project_wkt;
			$currentproject['last_modified'] = $row->last_modified;

			$currentproject['dataset_count'] = (int)$row->rawdatasetcount;
			$currentproject['image_count'] = (int)$row->rawimagecount;
			$currentproject['spot_count'] = (int)$row->rawspotcount;

			unset($dataset);

			$dataset['owner_firstname'] = $row->firstname;
			$dataset['owner_lastname'] = $row->lastname;
			$dataset['owner_id'] = $row->userpkey;
			$dataset['dataset_id'] = $row->userpkey."-".$row->strabo_dataset_id;

			$dataset_name = $row->dataset_name;
			if($dataset_name==""){
				$dataset_name = "Default";
			}
			$dataset['dataset_name'] = $dataset_name;

			$dataset['dataset_location'] = $row->dataset_wkt;
			$dataset['spot_count'] = $row->spotcount;

			$projectspotcount += $row->spotcount;

			$projectdatasets[] = $dataset;

			$datasetcount++;
			$spotcount += $row->spotcount;

		}

		if($currentproject['project_id'] != ""){
					//$currentproject['dataset_count'] = count($projectdatasets);
					//$currentproject['spot_count'] = $projectspotcount;
					$currentproject['datasets'] = $projectdatasets;
			$currentproject['datasets'] = $projectdatasets;
			$allprojects[]=$currentproject;
		}

		$strabofieldout['counts']['projectcount']=$projectcount;
		$strabofieldout['counts']['datasetcount']=$datasetcount;
		$strabofieldout['counts']['spotcount']=$spotcount;

		if($this->searchType != "count"){
			if(count($allprojects) > 0){
				$strabofieldout['projects']=$allprojects;
			}
		}

		if($this->searchType != "count" && 1==2){

			$geojsonfeatures = [];

			foreach($rows as $row){
				unset($thisfeature);
				$thisfeature['type'] = "Feature";

				$points = str_replace("POINT(", "", $row->dataset_wkt);
				$points = str_replace(")", "", $points);
				$points = explode(" ", $points);
				$longitude = $points[0];
				$latitude = $points[1];

				$thisfeature['geometry']['type'] = "Point";
				$thisfeature['geometry']['coordinates'][0] = (float)$longitude;
				$thisfeature['geometry']['coordinates'][1] = (float)$latitude;

				$thisfeature['properties']['name'] = $row->dataset_name;
				$thisfeature['properties']['projectname'] = $row->project_name;
				$thisfeature['properties']['id'] = $row->userpkey."-".$row->strabo_dataset_id;
				$thisfeature['properties']['count'] = $row->spotcount;
				$thisfeature['properties']['owner'] = $row->firstname." ".$row->lastname;

				$geojsonfeatures[] = $thisfeature;

			}

			$geojsonfeaturecollection['type'] = "FeatureCollection";

			$geojsonfeaturecollection['features'] = $geojsonfeatures;

			$strabofieldout['geoJSON'] = $geojsonfeaturecollection;

		}

		$out["straboField"] = $strabofieldout;

		}//end if 1 == 1









if(1==1){
		//StraboMicro
		//******************************************************************************************
		$this->microsearchrows = $this->microquerybuilder->buildSearchQueryRows($this->json);

		$microquery = "select
					users.pkey as userpkey,
					users.firstname,
					users.lastname,
					strabomicro.micro_projectmetadata.strabo_id as strabo_project_id,
					strabomicro.micro_projectmetadata.name as project_name,
					strabomicro.micro_projectmetadata.notes as notes,
					to_char ( strabomicro.micro_projectmetadata.uploaddate, 'MM/DD/YYYY' ) as last_modified,
					strabomicro.micro_samplemetadata.strabo_id as strabo_sample_id,
					strabomicro.micro_samplemetadata.label as sample_name,
					count(distinct(strabomicro.micro_micrographmetadata.id)) as micrographcount,
					count(distinct(strabomicro.micro_spotmetadata.id)) as spotcount
					from
					users
					FULL OUTER JOIN strabomicro.micro_projectmetadata ON users.pkey = strabomicro.micro_projectmetadata.userpkey
					FULL OUTER JOIN strabomicro.micro_datasetmetadata on strabomicro.micro_projectmetadata.id = strabomicro.micro_datasetmetadata.project_id
					FULL OUTER JOIN strabomicro.micro_samplemetadata on strabomicro.micro_datasetmetadata.id = strabomicro.micro_samplemetadata.dataset_id
					FULL OUTER JOIN strabomicro.micro_micrographmetadata on strabomicro.micro_samplemetadata.id = strabomicro.micro_micrographmetadata.sample_id
					FULL OUTER JOIN strabomicro.micro_spotmetadata on strabomicro.micro_micrographmetadata.id = strabomicro.micro_spotmetadata.micrograph_id
					where
					(
						(strabomicro.micro_projectmetadata.ispublic = true or strabomicro.micro_projectmetadata.userpkey = $this->userpkey)
					) and (
					1 = 1
					$this->microsearchrows
					)
					group by
					users.pkey,
					firstname,
					lastname,
					strabo_project_id,
					project_name,
					micro_projectmetadata.notes,
					last_modified,
					strabo_sample_id,
					sample_name
					order by userpkey, strabo_project_id
					";

		$this->logToFile($microquery);


		//Now do query
		$rows = $this->db->get_results($microquery);


		if(1==1){
		$allprojects = [];
		$currentprojectid = "";
		$projectcount=0;
		$samplecount=0;
		$micrographcount=0;
		$spotcount=0;
		unset($currentproject);

		foreach($rows as $row){
			$uniqid = $row->userpkey . "-" . $row->strabo_project_id;
			if($uniqid != $currentprojectid){
				if($currentprojectid!=""){
					$currentproject['sample_count'] = count($projectsamples);
					$currentproject['micrograph_count'] = $projectmicrographcount;
					$currentproject['spot_count'] = $projectspotcount;
					$currentproject['samples'] = $projectsamples;
					$allprojects[]=$currentproject;
					unset($currentproject);
					unset($projectsamples);
					$projectmicrographcount = 0;
					$projectspotcount = 0;
				}
				$currentprojectid = $uniqid;
				$projectcount++;
			}

			$currentproject['owner_firstname'] = $row->firstname;
			$currentproject['owner_lastname'] = $row->lastname;
			$currentproject['owner_id'] = $row->userpkey;
			$currentproject['project_id'] = $row->strabo_project_id;
			$currentproject['project_name'] = $row->project_name;
			$currentproject['notes'] = $row->notes;
			$currentproject['last_modified'] = $row->last_modified;
			$currentproject['project_location'] = $row->project_wkt;

			unset($sample);

			$sample['owner_firstname'] = $row->firstname;
			$sample['owner_lastname'] = $row->lastname;
			$sample['owner_id'] = $row->userpkey;
			$sample['sample_id'] = $row->userpkey."-".$row->strabo_sample_id;

			$sample_name = $row->sample_name;
			if($sample_name==""){
				$sample_name = "None";
			}
			$sample['sample_name'] = $sample_name;

			//$dataset['dataset_location'] = $row->dataset_wkt;
			$sample['micrograph_count'] = $row->micrographcount;
			$projectmicrographcount += $row->micrographcount;
			$sample['spot_count'] = $row->spotcount;
			$projectspotcount += $row->spotcount;

			$projectsamples[] = $sample;

			$samplecount++;
			$micrographcount += $row->micrographcount;
			$spotcount += $row->spotcount;
		}

		if($currentproject['project_id'] != ""){
			$currentproject['sample_count'] = count($projectsamples);
			$currentproject['micrograph_count'] = $projectmicrographcount;
			$currentproject['spot_count'] = $projectspotcount;
			$currentproject['samples'] = $projectsamples;
			$allprojects[]=$currentproject;
		}

		$strabomicroout['counts']['projectcount']=$projectcount;
		$strabomicroout['counts']['samplecount']=$samplecount;
		$strabomicroout['counts']['micrographcount']=$micrographcount;
		$strabomicroout['counts']['spotcount']=$spotcount;

		if($this->searchType != "count"){
			if(count($allprojects) > 0){
				$strabomicroout['projects']=$allprojects;
			}
		}

		if($this->searchType == "ddddcount"){

			$geojsonfeatures = [];

			foreach($rows as $row){
				unset($thisfeature);
				$thisfeature['type'] = "Feature";

				$points = str_replace("POINT(", "", $row->dataset_wkt);
				$points = str_replace(")", "", $points);
				$points = explode(" ", $points);
				$longitude = $points[0];
				$latitude = $points[1];

				$thisfeature['geometry']['type'] = "Point";
				$thisfeature['geometry']['coordinates'][0] = (float)$longitude;
				$thisfeature['geometry']['coordinates'][1] = (float)$latitude;

				$thisfeature['properties']['name'] = $row->dataset_name;
				$thisfeature['properties']['projectname'] = $row->project_name;
				$thisfeature['properties']['id'] = $row->userpkey."-".$row->strabo_dataset_id;
				$thisfeature['properties']['count'] = $row->spotcount;
				$thisfeature['properties']['owner'] = $row->firstname." ".$row->lastname;

				$geojsonfeatures[] = $thisfeature;

			}

			$geojsonfeaturecollection['type'] = "FeatureCollection";

			$geojsonfeaturecollection['features'] = $geojsonfeatures;

			$strabomicroout['geoJSON'] = $geojsonfeaturecollection;

		}

		$out["straboMicro"] = $strabomicroout;
		}

}

if(1==1){
		//StraboExperimental
		//******************************************************************************************
		$this->expsearchrows = $this->expquerybuilder->buildSearchQueryRows($this->json);


		$expquery = "select
					u.pkey as userpkey,
					u.firstname,
					u.lastname,
					p.pkey as project_pkey,
					p.uuid as strabo_project_uuid,
					p.name as project_name,
					p.notes as notes,
					to_char ( p.modified_timestamp, 'MM/DD/YYYY' ) as last_modified,
					to_char ( p.created_timestamp, 'MM/DD/YYYY' ) as created_on,
					date_part('epoch', created_timestamp) as project_id,
					(select count(*) from straboexp.experiment where project_pkey = p.pkey) as experiment_count
					from
					straboexp.project p,
					users u
					where
					p.userpkey = u.pkey and
					(
					(p.ispublic = true or p.userpkey = $this->userpkey)
					) and (
					1 = 1
					$this->expsearchrows
					)
					";
		$this->logToFile($expquery); //exit();


		//Now do query
		$rows = $this->db->get_results($expquery);

		$allprojects = [];
		$currentprojectid = "";
		$projectcount=0;
		$experimentcount=0;
		unset($currentproject);

		foreach($rows as $row){

			$currentproject['owner_firstname'] = $row->firstname;
			$currentproject['owner_lastname'] = $row->lastname;
			$currentproject['owner_id'] = $row->userpkey;
			$currentproject['project_id'] = (int)$row->project_id . rand(111,999);
			$currentproject['project_uuid'] = $row->strabo_project_uuid;
			$currentproject['project_name'] = $row->project_name;
			$currentproject['notes'] = $row->notes;
			$currentproject['last_modified'] = $row->last_modified;
			$currentproject['created_on'] = $row->created_on;
			$currentproject['experiment_count'] = $row->experiment_count;
			//$currentproject['project_location'] = $row->project_wkt;

			$projectcount ++;
			$experimentcount += $row->experiment_count;

			$allprojects[] = $currentproject;
		}

		$straboexpout['counts']['projectcount']=$projectcount;
		$straboexpout['counts']['experimentcount']=$experimentcount;

		if($this->searchType != "count"){
			if(count($allprojects) > 0){
				$straboexpout['projects']=$allprojects;
			}
		}

		$out["straboExperimental"] = $straboexpout;

}


























































		return $out;










	}




















}