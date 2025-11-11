<?php
/**
 * File: EventTest.php
 * Description: EventTest class definition
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

namespace Symfony\Component\EventDispatcher\Tests;

use Symfony\Component\EventDispatcher\Event;

/**
 * Test class for Event.
 */
class EventTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Symfony\Component\EventDispatcher\Event
     */
    protected $event;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->event = new Event();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        $this->event = null;
    }

    public function testIsPropagationStopped()
    {
        $this->assertFalse($this->event->isPropagationStopped());
    }

    public function testStopPropagationAndIsPropagationStopped()
    {
        $this->event->stopPropagation();
        $this->assertTrue($this->event->isPropagationStopped());
    }
}
