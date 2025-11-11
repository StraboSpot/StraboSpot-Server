<?php
/**
 * File: 20_Read_Excel2003XML.php
 * Description: Handles 20 Read Excel2003XML operations
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


use PhpOffice\PhpSpreadsheet\IOFactory;

require __DIR__ . '/../Header.php';

$filename = __DIR__ . '/../templates/Excel2003XMLTest.xml';
$callStartTime = microtime(true);
$spreadsheet = IOFactory::load($filename);
$helper->logRead('Xml', $filename, $callStartTime);

// Save
$helper->write($spreadsheet, __FILE__);
