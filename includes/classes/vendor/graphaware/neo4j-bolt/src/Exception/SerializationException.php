<?php
/**
 * File: SerializationException.php
 * Description: SerializationException class definition
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

namespace GraphAware\Bolt\Exception;

class SerializationException extends \InvalidArgumentException implements BoltExceptionInterface{}