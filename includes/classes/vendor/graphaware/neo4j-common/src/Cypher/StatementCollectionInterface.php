<?php
/**
 * File: StatementCollectionInterface.php
 * Description: graphaware.com>
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
namespace GraphAware\Common\Cypher;

interface StatementCollectionInterface
{
    /**
     * @return StatementInterface[]
     */
    public function getStatements();

    /**
     * @param StatementInterface $statement
     */
    public function add(StatementInterface $statement);

    /**
     * @return bool
     */
    public function isEmpty();

    /**
     * @return int
     */
    public function getCount();
}
