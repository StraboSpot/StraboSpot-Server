<?php
/**
 * File: Helper.php
 * Description: Helper class providing  utilities
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

namespace GraphAware\Bolt\Misc;

class Helper
{
    public static function prettyHex($raw)
    {
        $split = str_split(bin2hex($raw), 2);

        return implode(':', $split);
    }
}