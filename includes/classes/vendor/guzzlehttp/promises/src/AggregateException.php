<?php
/**
 * File: AggregateException.php
 * Description: AggregateException class
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

namespace GuzzleHttp\Promise;

/**
 * Exception thrown when too many errors occur in the some() or any() methods.
 */
class AggregateException extends RejectionException
{
    public function __construct($msg, array $reasons)
    {
        parent::__construct(
            $reasons,
            sprintf('%s; %d rejected promises', $msg, count($reasons))
        );
    }
}
