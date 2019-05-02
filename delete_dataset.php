<?
include("logincheck.php");

include("prepare_connections.php");

$id = $_GET['id'];

$strabo->deleteSingleDataset($id);

header("Location:my_data");
?>