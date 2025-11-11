<?php
/**
 * File: MultiPolygon.class.php
 * Description: MultiPolygon class definition
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

/**
 * MultiPolygon: A collection of Polygons
 */
class MultiPolygon extends Collection
{
  protected $geom_type = 'MultiPolygon';
}
