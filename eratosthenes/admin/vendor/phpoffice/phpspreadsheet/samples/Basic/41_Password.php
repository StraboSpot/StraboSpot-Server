<?php
/**
 * File: 41_Password.php
 * Description: Handles 41 Password operations
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


require __DIR__ . '/../Header.php';
$spreadsheet = require __DIR__ . '/../templates/sampleSpreadsheet.php';

// Set password against the spreadsheet file
$spreadsheet->getSecurity()->setLockWindows(true);
$spreadsheet->getSecurity()->setLockStructure(true);
$spreadsheet->getSecurity()->setWorkbookPassword('secret');

// Save
$helper->write($spreadsheet, __FILE__);
