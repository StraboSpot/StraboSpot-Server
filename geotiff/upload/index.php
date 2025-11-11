<?php
/**
 * File: index.php
 * Description: Main page or directory index
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


ini_set('max_execution_time', '600');
ini_set('max_input_time', '600');
set_time_limit(600);

session_start();

include("../../includes/config.inc.php");
include("../../db.php");

$userpkey = $_SESSION['userpkey'];

function getRandVal(){
	$chars = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z","a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z","0","1","2","3","4","5","6","7","8","9");
	$randstring="";
	for($x=0;$x<26;$x++){
		$randstring.=$chars[rand(0,51)];
	}
	return(uniqid());
}

function dumpVar($var){
	echo "<pre>";
	print_r($var);
	echo "</pre>";
}

function doLog($text){
	if(file_exists("/srv/app/www/log.txt")){
		file_put_contents ("/srv/app/www/log.txt", "Text: ".$text, FILE_APPEND);
		file_put_contents ("/srv/app/www/log.txt", "\n\n*************************************************\n\n", FILE_APPEND);
	}else{
		echo "doesnt exist";
	}
}

function logDumpVar($var){
	$text = print_r($var, true);
	if(file_exists("/srv/app/www/log.txt")){
		file_put_contents ("/srv/app/www/log.txt", $text, FILE_APPEND);
		file_put_contents ("/srv/app/www/log.txt", "\n\n*************************************************\n\n", FILE_APPEND);
	}
}

$filename=$_FILES['geotifffile']['name'];
$filename = str_replace ("'","",$filename);
$filetype=$_FILES['geotifffile']['type'];
$tempname=$_FILES['geotifffile']['tmp_name'];
$fileerror=$_FILES['geotifffile']['error'];
$filesize=$_FILES['geotifffile']['size'];
if($filesize==""){
	$filesize="null";
}

/*
Text: filename: USGS_MF-2340.zip
Text: filetype: application/zip
Text: tempname: /tmp/phpE3CsNq
Text: fileerror: 0
Text: filesize: 17256654
*/

if($userpkey!=""){

	if($fileerror==0){

		if($filetype=="image/tiff" || $filetype=="application/zip" || $filetype=="application/x-zip-compressed"){

			//if file type is zip, unzip and gdal_translate... also set tempname
			if($filetype=="application/zip" || $filetype=="application/x-zip-compressed"){
				$randnum = $db->get_var("select nextval('file_seq')");
				$filename = str_replace(" ","_",$filename);

				$mydir = "/srv/app/www/ziptemp/$randnum";
				mkdir($mydir);

				$newfilename = $mydir."/".$filename;

				copy($tempname, "$newfilename");

				$linuxfilename=escapeshellcmd($newfilename);

				//unzip file
				exec("/usr/bin/unzip $linuxfilename -d /srv/app/www/ziptemp/$randnum/");
				exec("/bin/chmod -R 777 /srv/app/www/ziptemp/$randnum/");

				//delete macosx stuff
				exec("/usr/bin/find /srv/app/www/ziptemp/$randnum/ -name '__MACOSX' -exec rm -rf {} \;",$foo);

				//rename all files here, replacing spaces
				$files = scandir("/srv/app/www/ziptemp/$randnum/");
				foreach($files as $file){
					if(is_file("/srv/app/www/ziptemp/$randnum/$file")){
						$extension = pathinfo("/srv/app/www/ziptemp/$randnum/$file",  PATHINFO_EXTENSION);
						if($extension != "zip"){
							if (strpos($file, ' ') !== false) {
								$newfilename = str_replace(" ", "_", $file);
								rename("/srv/app/www/ziptemp/$randnum/$file", "/srv/app/www/ziptemp/$randnum/$newfilename");
							}
						}
					}
				}

				//find tif file and gdal_translate

				$foundtiff = "";

				$files = scandir("/srv/app/www/ziptemp/$randnum/");

				foreach($files as $file){
					$mimetype = mime_content_type("/srv/app/www/ziptemp/$randnum/$file");

					if($mimetype == "image/tiff"){
						$foundtiff = "/srv/app/www/ziptemp/$randnum/$file";
					}
				}

				if($foundtiff != ""){
					//translate here /usr/bin/gdal_translate
					exec("gdal_translate -of Gtiff $foundtiff /srv/app/www/ziptemp/$randnum/out.tif",$foo);

					$tempname = "/srv/app/www/ziptemp/$randnum/out.tif";
				}else{
					$out['error'] = "Invalid .zip file. No tif found in archive.";
				}
			}

			if($out['error'] == ""){

				

				doLog("tempname: $tempname"); //good to here

				//set tempname above if zip upload to somewhere in /srv/app/www/ziptemp
				exec("/usr/bin/gdalinfo -stats -json $tempname",$gdalinfo);
				$gdalinfo = implode("\n", $gdalinfo);
				$gdalinfo = json_decode($gdalinfo);

				$fileWKT = $gdalinfo->gcps->coordinateSystem->wkt;

				if($fileWKT == ""){
					$fileWKT = $gdalinfo->coordinateSystem->wkt;
				}

				if($fileWKT=="" || is_null($fileWKT)){
					//error
					$out['error']="GeoTIFF file not referenced correctly.";
				}else{

					$hash = getRandVal();

					//Move file

					exec("mv $tempname /srv/app/www/geotiff/upload/files/".$hash.".tif");

					doLog("tempname: $tempname "."files/".$hash.".tif");

/*
ZIP:

Text: tempname: /srv/app/www/ziptemp/23095/out.tif

*************************************************

Text: tempname: /srv/app/www/ziptemp/23095/out.tif files/6877d5de51b67.tif

*************************************************

GeoTIFF:
Text: tempname: /tmp/php4oAndL

*************************************************

Text: tempname: /tmp/php4oAndL files/6877d6e6bb26d.tif

*************************************************

*/

					$bands = $gdalinfo->bands;
					if($bands[0]->type != "Byte"){

						//Convert to Byte

						$band = $bands[0];
						$min = $band->minimum;
						$max = $band->maximum;

						$newtemp = getRandVal();
						//gdal_translate -of GTiff -scale 0 65535 -ot Byte stormmtn.tif byte.tif
						exec("gdal_translate -of GTiff -scale $min $max -ot Byte files/".$hash.".tif files/".$newtemp.".tif");
						$hash = $newtemp;

					}

					//move file to folder

					//Do usual gdalwarp
					$newtemp = getRandVal();
					exec("gdalwarp files/".$hash.".tif files/".$newtemp.".tif -co \"PHOTOMETRIC=RGB\" -t_srs \"+proj=longlat +ellps=WGS84\"");
					$hash = $newtemp;

					$out['hash']=$hash;

					exec("/usr/bin/gdalinfo files/".$hash.".tif",$gdalinfo);
					$gdalinfo = implode("\n",$gdalinfo);
					$gdalinfo = pg_escape_string($gdalinfo);

	$mapfile='# Geotiff file ('.$hash.').

	MAP
	  IMAGETYPE      PNG
	  EXTENT         -180 -90 180 90
	  SIZE           400 300
	  IMAGECOLOR 255 255 255

	  OUTPUTFORMAT
		NAME "png"
		MIMETYPE "image/png"
		DRIVER AGG/PNG8
		IMAGEMODE RGB
		TRANSPARENT TRUE
	  END

	  PROJECTION
		 "init=epsg:4326"
	  END

	  LAYER
		NAME "geotifflayer"
		DATA "/var/www/geotiff/upload/files/'.$hash.'.tif"
		TYPE RASTER
		STATUS ON
	  END

	END';

					file_put_contents("/var/www/geotiff/upload/maps/$hash".".map",$mapfile);

					//add to database
					$db->query("
						insert into geotiffs(
										userpkey,
										hash,
										gdalinfo,
										name,
										filesize
									)values(
										$userpkey,
										'$hash',
										'$gdalinfo',
										'$filename',
										$filesize
									)
					");

				}

			} //end if error == ""

		}else{
			$out['error']="Wrong filetype ($filetype).";
		}

	}else{
		$out['error']="Upload error ($fileerror).";
	}

}else{
	$out['error']="Not logged in.";
}

$out = json_encode($out,JSON_PRETTY_PRINT);

header('Content-Type: application/json');
echo $out;

?>