<?php
/**
 * File: searchWorker.php
 * Description: Search interface for querying and filtering data
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

session_start();

if($_SESSION['userpkey']!=""){
	$userpkey = $_SESSION['userpkey'];
}else{
	$userpkey = 0;
}

include($_SERVER['DOCUMENT_ROOT']."/includes/config.inc.php");
include($_SERVER['DOCUMENT_ROOT']."/db.php");
include $_SERVER['DOCUMENT_ROOT']."/neodb.php"; //neo4j database abstraction layer
include $_SERVER['DOCUMENT_ROOT']."/db/strabospotclass.php"; //strabospot specific functions
include_once($_SERVER['DOCUMENT_ROOT'].'/includes/geophp/geoPHP.inc'); //geospatial functions
include_once($_SERVER['DOCUMENT_ROOT'].'/includes/UUID.php'); //UUID Class
include_once($_SERVER['DOCUMENT_ROOT'].'/includes/straboClasses/searchQueryRowBuilder.php'); //Build SearchQuery
include_once($_SERVER['DOCUMENT_ROOT'].'/includes/straboClasses/microSearchQueryRowBuilder.php'); //Build SearchQuery
include_once($_SERVER['DOCUMENT_ROOT'].'/includes/straboClasses/expSearchQueryRowBuilder.php'); //Build SearchQuery

$fieldquerybuilder = new searchQueryRowBuilder();
$fieldquerybuilder->setDb($db);

$microquerybuilder = new microSearchQueryRowBuilder();
$microquerybuilder->setDb($db);

$expquerybuilder = new expSearchQueryRowBuilder();
$expquerybuilder->setDb($db);

$geoPHP = new geoPHP;

include_once("searchResultsClass.php");

$sr = new searchResults($db,$fieldquerybuilder,$microquerybuilder,$expquerybuilder,$json,$userpkey,$searchType);

?>