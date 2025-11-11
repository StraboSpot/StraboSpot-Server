<?php
/**
 * File: Config.php
 * Description: Config class definition
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

/**
 * This file is part of the GraphAware Neo4j Client package.
 *
 * (c) GraphAware Limited <http://graphaware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GraphAware\Neo4j\Client;

class Config
{
    protected $defaultHttpPort = 7474;

    protected $defaultTcpPort = 8687;

    public static function create()
    {
        return new self();
    }

    public function withDefaultHttpPort($port)
    {
        $this->defaultHttpPort = (int) $port;

        return $this;
    }

    public function withDefaultTcpPort($port)
    {
        $this->defaultTcpPort = (int) $port;

        return $this;
    }
}