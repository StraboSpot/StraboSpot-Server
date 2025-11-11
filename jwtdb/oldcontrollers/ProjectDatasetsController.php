<?php
/**
 * File: ProjectDatasetsController.php
 * Description: ProjectDatasetsController class
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


class ProjectDatasetsController extends MyController
{

	public function getAction($request) {

		if(isset($request->url_elements[2])) {

			$feature_id = $request->url_elements[2];

			$data = $this->strabo->getProjectDatasets($feature_id);

		} else {
			header("Bad Request", true, 400);
			$data["Error"] = "Bad Request. No Dataset id specified.";
		}
		return $data;
	}

	public function postAction($request) {

		if(isset($request->url_elements[2])) {

			$feature_id = $request->url_elements[2];

			//********************************************************************
			// check for Project with userid and id
			//********************************************************************
			if($this->strabo->findProject($feature_id)){

				$upload = $request->parameters;

				unset($upload['apiformat']);

				$datasetid=$upload['id'];

				if($datasetid==""){

					// bad body sent, error
					header("Bad Request", true, 400);
					$data["Error"] = "Invalid body JSON sentt.";

				}else{

					//OK, check to see if feature exists
					if($this->strabo->findDataset($datasetid)){

						if(!$this->strabo->datasetExistsInOtherProject($datasetid,$feature_id)){

							//see if it already exists in group
							if(!$this->strabo->findDatasetInProject($feature_id,$datasetid)){

								//********************************************************************
								// Add to project
								//********************************************************************
								$this->strabo->addDatasetToProject($feature_id,$datasetid,"HAS_DATASET");

								header("Dataset added to project", true, 201);
								$data['message']="Dataset $datasetid added to project $feature_id.";

							}else{

								//Error, dataset already exists
								header("Dataset $datasetid already exists in project $feature_id.", true, 200);
								$data["Error"] = "Dataset $datasetid already exists in project $feature_id.";

							}

						}else{

							//Error, dataset already exists in another project
							header("Dataset $datasetid already exists in another project.", true, 200);
							$data["Error"] = "Dataset $datasetid already exists in another project.";

						}

					}else{

						//Error, feature not found
						header("Bad Request", true, 404);
						$data["Error"] = "Dataset $datasetid not found.";

					}

				}

			}else{
				//Error, feature not found
				header("Bad Request", true, 404);
				$data["Error"] = "Project $feature_id not found.";
			}

		} else { //feature id is not set error

			//Error, feature not found
			header("Bad Request", true, 404);
			$data["Error"] = "No project ID provided.";

		}

		return $data;
	}

	public function deleteAction($request) {

		if(isset($request->url_elements[2])) {

			$feature_id = $request->url_elements[2];

			$this->strabo->deleteProjectDatasets($feature_id);

			header("Datasets deleted", true, 204);
			$data['message']="Datasets deleted.";

		} else {
			header("Bad Request", true, 400);
			$data["Error"] = "Bad Request.";
		}
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
