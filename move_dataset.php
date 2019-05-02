<?
include("logincheck.php");

include("prepare_connections.php");

$projectid=$_GET['pid'];
$datasetid=$_GET['did'];

$projectid=$strabo->straboIDToID($projectid,"Project");
$datasetid=$strabo->straboIDToID($datasetid,"Dataset");

$neodb->query("match (a:Project)-[b:HAS_DATASET]->(c:Dataset) where a.userpkey=$userpkey and id(c)=$datasetid delete b");

$neodb->addRelationship($projectid, $datasetid, "HAS_DATASET");

header("Location:my_data");

?>