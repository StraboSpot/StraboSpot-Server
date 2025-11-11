<?php
/**
 * File: rho.php
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
 * Function code for the complex rho() function
 *
 * @copyright  Copyright (c) 2013-2018 Mark Baker (https://github.com/MarkBaker/PHPComplex)
 * @license    https://opensource.org/licenses/MIT    MIT
 */
namespace Complex;

/**
 * Returns the rho of a complex number.
 * This is the distance/radius from the centrepoint to the representation of the number in polar coordinates.
 *
 * @param     Complex|mixed    $complex    Complex number or a numeric value.
 * @return    float            The rho value of the complex argument.
 * @throws    Exception        If argument isn't a valid real or complex number.
 */
function rho($complex)
{
    $complex = Complex::validateComplexArgument($complex);

    return \sqrt(
        ($complex->getReal() * $complex->getReal()) +
        ($complex->getImaginary() * $complex->getImaginary())
    );
}
