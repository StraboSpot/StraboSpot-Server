<?php
/**
 * File: Thumbnails.php
 * Description: Thumbnails class
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


namespace PhpOffice\PhpSpreadsheet\Writer\Ods;

use PhpOffice\PhpSpreadsheet\Spreadsheet;

class Thumbnails extends WriterPart
{
    /**
     * Write Thumbnails/thumbnail.png to PNG format.
     *
     * @param Spreadsheet $spreadsheet
     *
     * @return string XML Output
     */
    public function writeThumbnail(Spreadsheet $spreadsheet = null)
    {
        return '';
    }
}
