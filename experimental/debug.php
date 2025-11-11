<?php
/**
 * File: debug.php
 * Description: Debugging utility and diagnostic tool
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


$files = print_r($_FILES, true);
$requests = print_r($_REQUEST, true);


if(file_exists("log.txt")){
	file_put_contents ("log.txt", "\n\n************************************************************************************************************************\n\n", FILE_APPEND);
	file_put_contents ("log.txt", "REQUESTS: ".$requests."\n\n", FILE_APPEND);
	file_put_contents ("log.txt", "FILES: ".$files."\n\n", FILE_APPEND);
}

echo "foo bar";

?>