<?php
/**
 * File: MessageFailureException.php
 * Description: MessageFailureException class definition
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

namespace GraphAware\Bolt\Exception;

class MessageFailureException extends \RuntimeException implements BoltExceptionInterface
{
    protected $statusCode;

    public function setStatusCode($code)
    {
        $this->statusCode = $code;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }
}