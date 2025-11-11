<?php
/**
 * File: ProjectDeleteDatasetController.php
 * Description: ProjectDeleteDatasetController class
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


class ProjectDeleteDatasetController extends MyController
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
			if($upload['datasetId']!=""){
				$datasetid=$upload['datasetId'];
			}else{
				$errors.="No Dataset ID Provided. ";
			}
		}else{
			$errors.="No Project ID Provided. ";
		}

		$datasetid=$upload['datasetId'];
		$projectid=$upload['project']->id;

		$dcount = $this->strabo->neodb->get_var("match (p:Project)-[HAS_DATASET]->(d:Dataset) where p.id = $projectid and d.id = $datasetid return count(d)");

		if(!$errors){

			$userpkey = $this->strabo->userpkey;

			$pcount = $this->strabo->db->get_var("select count(*) from vprojects where userpkey=$userpkey and projectid='$projectid'");
			if($pcount > 0){
				$this->strabo->createVersion($projectid);
				$this->strabo->db->query("delete from vprojects where userpkey=$userpkey and projectid='$projectid'");
			}

			$injson = json_encode($upload['project']);
			$this->strabo->insertProject($injson);

			//Delete Dataset
			if($dcount > 0) $this->strabo->deleteSingleDataset($datasetid);

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
