<?php
/**
 * File: MultiPoint.class.php
 * Description: MultiPoint class definition
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

/**
 * MultiPoint: A collection Points
 */
class MultiPoint extends Collection
{
  protected $geom_type = 'MultiPoint';

  public function numPoints() {
	return $this->numGeometries();
  }

  public function isSimple() {
	return TRUE;
  }

  // Not valid for this geometry type
  // --------------------------------
  public function explode() { return NULL; }
}

