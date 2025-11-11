<?php
/**
 * File: 04_Simple_file_reader_using_the_IOFactory_to_identify_a_reader_to_use.php
 * Description: Handles 04 Simple file reader using the IOFactory to identify a reader to use operations
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


use PhpOffice\PhpSpreadsheet\IOFactory;

require __DIR__ . '/../Header.php';

$inputFileName = __DIR__ . '/sampleData/example1.xls';

$inputFileType = IOFactory::identify($inputFileName);
$helper->log('File ' . pathinfo($inputFileName, PATHINFO_BASENAME) . ' has been identified as an ' . $inputFileType . ' file');

$helper->log('Loading file ' . pathinfo($inputFileName, PATHINFO_BASENAME) . ' using IOFactory with the identified reader type');
$reader = IOFactory::createReader($inputFileType);
$spreadsheet = $reader->load($inputFileName);

$sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
var_dump($sheetData);
