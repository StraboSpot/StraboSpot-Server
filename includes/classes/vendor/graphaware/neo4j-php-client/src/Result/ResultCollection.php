<?php
/**
 * File: ResultCollection.php
 * Description: ResultCollection class definition
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

namespace GraphAware\Neo4j\Client\Result;

use GraphAware\Common\Result\RecordCursorInterface;
use GraphAware\Common\Result\ResultCollection as BaseResultCollection;

class ResultCollection extends BaseResultCollection
{
    protected $tag;

    public function setTag($tag)
    {
        $this->tag = $tag;
    }

    public function getTag()
    {
        return $this->tag;
    }

    public static function withResult(RecordCursorInterface $result)
    {
        $coll = new ResultCollection();
        $coll->add($result);

        return $coll;
    }
}