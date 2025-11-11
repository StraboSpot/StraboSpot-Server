<?php
/**
 * File: ProjectDatasetSpotController.php
 * Description: ProjectDatasetSpotController class
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


class ProjectDatasetSpotController extends MyController
{

	public function getAction($request) {

		header("Bad Request", true, 400);
		$data["Error"] = "Bad Request.";

		return $data;
	}

	public function postAction($request) {

		$upload = $request->parameters;
		unset($upload['apiformat']);

		$projectid = $upload['project']->id;
		if($projectid != ""){
			if($upload['dataset']!=""){
				$datasetid=$upload['dataset']->id;
				if($datasetid!=""){

					if($upload['spot']!=""){
						$spotid=$upload['spot']->properties->id;
						if($spotid==""){
						}
					}

				}else{
					$errors.="No Dataset ID Provided. ";
				}
			}
		}else{
			$errors.="No Project ID Provided. ";
		}

		if(!$errors){

			$userpkey = $this->strabo->userpkey;
			$pcount = $this->strabo->db->get_var("select count(*) from vprojects where userpkey=$userpkey and projectid='$projectid'");
			if($pcount > 0){
				$this->strabo->createVersion($projectid);
				$this->strabo->db->query("delete from vprojects where userpkey=$userpkey and projectid='$projectid'");
			}

			$injson = json_encode($upload['project']);
			$this->strabo->insertProject($injson);

			if($datasetid!=""){
				//first, delete dataset relationships
				$this->strabo->deleteDatasetRelationships($datasetid);
				$injson = json_encode($upload['dataset']);
				$this->strabo->insertDataset($injson);
				$this->strabo->addDatasetToProject($projectid,$datasetid,"HAS_DATASET");

				if($spotid!=""){

					$injson = json_encode($upload['spot']);
					$this->strabo->insertSpot($injson);
					$this->strabo->addSpotToDataset($datasetid,$spotid,"HAS_SPOT");

					//fix real-world coordinates here
					$thisspot = $upload['spot'];
					$image_basemap = $thisspot->properties->image_basemap;

					$mygeojson=$thisspot->geometry;
					$mygeojson=trim(json_encode($mygeojson));
					$mywkt=geoPHP::load($mygeojson,"json");
					$origwkt = $mywkt->out('wkt');

					if($image_basemap!=""){
						$newwkt = $this->strabo->fixSpotCoordinates($image_basemap);
					}

					$userpkey = $this->strabo->userpkey;

					if($newwkt!=""){
						$this->strabo->neodb->query("match (s:Spot) where s.userpkey=$userpkey and s.id=$spotid set s.wkt='$newwkt', s.origwkt='$origwkt'");
					}else{
						$this->strabo->neodb->query("match (s:Spot) where s.userpkey=$userpkey and s.id=$spotid set s.origwkt='$origwkt'");
					}
				}
			}

			if($datasetid!=""){
				$this->strabo->buildDatasetRelationships($datasetid);
				$this->strabo->setDatasetCenter($datasetid);

				//also add dataset to Postgres Database here.
				$this->strabo->buildPgDataset($datasetid); //need to re-implement JMA 02282020
			}

			$this->strabo->setProjectCenter($projectid);

			$data = $upload;
		}else{
			header("Bad Request", true, 400);
			$data["Error"] = $errors;
		}

		return $data;
	}

	public function deleteAction($request) {

		header("Bad Request", true, 400);
		$data["Error"] = "Bad Request.";

		return $data;
	}

	public function putAction($request) {

		header("Bad Request", true, 400);
		$data["Error"] = "Bad Request.";

		return $data;
	}

	public function optionsAction($request) {

		header("Bad Request", true, 400);
		$data["Error"] = "Bad Request.";

		return $data;
	}

	public function patchAction($request) {

		header("Bad Request", true, 400);
		$data["Error"] = "Bad Request.";

		return $data;
	}

	public function copyAction($request) {

		header("Bad Request", true, 400);
		$data["Error"] = "Bad Request.";

		return $data;
	}

	public function searchAction($request) {

		header("Bad Request", true, 400);
		$data["Error"] = "Bad Request.";

		return $data;
	}

}
