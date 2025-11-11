<?php
/**
 * File: 21_Pdf_mPDF.php
 * Description: PDF document generation handler
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

require __DIR__ . '/../Header.php';
$spreadsheet = require __DIR__ . '/../templates/sampleSpreadsheet.php';

$helper->log('Hide grid lines');
$spreadsheet->getActiveSheet()->setShowGridLines(false);

$helper->log('Set orientation to landscape');
$spreadsheet->getActiveSheet()->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);

$className = \PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf::class;
$helper->log("Write to PDF format using {$className}");
IOFactory::registerWriter('Pdf', $className);

// Save
$helper->write($spreadsheet, __FILE__, ['Pdf']);
