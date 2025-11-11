<?php
/**
 * File: Bootstrap.php
 * Description: This sucks, but we have to try to find the composer autoloader
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

/**
 * Bootstrap for PhpSpreadsheet classes.
 */

// This sucks, but we have to try to find the composer autoloader

$paths = [
    __DIR__ . '/../vendor/autoload.php', // In case PhpSpreadsheet is cloned directly
    __DIR__ . '/../../../autoload.php', // In case PhpSpreadsheet is a composer dependency.
];

foreach ($paths as $path) {
    if (file_exists($path)) {
        require_once $path;

        return;
    }
}

throw new \Exception('Composer autoloader could not be found. Install dependencies with `composer install` and try again.');
