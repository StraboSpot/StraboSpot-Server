<?php
/**
 * File: ProjectDatasetsSpotsController.php
 * Description: ProjectDatasetsSpotsController class
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


class ProjectDatasetsSpotsController extends MyController
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
			
		}else{
			$errors.="No Project ID Provided. ";
		}

		$showout = json_decode(json_encode($upload));

		if(!$errors){

			$userpkey = $this->strabo->userpkey;
			$pcount = $this->strabo->db->get_var("select count(*) from vprojects where userpkey=$userpkey and projectid='$projectid'");
			if($pcount > 0){
				$this->strabo->createVersion($projectid);
				$this->strabo->db->query("delete from vprojects where userpkey=$userpkey and projectid='$projectid'");
			}

			$datasets = $upload['project']->datasets;

			unset($upload['project']->datasets);
			$injson = json_encode($upload['project'], JSON_PRETTY_PRINT);

			$this->strabo->insertProject($injson);

			if($datasets != ""){
				foreach($datasets as $dataset){

					$datasetid = $dataset->id;

					$spots = $dataset->spots;

					unset($dataset->spots);

					if($datasetid!=""){
						//first, delete dataset relationships
						$this->strabo->deleteDatasetRelationships($datasetid);
						$injson = json_encode($dataset, JSON_PRETTY_PRINT);
						$this->strabo->insertDataset($injson);
						$this->strabo->addDatasetToProject($projectid,$datasetid,"HAS_DATASET");

						if($spots->features != ""){

							foreach($spots->features as $spot){

								$spotid = $spot->properties->id;

								if($spotid!=""){

									$injson = json_encode($spot, JSON_PRETTY_PRINT);
									$this->strabo->insertSpot($injson);
									$this->strabo->addSpotToDataset($datasetid,$spotid,"HAS_SPOT");

								}

							}
						}

						$spotcount = count($spots->features);
						$userpkey = $this->strabo->userpkey;
						$this->strabo->db->query("
							insert into up_down_stats
								(
									userpkey,
									upload_download,
									data_type,
									count_type,
									count
								) values (
									$userpkey,
									'upload',
									'field app data',
									'spot',
									$spotcount
								)
						");

					}

					if($datasetid!=""){
						$this->strabo->buildDatasetRelationships($datasetid);
						$this->strabo->setDatasetCenter($datasetid);

						//also add dataset to Postgres Database here.
						$this->strabo->buildPgDataset($datasetid); //need to re-implement JMA 02282020
					}

				}

			}

			$this->strabo->setProjectCenter($projectid);

			$data = $showout;
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
