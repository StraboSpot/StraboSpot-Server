<?php
/**
 * File: MultiLineString.class.php
 * Description: MultiLineString class definition
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

/**
 * MultiLineString: A collection of LineStrings
 */
class MultiLineString extends Collection
{
  protected $geom_type = 'MultiLineString';

  // MultiLineString is closed if all it's components are closed
  public function isClosed() {
	foreach ($this->components as $line) {
	  if (!$line->isClosed()) {
		return FALSE;
	  }
	}
	return TRUE;
  }

}

