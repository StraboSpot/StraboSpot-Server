<?php
/**
 * File: 01-basic_usage.php
 * Description: Parse books.xslx
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

ini_set('error_reporting', E_ALL);
ini_set('display_errors', true);

require_once __DIR__.'/../src/SimpleXLSX.php';

echo '<h1>Parse books.xslx</h1><pre>';
if ( $xlsx = SimpleXLSX::parse('books.xlsx') ) {
	print_r( $xlsx->rows() );
} else {
	echo SimpleXLSX::parseError();
}
echo '<pre>';