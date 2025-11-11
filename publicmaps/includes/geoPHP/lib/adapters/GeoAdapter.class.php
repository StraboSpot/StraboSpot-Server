<?php
/**
 * File: GeoAdapter.class.php
 * Description: Handles GeoAdapter.class operations
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

/**
 * GeoAdapter : abstract class which represents an adapter
 * for reading and writing to and from Geomtry objects
 *
 */
abstract class GeoAdapter
{
  /**
   * Read input and return a Geomtry or GeometryCollection
   *
   * @return Geometry|GeometryCollection
   */
  abstract public function read($input);

  /**
   * Write out a Geomtry or GeometryCollection in the adapter's format
   *
   * @return mixed
   */
  abstract public function write(Geometry $geometry);

}
