<?php
/**
 * File: RejectionExceptionTest.php
 * Description: Thing1 class
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

namespace GuzzleHttp\Promise\Tests;

use GuzzleHttp\Promise\RejectionException;

class Thing1
{
    public function __construct($message)
    {
        $this->message = $message;
    }

    public function __toString()
    {
        return $this->message;
    }
}

class Thing2 implements \JsonSerializable
{
    public function jsonSerialize()
    {
        return '{}';
    }
}

/**
 * @covers GuzzleHttp\Promise\RejectionException
 */
class RejectionExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testCanGetReasonFromException()
    {
        $thing = new Thing1('foo');
        $e = new RejectionException($thing);

        $this->assertSame($thing, $e->getReason());
        $this->assertEquals('The promise was rejected with reason: foo', $e->getMessage());
    }

    public function testCanGetReasonMessageFromJson()
    {
        $reason = new Thing2();
        $e = new RejectionException($reason);
        $this->assertContains("{}", $e->getMessage());
    }
}
