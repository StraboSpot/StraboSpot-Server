<?php
/**
 * File: TCKTestCase.php
 * Description: TCKTestCase class
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


namespace GraphAware\Bolt\Tests\TCK;

use GraphAware\Bolt\Driver;
use GraphAware\Bolt\GraphDatabase;

class TCKTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \GraphAware\Bolt\Driver
     */
    private $driver;

    public function setUp()
    {
        $this->driver = GraphDatabase::driver("bolt://localhost");
    }

    /**
     * @return \GraphAware\Bolt\Driver
     */
    protected function getDriver()
    {
        return $this->driver;
    }
}