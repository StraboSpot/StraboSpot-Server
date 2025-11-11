<?php
/**
 * File: get_all_apparatuses.php
 * Description: Retrieves and returns all apparatuses data
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


include_once("../adminkeys.php");

//Check for Login Timeout Here
include("apiLoginCheck.php");

include("../prepare_connections.php");
include("../expdb/straboexpclass.php");

$exp = new StraboExp($neodb,$userpkey,$db);

//pass along uuid class
$uuid = new UUID();
$exp->setuuid($uuid);

$obj = $exp->getExperimentalApparatuses();
header('Content-type: application/json');
echo json_encode($obj, JSON_PRETTY_PRINT);





?>