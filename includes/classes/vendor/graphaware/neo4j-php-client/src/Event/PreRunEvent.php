<?php
/**
 * File: PreRunEvent.php
 * Description: PreRunEvent class definition
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

/**
 * This file is part of the GraphAware Neo4j Client package.
 *
 * (c) GraphAware Limited <http://graphaware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GraphAware\Neo4j\Client\Event;

use Symfony\Component\EventDispatcher\Event;

class PreRunEvent extends Event
{
    /**
     * @var \GraphAware\Common\Cypher\StatementInterface[]
     */
    private $statements;

    public function __construct(array $statements)
    {
        $this->statements = $statements;
    }

    /**
     * @return \GraphAware\Common\Cypher\StatementInterface[]
     */
    public function getStatements()
    {
        return $this->statements;
    }

}