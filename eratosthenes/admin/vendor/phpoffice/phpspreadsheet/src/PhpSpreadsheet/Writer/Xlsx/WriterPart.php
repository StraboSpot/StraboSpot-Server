<?php
/**
 * File: WriterPart.php
 * Description: WriterPart class
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


namespace PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

abstract class WriterPart
{
    /**
     * Parent Xlsx object.
     *
     * @var Xlsx
     */
    private $parentWriter;

    /**
     * Get parent Xlsx object.
     *
     * @return Xlsx
     */
    public function getParentWriter()
    {
        return $this->parentWriter;
    }

    /**
     * Set parent Xlsx object.
     *
     * @param Xlsx $pWriter
     */
    public function __construct(Xlsx $pWriter)
    {
        $this->parentWriter = $pWriter;
    }
}
