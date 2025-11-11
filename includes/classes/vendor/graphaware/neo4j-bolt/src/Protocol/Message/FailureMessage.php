<?php
/**
 * File: FailureMessage.php
 * Description: FailureMessage class definition
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

namespace GraphAware\Bolt\Protocol\Message;

use GraphAware\Bolt\Protocol\Constants;
use GraphAware\Bolt\PackStream\Structure\Map;

class FailureMessage extends AbstractMessage
{
    const MESSAGE_TYPE = 'FAILURE';

    protected $code;

    protected $message;

    public function __construct(Map $map)
    {
        parent::__construct(Constants::SIGNATURE_FAILURE);
        $this->code = $map->get('code')->__toString();
        $this->message = $map->get('message')->__toString();
    }

    public function getMessageType()
    {
        return self::MESSAGE_TYPE;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function getMessage()
    {
        return $this->getMessage();
    }

    public function getFullMessage()
    {
        return $this->code . ' : ' . $this->message;
    }


}