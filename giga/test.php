<?php
/**
 * File: test.php
 * Description: Testing and development utility file
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

exit();

echo "<pre>"; print_r($_SERVER); echo "</pre>";

// ********************** Server-Side Google Analytics **********************
require_once("GoogleAnalytics.php");
$script_name = $_SERVER['REQUEST_URI'];
$analytics = new GoogleAnalytics("UA-161637893-1", "GigaBrowser", true);
$analytics->Track("Hit to $script_name");
// **************************************************************************

?>
