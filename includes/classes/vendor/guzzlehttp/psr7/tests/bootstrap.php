<?php
/**
 * File: bootstrap.php
 * Description: HasToString class
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

namespace GuzzleHttp\Tests\Psr7;

require __DIR__ . '/../vendor/autoload.php';

class HasToString
{
    public function __toString() {
        return 'foo';
    }
}
