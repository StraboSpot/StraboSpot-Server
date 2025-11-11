<?php
/**
 * File: DefaultReadFilter.php
 * Description: DefaultReadFilter class
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


namespace PhpOffice\PhpSpreadsheet\Reader;

class DefaultReadFilter implements IReadFilter
{
    /**
     * Should this cell be read?
     *
     * @param string $column Column address (as a string value like "A", or "IV")
     * @param int $row Row number
     * @param string $worksheetName Optional worksheet name
     *
     * @return bool
     */
    public function readCell($column, $row, $worksheetName = '')
    {
        return true;
    }
}
