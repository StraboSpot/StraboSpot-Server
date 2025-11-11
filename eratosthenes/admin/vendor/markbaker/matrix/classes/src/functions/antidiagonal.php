<?php
/**
 * File: antidiagonal.php
 * Description: github.com/MarkBaker/PHPMatrix)
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

/**
 *
 * Function code for the matrix antidiagonal() function
 *
 * @copyright  Copyright (c) 2018 Mark Baker (https://github.com/MarkBaker/PHPMatrix)
 * @license    https://opensource.org/licenses/MIT    MIT
 */
namespace Matrix;

/**
 * Returns the antidiagonal of a matrix or an array.
 *
 * @param     Matrix|array     $matrix    Matrix or an array to treat as a matrix.
 * @return    Matrix           The new matrix
 * @throws    Exception        If argument isn't a valid matrix or array.
 */
function antidiagonal($matrix)
{
    if (!is_object($matrix) || !($matrix instanceof Matrix)) {
        $matrix = new Matrix($matrix);
    }

    return Functions::antidiagonal($matrix);
}
