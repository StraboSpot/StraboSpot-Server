<?php
/**
 * File: MessageUnitTest.php
 * Description: MessageUnitTest class
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


namespace GraphAware\Bolt\Tests\Protocol\Message;

use GraphAware\Bolt\PackStream\Structure\Map;
use GraphAware\Bolt\Protocol\Message\RawMessage;
use GraphAware\Bolt\Protocol\Message\SuccessMessage;
use GraphAware\Bolt\Protocol\Message\AbstractMessage;

/**
 * Class MessageUnitTest
 * @package GraphAware\Bolt\Tests\Protocol\Message
 *
 * @group message
 * @group unit
 */
class MessageUnitTest extends \PHPUnit_Framework_TestCase
{
    public function testSuccessMessageWithoutFields()
    {
        $message = new SuccessMessage(array());
        $this->assertInstanceOf(AbstractMessage::class, $message);
    }
}