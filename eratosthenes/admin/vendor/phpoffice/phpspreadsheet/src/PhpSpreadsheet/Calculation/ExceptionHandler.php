<?php
/**
 * File: ExceptionHandler.php
 * Description: ExceptionHandler class
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


namespace PhpOffice\PhpSpreadsheet\Calculation;

class ExceptionHandler
{
    /**
     * Register errorhandler.
     */
    public function __construct()
    {
        set_error_handler([Exception::class, 'errorHandlerCallback'], E_ALL);
    }

    /**
     * Unregister errorhandler.
     */
    public function __destruct()
    {
        restore_error_handler();
    }
}
