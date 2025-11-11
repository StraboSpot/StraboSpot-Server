<?php
/**
 * File: geohashTest.php
 * Description: GeoHashTest class
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

require_once ('../geoPHP.inc');

class GeoHashTest extends PHPUnit_Framework_TestCase {

  function setUp() {
  }

  /**
   * test cases for adjacent geohashes.
   */
  function testAdjacent() {
	$geohash = new Geohash();
	$this->assertEquals ( 'xne', $geohash->adjacent ( 'xn7', 'top' ), 'Did not find correct top adjacent geohash for xn7' );
	$this->assertEquals ( 'xnk', $geohash->adjacent ( 'xn7', 'right' ), 'Did not find correct right adjacent geohash for xn7' );
	$this->assertEquals ( 'xn5', $geohash->adjacent ( 'xn7', 'bottom' ), 'Did not find correct bottom adjacent geohash for xn7' );
	$this->assertEquals ( 'xn6', $geohash->adjacent ( 'xn7', 'left' ), 'Did not find correct left adjacent geohash for xn7' );
	$this->assertEquals ( 'xnd', $geohash->adjacent ( $geohash->adjacent ( 'xn7', 'left' ), 'top' ), 'Did not find correct top-left adjacent geohash for xn7' );
  }
}
