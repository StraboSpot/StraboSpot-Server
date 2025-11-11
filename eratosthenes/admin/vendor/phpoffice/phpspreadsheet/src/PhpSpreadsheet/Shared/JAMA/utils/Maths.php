<?php
/**
 * File: Maths.php
 * Description: Handles Maths operations
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

/**
 *    Pythagorean Theorem:.
 *
 *    a = 3
 *    b = 4
 *    r = sqrt(square(a) + square(b))
 *    r = 5
 *
 *    r = sqrt(a^2 + b^2) without under/overflow.
 *
 * @param mixed $a
 * @param mixed $b
 *
 * @return float
 */
function hypo($a, $b)
{
    if (abs($a) > abs($b)) {
        $r = $b / $a;
        $r = abs($a) * sqrt(1 + $r * $r);
    } elseif ($b != 0) {
        $r = $a / $b;
        $r = abs($b) * sqrt(1 + $r * $r);
    } else {
        $r = 0.0;
    }

    return $r;
}
