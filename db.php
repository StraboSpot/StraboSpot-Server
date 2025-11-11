<?php
/**
 * File: db.php
 * Description: Handles db operations
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

include_once "strabo_db_core.php";
include_once "strabo_db_postgresql.php";
$db = new StraboDbPostgreSQL($dbusername,$dbpassword,$dbname,$dbhost);
?>
