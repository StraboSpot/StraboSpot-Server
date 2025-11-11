<?php
/**
 * File: 46_ReadHtml.php
 * Description: Handles 46 ReadHtml operations
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


// Turn off error reporting
error_reporting(0);

use PhpOffice\PhpSpreadsheet\IOFactory;

require __DIR__ . '/../Header.php';

$html = __DIR__ . '/../templates/46readHtml.html';
$callStartTime = microtime(true);

$objReader = IOFactory::createReader('Html');
$objPHPExcel = $objReader->load($html);

$helper->logRead('Html', $html, $callStartTime);

// Save
$helper->write($objPHPExcel, __FILE__);
