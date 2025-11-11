<?php
/**
 * File: delete_experiment.php
 * Description: Deletes experiments and related data
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

include("../logincheck.php");
include("../prepare_connections.php");
include("../expdb/straboexpclass.php");

$experiment_pkey = isset($_GET['e']) ? (int)$_GET['e'] : 0;
if($experiment_pkey == 0) die("Experiment not found.");
$count = $db->get_var_prepared("SELECT count(*) FROM straboexp.experiment WHERE pkey = $1 AND userpkey = $2", array($experiment_pkey, $userpkey));
if($count == 0) die("Experiment not found.");

$exp = new StraboExp($neodb,$userpkey,$db);

//pass along uuid class
$uuid = new UUID();
$exp->setuuid($uuid);

//create version here
$project_pkey = $db->get_var_prepared("SELECT project_pkey FROM straboexp.experiment WHERE pkey = $1", array($experiment_pkey));
if($project_pkey != "") $exp->createProjectVersion($project_pkey);




//$exp->deleteExperimentFiles($experiment_pkey); //what about versioning? Let's keep files for now and set up a CRON later to delete files if we need to.
$exp->deleteExperiment($experiment_pkey);

header("Location: /my_experimental_data");
?>