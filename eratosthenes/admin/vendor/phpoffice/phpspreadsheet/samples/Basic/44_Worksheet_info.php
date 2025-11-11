<?php
/**
 * File: 44_Worksheet_info.php
 * Description: Handles 44 Worksheet info operations
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

$inputFileType = IOFactory::identify($filename);
$reader = IOFactory::createReader($inputFileType);
$sheetList = $reader->listWorksheetNames($filename);
$sheetInfo = $reader->listWorksheetInfo($filename);

$helper->log('File Type:');
var_dump($inputFileType);

$helper->log('Worksheet Names:');
var_dump($sheetList);

$helper->log('Worksheet Names:');
var_dump($sheetInfo);
