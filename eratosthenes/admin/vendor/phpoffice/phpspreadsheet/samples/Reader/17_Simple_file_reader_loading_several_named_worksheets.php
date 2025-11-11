<?php
/**
 * File: 17_Simple_file_reader_loading_several_named_worksheets.php
 * Description: Handles 17 Simple file reader loading several named worksheets operations
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

// Read the list of Worksheet Names from the Workbook file
$helper->log('Read the list of Worksheets in the WorkBook');
$worksheetNames = $reader->listWorksheetNames($inputFileName);

$helper->log('There are ' . count($worksheetNames) . ' worksheet' . ((count($worksheetNames) == 1) ? '' : 's') . ' in the workbook');
foreach ($worksheetNames as $worksheetName) {
    $helper->log($worksheetName);
}
