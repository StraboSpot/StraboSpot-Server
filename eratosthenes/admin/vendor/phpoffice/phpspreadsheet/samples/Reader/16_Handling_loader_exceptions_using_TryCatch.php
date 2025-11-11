<?php
/**
 * File: 16_Handling_loader_exceptions_using_TryCatch.php
 * Description: Handles 16 Handling loader exceptions using TryCatch operations
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


use PhpOffice\PhpSpreadsheet\IOFactory;

require __DIR__ . '/../Header.php';

$inputFileName = __DIR__ . '/sampleData/non-existing-file.xls';
$helper->log('Loading file ' . pathinfo($inputFileName, PATHINFO_BASENAME) . ' using IOFactory to identify the format');

try {
    $spreadsheet = IOFactory::load($inputFileName);
} catch (InvalidArgumentException $e) {
    $helper->log('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) . '": ' . $e->getMessage());
}
