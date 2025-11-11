<?php
/**
 * File: svg2jpg.php
 * Description: Converts SVG geological symbols to JPG image format
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


//svg to jpg ... this is a service for geochron

$raw = file_get_contents("php://input");

$randnum = rand(111111,999999);

file_put_contents ("/dev/shm/$randnum.svg", $raw);

exec("/bin/rsvg-convert --background-color white /dev/shm/$randnum.svg > svgfiles/$randnum.png");
exec("/bin/convert svgfiles/$randnum.png svgfiles/$randnum.jpg");

unlink("/dev/shm/$randnum.svg");

echo "http://www.strabospot.org/svgfiles/$randnum.jpg";

?>