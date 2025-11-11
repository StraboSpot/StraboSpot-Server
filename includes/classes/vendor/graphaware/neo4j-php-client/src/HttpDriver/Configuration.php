<?php
/**
 * File: Configuration.php
 * Description: Configuration class
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


namespace GraphAware\Neo4j\Client\HttpDriver;

use GraphAware\Common\Driver\ConfigInterface;

class Configuration implements ConfigInterface
{
    /**
     * @var int
     */
    protected $timeout;

    /**
     * Configuration constructor.
     * @param int $timeout
     */
    public function __construct($timeout)
    {
        $this->timeout = (int) $timeout;
    }

    /**
     * @param int $timeout
     * @return \GraphAware\Neo4j\Client\HttpDriver\Configuration
     */
    public static function withTimeout($timeout)
    {
        return new self($timeout);
    }

    /**
     * @return int
     */
    public function getTimeout()
    {
        return $this->timeout;
    }


}