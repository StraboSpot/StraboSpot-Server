<?php
/**
 * File: EWKT.class.php
 * Description: EWKT class definition
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

/**
 * EWKT (Extended Well Known Text) Adapter
 */
class EWKT extends WKT
{

  /**
   * Serialize geometries into an EWKT string.
   *
   * @param Geometry $geometry
   *
   * @return string The Extended-WKT string representation of the input geometries
   */
  public function write(Geometry $geometry) {
	$srid = $geometry->SRID();
	$wkt = '';
	if ($srid) {
	  $wkt = 'SRID=' . $srid . ';';
	  $wkt .= $geometry->out('wkt');
	  return $wkt;
	}
	else {
	  return $geometry->out('wkt');
	}
  }
}
