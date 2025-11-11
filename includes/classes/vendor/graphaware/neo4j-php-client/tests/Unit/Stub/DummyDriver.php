<?php
/**
 * File: DummyDriver.php
 * Description: DummyDriver class
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


namespace GraphAware\Neo4j\Client\Tests\Unit\Stub;

use GraphAware\Common\Driver\DriverInterface;

class DummyDriver implements DriverInterface
{
    protected $uri;

    public function __construct($uri)
    {
        $this->uri = $uri;
    }

    /**
     * @return mixed
     */
    public function getUri()
    {
        return $this->uri;
    }

    public function session()
    {

    }

}