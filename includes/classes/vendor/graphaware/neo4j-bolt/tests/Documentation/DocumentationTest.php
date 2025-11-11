<?php
/**
 * File: DocumentationTest.php
 * Description: DocumentationTest class definition
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

namespace GraphAware\Bolt\Tests\Documentation;

use GraphAware\Bolt\GraphDatabase;
use GraphAware\Common\Driver\DriverInterface;

/**
 * Class DocumentationTest
 *
 * @group documentation
 */
class DocumentationTest extends \PHPUnit_Framework_TestCase
{
    public function testSetup()
    {
        /**
         * Creating a driver
         */

        $driver = GraphDatabase::driver("bolt://localhost");
        $this->assertInstanceOf(DriverInterface::class, $driver);
    }
}