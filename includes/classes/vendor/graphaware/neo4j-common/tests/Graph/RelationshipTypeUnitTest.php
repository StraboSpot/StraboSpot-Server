<?php
/**
 * File: RelationshipTypeUnitTest.php
 * Description: RelationshipTypeUnitTest class definition
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

/**
 * This file is part of the GraphAware Neo4j Common package.
 *
 * (c) GraphAware Limited <http://graphaware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GraphAware\Common\Tests\Graph;

use GraphAware\Common\Graph\RelationshipType;

/**
 * @group unit
 * @group graph
 */
class RelationshipTypeUnitTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $type = RelationshipType::withName("FOLLOWS");
        $this->assertEquals("FOLLOWS", $type->getName());
        $this->assertEquals("FOLLOWS", (string) $type);
    }
}