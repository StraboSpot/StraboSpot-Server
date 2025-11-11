<?php
/**
 * File: asin.php
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
 * Function code for the complex asin() function
 *
 * @copyright  Copyright (c) 2013-2018 Mark Baker (https://github.com/MarkBaker/PHPComplex)
 * @license    https://opensource.org/licenses/MIT    MIT
 */
namespace Complex;

/**
 * Returns the inverse sine of a complex number.
 *
 * @param     Complex|mixed    $complex    Complex number or a numeric value.
 * @return    Complex          The inverse sine of the complex argument.
 * @throws    Exception        If argument isn't a valid real or complex number.
 */
function asin($complex)
{
    $complex = Complex::validateComplexArgument($complex);

    $square = multiply($complex, $complex);
    $invsqrt = new Complex(1.0);
    $invsqrt = subtract($invsqrt, $square);
    $invsqrt = sqrt($invsqrt);
    $adjust = new Complex(
        $invsqrt->getReal() - $complex->getImaginary(),
        $invsqrt->getImaginary() + $complex->getReal()
    );
    $log = ln($adjust);

    return new Complex(
        $log->getImaginary(),
        -1 * $log->getReal()
    );
}
