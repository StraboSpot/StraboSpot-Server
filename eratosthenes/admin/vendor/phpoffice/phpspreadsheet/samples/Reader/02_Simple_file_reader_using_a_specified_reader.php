<?php
/**
 * File: 02_Simple_file_reader_using_a_specified_reader.php
 * Description: Handles 02 Simple file reader using a specified reader operations
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


use PhpOffice\PhpSpreadsheet\Reader\Xls;

require __DIR__ . '/../Header.php';
$inputFileName = __DIR__ . '/sampleData/example1.xls';

$helper->log('Loading file ' . pathinfo($inputFileName, PATHINFO_BASENAME) . ' using ' . Xls::class);
$reader = new Xls();
$spreadsheet = $reader->load($inputFileName);

$sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
var_dump($sheetData);
