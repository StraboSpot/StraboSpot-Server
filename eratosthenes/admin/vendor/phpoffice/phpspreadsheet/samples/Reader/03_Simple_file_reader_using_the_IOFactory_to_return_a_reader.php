<?php
/**
 * File: 03_Simple_file_reader_using_the_IOFactory_to_return_a_reader.php
 * Description: Handles 03 Simple file reader using the IOFactory to return a reader operations
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


use PhpOffice\PhpSpreadsheet\IOFactory;

require __DIR__ . '/../Header.php';

$inputFileType = 'Xls';
$inputFileName = __DIR__ . '/sampleData/example1.xls';

$helper->log('Loading file ' . pathinfo($inputFileName, PATHINFO_BASENAME) . ' using IOFactory with a defined reader type of ' . $inputFileType);
$reader = IOFactory::createReader($inputFileType);
$spreadsheet = $reader->load($inputFileName);

$sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
var_dump($sheetData);
