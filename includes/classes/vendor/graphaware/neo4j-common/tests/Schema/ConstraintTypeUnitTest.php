<?php
/**
 * File: ConstraintTypeUnitTest.php
 * Description: ContraintTypeUnitTest class definition
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

namespace GraphAware\Common\Tests\Schema;

use GraphAware\Common\Schema\ConstraintType;

/**
 * @group unit
 * @group schema
 */
class ContraintTypeUnitTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $c = new ConstraintType(ConstraintType::UNIQUENESS);
        $this->assertEquals("UNIQUENESS", $c);

        $cn = new ConstraintType(ConstraintType::NODE_PROPERTY_EXISTENCE);
        $this->assertEquals("NODE_PROPERTY_EXISTENCE", $cn);

        $cr = new ConstraintType(ConstraintType::RELATIONSHIP_PROPERTY_EXISTENCE);
        $this->assertEquals("RELATIONSHIP_PROPERTY_EXISTENCE", $cr);

        $this->assertEquals($c, ConstraintType::UNIQUENESS());
        $this->assertEquals($cn, ConstraintType::NODE_PROPERTY_EXISTENCE());
        $this->assertEquals($cr, ConstraintType::RELATIONSHIP_PROPERTY_EXISTENCE());
    }
}