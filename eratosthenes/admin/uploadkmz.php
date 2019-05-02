<?

//accept KMZ file and parse it into points and lines for map display.
//store as .json file

/*
Array
(
    [fileToUpload] => Array
        (
            [name] => FInal Teams.kmz
            [type] => application/vnd.google-earth.kmz
            [tmp_name] => /tmp/phpUmuNE1
            [error] => 0
            [size] => 3462
        )

)

Array
(
    [dirname] => .
    [basename] => FInal Teams.kmz
    [extension] => kmz
    [filename] => FInal Teams
)
*/

function dumpVar($var){
	echo "<pre>\n";
	print_r($var);
	echo "\n</pre>";
}

if(isset($_POST["submit"])){

	exec("rm -rf ziptemp/*");
	
	if(!$_FILES['fileToUpload']['error']){
	
		$pathinfo = pathinfo($_FILES['fileToUpload']['name']);
		if($pathinfo['extension']=="kmz"){
	
			$zip = new ZipArchive;
			$res = $zip->open($_FILES['fileToUpload']['tmp_name']);
			if ($res === TRUE) {
				$zip->extractTo('ziptemp');
				$zip->close();
				
				$xmlstring = file_get_contents("ziptemp/doc.kml");
				$xml = simplexml_load_string($xmlstring);
				$json = json_encode($xml);
				$array = json_decode($json,TRUE);
				$array = $array['Document']['Folder']['Placemark'];
				$data = (object) $array;

				$out = [];
				
				if(count($data)>0){

					$pointfeatures = [];
					$linefeatures = [];
										
					foreach($data as $row){

						unset($thispoint);
						unset($thisline);

						if($row['Point']){

							$thispoint['type']="Feature";
							$thispoint['geometry']['type']='Point';
							$thispoint['properties']['name']=$row['name'];
							$coords = $row['Point']['coordinates'];
							$parts = explode(",",$coords);
							$thispoint['geometry']['coordinates'] = array((real)$parts[0],(real)$parts[1]);
							$pointfeatures[]=$thispoint;
						}
						
						if($row['LineString']){
							
							//dumpVar($row);exit();
							
							$linecoords = [];
							$thisline['type']="Feature";
							$thisline['geometry']['type']='LineString';
							$thisline['properties']['name']=$row['name'];
							$coords = trim($row['LineString']['coordinates']);
							$parts = explode(" ",$coords);
							foreach($parts as $part){
								$bits = explode(",",$part);
								$linecoords[] = array((real)$bits[0],(real)$bits[1]);
							}
							$thisline['geometry']['coordinates'] = $linecoords;
							$linefeatures[]=$thisline;
						}
					}

					/*
					$out['points'] = $points;
					$out['lines'] = $lines;
					$out = json_encode($out,JSON_PRETTY_PRINT);
					*/
					
					//dumpVar($pointfeatures);
					
					$pointsout['type']='FeatureCollection';
					$pointsout['features']=$pointfeatures;
					$pointsout = json_encode($pointsout,JSON_PRETTY_PRINT);
					
					$linesout['type']='FeatureCollection';
					$linesout['features']=$linefeatures;
					$linesout = json_encode($linesout,JSON_PRETTY_PRINT);

					exec("rm ../data/lines.json");
					exec("rm ../data/points.json");
					exec("rm ../data/EratosthenesMap.kmz");
					
					move_uploaded_file($_FILES['fileToUpload']['tmp_name'], "../data/EratosthenesMap.kmz");
					file_put_contents("../data/lines.json", $linesout);
					file_put_contents("../data/points.json", $pointsout);
					
					include("adminheader.php");
					echo "Success! KMZ file has been uploaded successfully.";
					echo "<br><br><div class=\"linkheading\"><a href=\".\">Continue</a></div>";
					include("adminfooter.php");
					exit();
				}else{
					include("adminheader.php");
					echo "Error: No data found in KMZ file.";
					echo "<br><br><div class=\"linkheading\"><a href=\".\">Continue</a></div>";
					include("adminfooter.php");
					exit();
				}
				
			} else {
				include("adminheader.php");
				echo 'Error: Unzip of KMZ failed, code:' . $res;
				echo "<br><br><div class=\"linkheading\"><a href=\".\">Continue</a></div>";
				include("adminfooter.php");
				exit();
			}
	
		}else{
			include("adminheader.php");
			echo "Error: wrong file type. must be kmz";
			echo "<br><br><div class=\"linkheading\"><a href=\".\">Continue</a></div>";
			include("adminfooter.php");
			exit();
		}
	}else{
		include("adminheader.php");
		echo "Error: ".$_FILES['fileToUpload']['error'];
		echo "<br><br><div class=\"linkheading\"><a href=\".\">Continue</a></div>";
		include("adminfooter.php");
		exit();
	}










	exit();
}













include("adminheader.php");
?>

Uploading a KMZ file will completely replace the existing map. It would be best to first<br>
download the existing map <a href="../data/EratosthenesMap.kmz">here</a> and then modify and re-upload.<br><br>

<form method="post" enctype="multipart/form-data">
    Select KMZ file to upload:<br><br>
    <input type="file" name="fileToUpload" id="fileToUpload"><br><br>
    <input type="submit" value="Upload" name="submit">
</form>

<?
include("adminfooter.php");
?>