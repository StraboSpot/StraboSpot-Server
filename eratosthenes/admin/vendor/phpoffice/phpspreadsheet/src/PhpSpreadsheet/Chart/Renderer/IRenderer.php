<?php
/**
 * File: IRenderer.php
 * Description: Handles IRenderer operations
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


namespace PhpOffice\PhpSpreadsheet\Chart\Renderer;

use PhpOffice\PhpSpreadsheet\Chart\Chart;

interface IRenderer
{
    /**
     * IRenderer constructor.
     *
     * @param \PhpOffice\PhpSpreadsheet\Chart\Chart $chart
     */
    public function __construct(Chart $chart);

    /**
     * Render the chart to given file (or stream).
     *
     * @param string $filename Name of the file render to
     *
     * @return bool true on success
     */
    public function render($filename);
}
