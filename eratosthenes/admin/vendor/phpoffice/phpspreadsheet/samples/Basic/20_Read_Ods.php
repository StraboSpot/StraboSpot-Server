<?php
/**
 * File: 20_Read_Ods.php
 * Description: Handles 20 Read Ods operations
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


use PhpOffice\PhpSpreadsheet\IOFactory;

require __DIR__ . '/../Header.php';

$filename = __DIR__ . '/../templates/OOCalcTest.ods';
$callStartTime = microtime(true);
$spreadsheet = IOFactory::load($filename);
$helper->logRead('Ods', $filename, $callStartTime);

// Save
$helper->write($spreadsheet, __FILE__);
