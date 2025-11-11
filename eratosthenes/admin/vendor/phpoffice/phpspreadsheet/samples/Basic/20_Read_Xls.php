<?php
/**
 * File: 20_Read_Xls.php
 * Description: Handles 20 Read Xls operations
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


use PhpOffice\PhpSpreadsheet\IOFactory;

require __DIR__ . '/../Header.php';

$spreadsheet = require __DIR__ . '/../templates/sampleSpreadsheet.php';

// Write temporary file
$filename = $helper->getTemporaryFilename('xls');
$writer = IOFactory::createWriter($spreadsheet, 'Xls');
$callStartTime = microtime(true);
$writer->save($filename);
$helper->logWrite($writer, $filename, $callStartTime);

// Read Xls file
$callStartTime = microtime(true);
$spreadsheet = IOFactory::load($filename);
$helper->logRead('Xls', $filename, $callStartTime);

// Save
$helper->write($spreadsheet, __FILE__);
