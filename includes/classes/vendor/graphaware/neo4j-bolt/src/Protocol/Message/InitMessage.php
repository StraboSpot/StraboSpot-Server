<?php
/**
 * File: InitMessage.php
 * Description: InitMessage class definition
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

namespace GraphAware\Bolt\Protocol\Message;

use GraphAware\Bolt\Protocol\Constants;

class InitMessage extends AbstractMessage
{
    const MESSAGE_TYPE = 'INIT';

    public function __construct($userAgent, array $credentials)
    {
        $authToken = array();
        if (isset($credentials[1]) && null !== $credentials[1]) {
            $authToken = [
                'scheme' => 'basic',
                'principal' => $credentials[0],
                'credentials' => $credentials[1]
            ];
        }
        parent::__construct(Constants::SIGNATURE_INIT, array($userAgent, $authToken));
    }

    public function getMessageType()
    {
        return self::MESSAGE_TYPE;
    }
}