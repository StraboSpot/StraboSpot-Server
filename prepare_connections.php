<?php
/**
 * File: prepare_connections.php
 * Description: Initialize Databases
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

$userpkey=(int)$_SESSION['userpkey'];

if(!$userpkey){
	$userpkey=99999;
}

//Initialize Databases
include_once $_SERVER[DOCUMENT_ROOT]."/includes/config.inc.php"; //credentials, etc
include $_SERVER[DOCUMENT_ROOT]."/db.php"; //postgres database abstraction layer
include $_SERVER[DOCUMENT_ROOT]."/neodb.php"; //neo4j database abstraction layer
include $_SERVER[DOCUMENT_ROOT]."/db/strabospotclass.php"; //strabospot specific functions
include $_SERVER[DOCUMENT_ROOT]."/microdb/strabomicroclass.php";
include_once($_SERVER[DOCUMENT_ROOT]."/includes/geophp/geoPHP.inc"); //geospatial functions
include_once($_SERVER[DOCUMENT_ROOT]."/includes/UUID.php"); //UUID Class

//Initialize StraboSpot class
$strabo = new StraboSpot($neodb,$userpkey,$db);

//Initialize StraboMicro class
$sm = new StraboMicro($neodb,$userpkey,$db);

//pass along uuid class
$uuid = new UUID();
$strabo->setuuid($uuid);

?>