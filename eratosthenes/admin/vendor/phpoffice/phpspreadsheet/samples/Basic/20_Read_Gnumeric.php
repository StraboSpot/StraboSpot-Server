<?php
/**
 * File: 20_Read_Gnumeric.php
 * Description: Handles 20 Read Gnumeric operations
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


use PhpOffice\PhpSpreadsheet\IOFactory;

require __DIR__ . '/../Header.php';

$filename = __DIR__ . '/../templates/GnumericTest.gnumeric';
$callStartTime = microtime(true);
$spreadsheet = IOFactory::load($filename);
$helper->logRead('Gnumeric', $filename, $callStartTime);

// Save
$helper->write($spreadsheet, __FILE__);
