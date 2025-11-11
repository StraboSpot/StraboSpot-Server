<?php
/**
 * File: HeaderFooterDrawing.php
 * Description: HeaderFooterDrawing class
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


namespace PhpOffice\PhpSpreadsheet\Worksheet;

class HeaderFooterDrawing extends Drawing
{
    /**
     * Get hash code.
     *
     * @return string Hash code
     */
    public function getHashCode()
    {
        return md5(
            $this->getPath() .
            $this->name .
            $this->offsetX .
            $this->offsetY .
            $this->width .
            $this->height .
            __CLASS__
        );
    }
}
