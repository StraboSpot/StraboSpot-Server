<?php
/**
 * File: 06_Largescale.php
 * Description: Handles 06 Largescale operations
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


require __DIR__ . '/../Header.php';

$spreadsheet = require __DIR__ . '/../templates/largeSpreadsheet.php';

// Save
$helper->write($spreadsheet, __FILE__);
