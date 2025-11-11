<?php
/**
 * File: svg2png.php
 * Description: Converts SVG geological symbols to PNG image format
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


//svg to png ... this is a service for geochron

$raw = file_get_contents("php://input");

$randnum = rand(111111,999999);

file_put_contents ("/dev/shm/$randnum.svg", $raw);

exec("/bin/rsvg-convert --background-color white /dev/shm/$randnum.svg > svgfiles/$randnum.png");

unlink("/dev/shm/$randnum.svg");

echo "http://www.strabospot.org/svgfiles/$randnum.png";

?>