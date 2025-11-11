<?php
/**
 * File: 07_Reader.php
 * Description: Handles 07 Reader operations
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

require __DIR__ . '/../Header.php';

// Create temporary file that will be read
$sampleSpreadsheet = require __DIR__ . '/../templates/sampleSpreadsheet.php';
$filename = $helper->getTemporaryFilename();
$writer = new Xlsx($sampleSpreadsheet);
$writer->save($filename);

$callStartTime = microtime(true);
$spreadsheet = IOFactory::load($filename);
$helper->logRead('Xlsx', $filename, $callStartTime);

// Save
$helper->write($spreadsheet, __FILE__);
