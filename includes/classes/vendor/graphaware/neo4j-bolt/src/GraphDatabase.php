<?php
/**
 * File: GraphDatabase.php
 * Description: GraphDatabase class definition
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

namespace GraphAware\Bolt;

use GraphAware\Common\Driver\ConfigInterface;
use GraphAware\Common\GraphDatabaseInterface;

class GraphDatabase implements GraphDatabaseInterface
{
    public static function driver($uri, ConfigInterface $config = null)
    {
        return new Driver(self::formatUri($uri), $config);
    }

    private static function formatUri($uri)
    {
        $i = str_replace("bolt://", "", $uri);

        return $i;
    }
}