<?php
/**
 * File: 05_Feature_demo.php
 * Description: Handles 05 Feature demo operations
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


require __DIR__ . '/../Header.php';
$spreadsheet = require __DIR__ . '/../templates/sampleSpreadsheet.php';

// Save
$helper->write($spreadsheet, __FILE__);
