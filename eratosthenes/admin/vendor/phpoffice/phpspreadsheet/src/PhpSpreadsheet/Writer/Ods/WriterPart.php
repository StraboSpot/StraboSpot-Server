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


namespace PhpOffice\PhpSpreadsheet\Writer\Ods;

use PhpOffice\PhpSpreadsheet\Writer\Ods;

abstract class WriterPart
{
    /**
     * Parent Ods object.
     *
     * @var Ods
     */
    private $parentWriter;

    /**
     * Get Ods writer.
     *
     * @return Ods
     */
    public function getParentWriter()
    {
        return $this->parentWriter;
    }

    /**
     * Set parent Ods writer.
     *
     * @param Ods $writer
     */
    public function __construct(Ods $writer)
    {
        $this->parentWriter = $writer;
    }
}
