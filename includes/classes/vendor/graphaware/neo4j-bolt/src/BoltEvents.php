<?php
/**
 * File: BoltEvents.php
 * Description: Display page
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

namespace GraphAware\Bolt;

final class BoltEvents
{
    /**
     * This event is dispatched before the handshake is performed with the server.
     * Listeners will receive a <code>PreHandshakeEvent</code> instance containing
     * the versions supported by BoltPHP
     */
    const PRE_HANDSHAKE = 'bolt.pre_handshake';

    /**
     * This event is dispatched after the handshake is performed with the server.
     * Listeners will receive a <code>PostHandshakeEvent</code> instance containing
     * the version defined by the server
     */
    const POST_HANDSHAKE = 'bolt.post_handshake';

    /**
     * This event is dispatched when an exception is raised.
     * Listeners will received a <code>ExeptionEvent</code> instance
     */
    const EXCEPTION = 'bolt.exception';
}