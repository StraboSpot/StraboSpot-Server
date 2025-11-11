<?php
/**
 * File: functions_include.php
 * Description: Handles functions include operations
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


// Don't redefine the functions if included multiple times.
if (!function_exists('GuzzleHttp\Psr7\str')) {
    require __DIR__ . '/functions.php';
}
