<?php
/**
 * File: delete_dataset.php
 * Description: Deletes datasets and associated records
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

include("logincheck.php");

include("prepare_connections.php");

$id = $_GET['id'];

$strabo->deleteSingleDataset($id);

header("Location:my_field_data");
?>