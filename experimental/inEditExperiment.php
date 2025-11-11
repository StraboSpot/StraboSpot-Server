<?php
/**
 * File: inEditExperiment.php
 * Description: Experiment editing interface for modifying parameters and data
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */



if(file_exists("log.txt")){

	$text = print_r($_REQUEST, true);
	file_put_contents ("log.txt", $text, FILE_APPEND);

	$text = print_r($_FILES, true);
	file_put_contents ("log.txt", $text, FILE_APPEND);

	file_put_contents ("log.txt", "\n\n\n*************************************************************************\n\n\n", FILE_APPEND);

}


$experiment_pkey = intval($_REQUEST['experiment_pkey']);

if($experiment_pkey == "") $experiment_pkey = 99999999;

include_once("../adminkeys.php");

//Check for Login Timeout Here
include("apiLoginCheck.php");

include("../prepare_connections.php");
include("../expdb/straboexpclass.php");

$count = $db->get_var_prepared("SELECT count(*) FROM straboexp.experiment WHERE pkey = $1 AND userpkey = $2", array($experiment_pkey, $userpkey));
if($count == 0){
	$error = new stdClass();
	$error->Error = "Experiment not found.";
	header('Content-type: application/json');
	echo json_encode($error, JSON_PRETTY_PRINT);
	exit();
}

$project_pkey = $db->get_var_prepared("SELECT project_pkey FROM straboexp.experiment WHERE pkey = $1 AND userpkey = $2", array($experiment_pkey, $userpkey));


$exp = new StraboExp($neodb,$userpkey,$db);

//pass along uuid class
$uuid = new UUID();
$exp->setuuid($uuid);

//Check json for errors before passing to experimental API:
$json = $_REQUEST['json'];

$experiment_id = $_REQUEST['experiment_id'];


//dev
//$json = file_get_contents("test_experiment.json");




$json = json_decode($json);




if($json->facility != ""){

	//create version here
	$exp->createProjectVersion($project_pkey);

	$exp->deleteExperiment($experiment_pkey);
	$obj = $exp->insertExperiment($json, $project_pkey, $_FILES, $experiment_id);

	header('Content-type: application/json');
	echo json_encode($obj, JSON_PRETTY_PRINT);
}else{
	$error = new stdClass();
	$error->Error = "Invalid JSON provided.";
	header('Content-type: application/json');
	echo json_encode($error, JSON_PRETTY_PRINT);
}









?>