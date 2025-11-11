<?php
/**
 * File: SessionInterface.php
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
namespace GraphAware\Common\Driver;

interface SessionInterface
{
    /**
     * @param string      $statement
     * @param array       $parameters
     * @param null|string $tag
     *
     * @return mixed
     */
    public function run($statement, array $parameters = [], $tag = null);

    public function close();
}
