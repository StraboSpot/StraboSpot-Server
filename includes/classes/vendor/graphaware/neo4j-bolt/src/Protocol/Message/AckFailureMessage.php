<?php
/**
 * File: AckFailureMessage.php
 * Description: AckFailureMessage class definition
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

namespace GraphAware\Bolt\Protocol\Message;

use GraphAware\Bolt\Protocol\Constants;

class AckFailureMessage extends AbstractMessage
{
    const MESSAGE_TYPE = 'ACK_FAILURE';

    public function __construct()
    {
        parent::__construct(Constants::SIGNATURE_ACK_FAILURE);
    }

    public function getMessageType()
    {
        return self::MESSAGE_TYPE;
    }
}