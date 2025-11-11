<?php
/**
 * File: testOperations.php
 * Description: Handles testOperations operations
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


use Complex\Complex as Complex;

include('../classes/Bootstrap.php');

$values = [
    new Complex(123),
    new Complex(456, 123),
    new Complex(0.0, 456),
];

foreach ($values as $value) {
    echo $value, PHP_EOL;
}

echo 'Addition', PHP_EOL;

$result = \Complex\add(...$values);
echo '=> ', $result, PHP_EOL;

echo PHP_EOL;

echo 'Subtraction', PHP_EOL;

$result = \Complex\subtract(...$values);
echo '=> ', $result, PHP_EOL;

echo PHP_EOL;

echo 'Multiplication', PHP_EOL;

$result = \Complex\multiply(...$values);
echo '=> ', $result, PHP_EOL;
