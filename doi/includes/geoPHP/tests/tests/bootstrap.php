<?php
/**
 * File: bootstrap.php
 * Description: Handles bootstrap operations
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

require_once ('../geoPHP.inc');
if (!@include __DIR__ . '/../../vendor/autoload.php') {
	die('You must set up the project dependencies, run the following commands:
		wget http://getcomposer.org/composer.phar
		php composer.phar install');
}