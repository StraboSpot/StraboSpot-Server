<?php
/**
 * File: neodb.php
 * Description: Handles neodb operations
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


include_once "strabo_db_neo4j.php";

$neodb = new StraboDbNeo4j($neousername,$neopassword,$neohost,$neoport,$neomode);

?>