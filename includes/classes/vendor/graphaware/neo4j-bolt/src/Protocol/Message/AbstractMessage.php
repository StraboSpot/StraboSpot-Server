<?php
/**
 * File: AbstractMessage.php
 * Description: Handles AbstractMessage operations
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

namespace GraphAware\Bolt\Protocol\Message;

use GraphAware\Bolt\Protocol\Constants;

abstract class AbstractMessage implements MessageInterface
{
    protected $signature;

    protected $fields = [];

    protected $isSerialized = false;

    protected $serialization = null;

    public function __construct($signature, array $fields = array())
    {
        $this->signature = $signature;
        $this->fields = $fields;
    }

    public function getSignature()
    {
        return $this->signature;
    }

    public function getFields()
    {
        return $this->fields;
    }

    public function getFieldsLength()
    {
        return count($this->fields);
    }

    public function setSerialization($stream)
    {
        $this->serialization = $stream;
        $this->isSerialized = true;
    }

    public function getSerialization()
    {
        return $this->serialization;
    }

    public function isSuccess()
    {
        return $this->getMessageType() === 'SUCCESS';
    }

    public function isFailure()
    {
        return $this->getMessageType() === 'FAILURE';
    }

    public function isRecord()
    {
        return $this->getMessageType() === 'RECORD';
    }

    public function hasFields()
    {
        return !empty($this->fields);
    }
}