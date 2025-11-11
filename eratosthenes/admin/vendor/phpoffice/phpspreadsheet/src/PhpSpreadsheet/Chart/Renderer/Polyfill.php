<?php
/**
 * File: Polyfill.php
 * Description: Handles Polyfill operations
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


// This is a dirty workaround to output JpGraph charts even when antialiasing is not available
if (!function_exists('imageantialias')) {
    function imageantialias(...$args)
    {
        // Do nothing
    }
}
