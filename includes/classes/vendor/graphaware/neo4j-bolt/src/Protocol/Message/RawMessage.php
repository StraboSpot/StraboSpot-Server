<?php
/**
 * File: RawMessage.php
 * Description: RawMessage class definition
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

namespace GraphAware\Bolt\Protocol\Message;

class RawMessage
{
    protected $bytes = '';

    public function __construct($bytes)
    {
        $this->bytes = $bytes;
    }

    public function getLength()
    {
        return mb_strlen($this->bytes, 'ASCII');
    }

    public function getBytes()
    {
        return $this->bytes;
    }
}