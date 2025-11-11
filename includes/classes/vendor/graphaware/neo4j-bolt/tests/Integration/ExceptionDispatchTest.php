<?php
/**
 * File: ExceptionDispatchTest.php
 * Description: ExceptionDispatchTest class
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


namespace GraphAware\Bolt\Tests\Integration;

use GraphAware\Bolt\GraphDatabase;
use GraphAware\Bolt\Exception\MessageFailureException;

/**
 * Class ExceptionDispatchTest
 * @package GraphAware\Bolt\Tests\Integration
 *
 * @group integration
 * @group exception-dispatch
 */
class ExceptionDispatchTest extends \PHPUnit_Framework_TestCase
{
    public function testExceptionsAreThrown()
    {
        $driver = GraphDatabase::driver('bolt://localhost');
        $session = $driver->session();

        $this->setExpectedException(MessageFailureException::class);
        $session->run("CREATE (n:)");

        try {
            $session->run("CR");
        } catch (MessageFailureException $e) {
            $this->assertEquals('Neo.ClientError.Statement.SyntaxError', $e->getStatusCode());
        }
    }

    public function testNeo4jStatusCodeIsAvalailble()
    {
        $driver = GraphDatabase::driver('bolt://localhost');
        $session = $driver->session();

        try {
            $session->run("CR");
        } catch (MessageFailureException $e) {
            $this->assertEquals('Neo.ClientError.Statement.SyntaxError', $e->getStatusCode());
        }
    }
}