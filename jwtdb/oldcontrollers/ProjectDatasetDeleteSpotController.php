<?php
/**
 * File: ProjectDatasetDeleteSpotController.php
 * Description: ProjectDatasetDeleteSpotController class
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


class ProjectDatasetDeleteSpotController extends MyController
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
			if($upload['datasets']!=""){
				$datasets=$upload['datasets'];
				if(count($datasets) > 0){
					if($upload['spotId']!="" && $upload['spotId'] != null){
						$spotid=$upload['spotId'];
					}else{
						$errors.="No Spot ID Provided. ";
					}
				}else{
					$errors.="No Datasets Provided. ";
				}
			}
		}else{
			$errors.="No Project ID Provided. ";
		}

		$datasets=$upload['datasets'];
		$spotid=$upload['spotId'];

		if(!$errors){

			$userpkey = $this->strabo->userpkey;

			$pcount = $this->strabo->db->get_var("select count(*) from vprojects where userpkey=$userpkey and projectid='$projectid'");
			if($pcount > 0){
				$this->strabo->createVersion($projectid);
				$this->strabo->db->query("delete from vprojects where userpkey=$userpkey and projectid='$projectid'");
			}

			$injson = json_encode($upload['project']);
			$this->strabo->insertProject($injson);

			foreach($datasets as $d){
				$datasetid = $d->id;

				if($datasetid!=""){
					//first, delete dataset relationships
					$this->strabo->deleteDatasetRelationships($datasetid);
					$injson = json_encode($d);
					$this->strabo->insertDataset($injson);
					$this->strabo->addDatasetToProject($projectid,$datasetid,"HAS_DATASET");

					if($spotid!=""){

						$this->strabo->deleteSingleSpot($spotid);

					}
				}

				if($datasetid!=""){
					$this->strabo->buildDatasetRelationships($datasetid);
					$this->strabo->setDatasetCenter($datasetid);

					//also add dataset to Postgres Database here.
					$this->strabo->buildPgDataset($datasetid); //need to re-implement JMA 02282020
				}

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
