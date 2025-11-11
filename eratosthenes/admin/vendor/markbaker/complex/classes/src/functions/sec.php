<?php
/**
 * File: sec.php
 * Description: github.com/MarkBaker/PHPComplex)
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

/**
 *
 * Function code for the complex sec() function
 *
 * @copyright  Copyright (c) 2013-2018 Mark Baker (https://github.com/MarkBaker/PHPComplex)
 * @license    https://opensource.org/licenses/MIT    MIT
 */
namespace Complex;

/**
 * Returns the secant of a complex number.
 *
 * @param     Complex|mixed    $complex    Complex number or a numeric value.
 * @return    Complex          The secant of the complex argument.
 * @throws    Exception        If argument isn't a valid real or complex number.
 * @throws    \InvalidArgumentException    If function would result in a division by zero
 */
function sec($complex)
{
    $complex = Complex::validateComplexArgument($complex);

    return inverse(cos($complex));
}
