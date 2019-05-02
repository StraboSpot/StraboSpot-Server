<?php

/*
******************************************************************
StraboSpot REST API
My Controller
Author: Jason Ash (jasonash@ku.edu)
Description: This is the base controller for the StraboSpot API.
				All other controllers stem from this class.
******************************************************************
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
