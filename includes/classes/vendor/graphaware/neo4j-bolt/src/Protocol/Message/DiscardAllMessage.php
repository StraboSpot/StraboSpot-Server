<?php
/**
 * File: DiscardAllMessage.php
 * Description: DiscardAllMessage class definition
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

namespace GraphAware\Bolt\Protocol\Message;

use GraphAware\Bolt\PackStream\Structure\Map;
use GraphAware\Bolt\Protocol\Constants;

class DiscardAllMessage extends AbstractMessage
{
    const MESSAGE_TYPE = 'DISCARD_ALL';

    public function __construct()
    {
        parent::__construct(Constants::SIGNATURE_DISCARD_ALL);
    }

    public function getMessageType()
    {
        return self::MESSAGE_TYPE;
    }
}