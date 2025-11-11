<?php
/**
 * File: IComparable.php
 * Description: Handles IComparable operations
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


namespace PhpOffice\PhpSpreadsheet;

interface IComparable
{
    /**
     * Get hash code.
     *
     * @return string Hash code
     */
    public function getHashCode();
}
