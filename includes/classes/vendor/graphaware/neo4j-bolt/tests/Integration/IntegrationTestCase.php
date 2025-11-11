<?php
/**
 * File: IntegrationTestCase.php
 * Description: IntegrationTestCase class
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


namespace GraphAware\Bolt\Tests\Integration;

use GraphAware\Bolt\Driver;
use GraphAware\Bolt\GraphDatabase;
use Neoxygen\NeoClient\ClientBuilder;

abstract class IntegrationTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \GraphAware\Bolt\Driver
     */
    protected $driver;

    public function setUp()
    {
        $this->driver = GraphDatabase::driver("bolt://localhost");
    }

    /**
     * @return \GraphAware\Bolt\Driver
     */
    public function getDriver()
    {
        return $this->driver;
    }

    /**
     * @return \Graphaware\Bolt\Protocol\SessionInterface
     */
    public function getSession()
    {
        return $this->driver->session();
    }

    /**
     * Empty the database
     */
    public function emptyDB()
    {
        $q = 'MATCH (n) DETACH DELETE n';
        $this->driver->session()->run($q);
    }
}