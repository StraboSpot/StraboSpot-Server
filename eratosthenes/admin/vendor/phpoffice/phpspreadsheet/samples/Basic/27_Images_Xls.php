<?php
/**
 * File: 27_Images_Xls.php
 * Description: Handles 27 Images Xls operations
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


use PhpOffice\PhpSpreadsheet\IOFactory;

require __DIR__ . '/../Header.php';

// Read from Xls (.xls) template
$helper->log('Load Xlsx template file');
$reader = IOFactory::createReader('Xls');
$spreadsheet = $reader->load(__DIR__ . '/../templates/27template.xls');

// Save
$helper->write($spreadsheet, __FILE__);
