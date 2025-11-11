<?php
/**
 * File: RecordMessage.php
 * Description: RecordMessage class definition
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

namespace GraphAware\Bolt\Protocol\Message;

use GraphAware\Bolt\PackStream\Structure\ListCollection;
use GraphAware\Bolt\Protocol\Constants;
use GraphAware\Common\Result\RecordInterface;
use GraphAware\Common\Result\RecordViewInterface;

class RecordMessage extends AbstractMessage implements RecordViewInterface
{
    const MESSAGE_TYPE = 'RECORD';

    protected $values;

    public function __construct($list)
    {
        parent::__construct(Constants::SIGNATURE_RECORD);
        $this->values = $list;
    }

    public function getMessageType()
    {
        return self::MESSAGE_TYPE;
    }

    public function getValues()
    {
        return $this->values;
    }

    public function keys()
    {
        // TODO: Implement keys() method.
    }

    public function hasValues()
    {
        // TODO: Implement hasValues() method.
    }

    public function value($key)
    {
        // TODO: Implement value() method.
    }

    public function values()
    {
        // TODO: Implement values() method.
    }

    public function valueByIndex($index)
    {
        // TODO: Implement valueByIndex() method.
    }

    public function record()
    {
        // TODO: Implement record() method.
    }
}