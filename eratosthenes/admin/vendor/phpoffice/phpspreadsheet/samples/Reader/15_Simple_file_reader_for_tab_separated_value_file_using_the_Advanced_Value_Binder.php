<?php
/**
 * File: 15_Simple_file_reader_for_tab_separated_value_file_using_the_Advanced_Value_Binder.php
 * Description: Handles 15 Simple file reader for tab separated value file using the Advanced Value Binder operations
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


use PhpOffice\PhpSpreadsheet\Cell\AdvancedValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\IOFactory;

require __DIR__ . '/../Header.php';

Cell::setValueBinder(new AdvancedValueBinder());

$inputFileType = 'Csv';
$inputFileName = __DIR__ . '/sampleData/example1.tsv';

$reader = IOFactory::createReader($inputFileType);
$helper->log('Loading file ' . pathinfo($inputFileName, PATHINFO_BASENAME) . ' into WorkSheet #1 using IOFactory with a defined reader type of ' . $inputFileType);
$reader->setDelimiter("\t");
$spreadsheet = $reader->load($inputFileName);
$spreadsheet->getActiveSheet()->setTitle(pathinfo($inputFileName, PATHINFO_BASENAME));

$helper->log($spreadsheet->getSheetCount() . ' worksheet' . (($spreadsheet->getSheetCount() == 1) ? '' : 's') . ' loaded');
$loadedSheetNames = $spreadsheet->getSheetNames();
foreach ($loadedSheetNames as $sheetIndex => $loadedSheetName) {
    $helper->log('<b>Worksheet #' . $sheetIndex . ' -> ' . $loadedSheetName . ' (Formatted)</b>');
    $spreadsheet->setActiveSheetIndexByName($loadedSheetName);
    $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
    var_dump($sheetData);
}

foreach ($loadedSheetNames as $sheetIndex => $loadedSheetName) {
    $helper->log('<b>Worksheet #' . $sheetIndex . ' -> ' . $loadedSheetName . ' (Unformatted)</b>');
    $spreadsheet->setActiveSheetIndexByName($loadedSheetName);
    $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, false, true);
    var_dump($sheetData);
}

foreach ($loadedSheetNames as $sheetIndex => $loadedSheetName) {
    $helper->log('<b>Worksheet #' . $sheetIndex . ' -> ' . $loadedSheetName . ' (Raw)</b>');
    $spreadsheet->setActiveSheetIndexByName($loadedSheetName);
    $sheetData = $spreadsheet->getActiveSheet()->toArray(null, false, false, true);
    var_dump($sheetData);
}
