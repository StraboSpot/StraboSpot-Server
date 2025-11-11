<?php
/**
 * File: Protocol.php
 * Description: Protocol class definition
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

/**
 * This file is part of the GraphAware Neo4j Common package.
 *
 * (c) GraphAware Limited <http://graphaware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace GraphAware\Common\Driver;

use MyCLabs\Enum\Enum;

class Protocol extends Enum
{
    const HTTP = 'HTTP';

    const HTTPS = 'HTTPS';

    const TCP = 'TCP';

    const TLS = 'TLS';

    const WS = 'WS';

    const WSS = 'WSS';
}
