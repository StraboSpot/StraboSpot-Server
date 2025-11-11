<?php
/**
 * File: 17_Html.php
 * Description: Handles 17 Html operations
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

$filename = $helper->getFilename(__FILE__, 'html');
$writer = IOFactory::createWriter($spreadsheet, 'Html');

$callStartTime = microtime(true);
$writer->save($filename);
$helper->logWrite($writer, $filename, $callStartTime);
