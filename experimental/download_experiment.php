<?php
/**
 * File: download_experiment.php
 * Description: Downloads experimental data and results
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

if(isset($_GET['u']) && $_GET['u'] != ""){
	$uuid = preg_replace('/[^a-zA-Z0-9\-]/', '', $_GET['u']);
	$experiment_pkey = $db->get_var_prepared("SELECT pkey FROM straboexp.experiment WHERE uuid = $1", array($uuid));
}else{
	$experiment_pkey = isset($_GET['e']) ? (int)$_GET['e'] : 0;
}

if($experiment_pkey == "" || $experiment_pkey == 0) die("Experiment not found.");
$row = $db->get_row_prepared("SELECT * FROM straboexp.experiment WHERE pkey = $1 AND userpkey = $2", array($experiment_pkey, $userpkey));
if($row->pkey == "") die("Experiment not found.");

$exp = new StraboExp($neodb,$userpkey,$db);

//pass along uuid class
$uuid = new UUID();
$exp->setuuid($uuid);

$filename = $row->id;
$filename = $exp->fixFileName($filename);

$json = $row->json;

header('Content-disposition: attachment; filename='.$filename);
header('Content-type: application/json');
echo $json;
?>


