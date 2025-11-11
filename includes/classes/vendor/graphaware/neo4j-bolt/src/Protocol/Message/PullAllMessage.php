<?php
/**
 * File: PullAllMessage.php
 * Description: PullAllMessage class definition
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

namespace GraphAware\Bolt\Protocol\Message;

use GraphAware\Bolt\Protocol\Constants;

class PullAllMessage extends AbstractMessage
{
    const MESSAGE_TYPE = 'PULL_ALL';

    public function __construct()
    {
        parent::__construct(Constants::SIGNATURE_PULL_ALL);
    }

    public function getMessageType()
    {
        return self::MESSAGE_TYPE;
    }
}