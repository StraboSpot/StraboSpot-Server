<?

/*
Helper script for creating connections

This script initializes:
 postgres database
 neo4j database
 strabospot class
 geophp class
*/

$userpkey=(int)$_SESSION['userpkey'];

if(!$userpkey){
	$userpkey=99999;
	//exit("Error! user pkey not set.");
}

//Initialize Databases
include_once "includes/config.inc.php"; //credentials, etc
include "db.php"; //postgres database abstraction layer
include "neodb.php"; //neo4j database abstraction layer
include "db/strabospotclass.php"; //strabospot specific functions
include_once('includes/geophp/geoPHP.inc'); //geospatial functions
include_once('includes/UUID.php'); //UUID Class

//Initialize StraboSpot class
$strabo = new StraboSpot($neodb,$userpkey,$db);

//pass along uuid class
$uuid = new UUID();
$strabo->setuuid($uuid);


?>