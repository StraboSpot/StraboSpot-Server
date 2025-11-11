<?php
/**
 * File: TaskQueueTest.php
 * Description: TaskQueueTest class
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

namespace GuzzleHttp\Promise\Test;

use GuzzleHttp\Promise\TaskQueue;

class TaskQueueTest extends \PHPUnit_Framework_TestCase
{
    public function testKnowsIfEmpty()
    {
        $tq = new TaskQueue(false);
        $this->assertTrue($tq->isEmpty());
    }

    public function testKnowsIfFull()
    {
        $tq = new TaskQueue(false);
        $tq->add(function () {});
        $this->assertFalse($tq->isEmpty());
    }

    public function testExecutesTasksInOrder()
    {
        $tq = new TaskQueue(false);
        $called = [];
        $tq->add(function () use (&$called) { $called[] = 'a'; });
        $tq->add(function () use (&$called) { $called[] = 'b'; });
        $tq->add(function () use (&$called) { $called[] = 'c'; });
        $tq->run();
        $this->assertEquals(['a', 'b', 'c'], $called);
    }
}
