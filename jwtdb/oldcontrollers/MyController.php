<?php
/**
 * File: MyController.php
 * Description: MyController class
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


class MyController
{
	 public function setstrabohandler($strabo){
		 $this->strabo=$strabo;
	 }

	 public function foobar($value){

		 echo "$value";exit();

	 }

}
