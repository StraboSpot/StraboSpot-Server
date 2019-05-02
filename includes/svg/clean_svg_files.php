<?
/*
build pattern files for Strabo SVGs
*/

function dumpVar($var){
	echo "<pre>\n";
	print_r($var);
	echo "\n</pre>";
}

exec("rm -rf cleaned/*");

$folders = scandir("origsvgfiles");

$strabopatternnum = 555;
$straboclassnum = 1;
$patternarray=[];
$patternarraynum = 0;

foreach($folders as $folder){
	if(substr($folder,0,1)!="."){
		mkdir("cleaned/$folder");
		
		$svgfiles = scandir("origsvgfiles/$folder");
		
		foreach($svgfiles as $svgfile){
		
			if(substr($svgfile,0,1)!="."){
			
				//read svg file and fix here
				$svgout = "";
				$classes = array();
				
				$xmldata = simplexml_load_file("origsvgfiles/$folder/$svgfile") or die("Failed to load");

				$style = $xmldata->defs->style->asXML();

				//dumpVar($style);
				
				// find original class names so we can replace them later
				$rectclasses=[];
				$classes=[];
				$foundclasses=[];
				$workstyle = $style;
				$workstyle = str_replace("<style>","",$workstyle);
				$workstyle = str_replace("</style>","",$workstyle);
				$workstyle = str_replace(",","\n",$workstyle);
				$workstyle = str_replace("}","\n",$workstyle);
				$oldclasses = explode("\n",$workstyle);
				
				//echo "\n\n\n";
				//dumpVar($oldclasses);
				
				$classnum=0;
				foreach($oldclasses as $oldclass){
					if($oldclass!=""){
						if(strpos($oldclass, "{")){
							//also get pattern number for replacement
							if(strpos($oldclass, "fill:url")){
								$pnum = substr($oldclass, strpos($oldclass, "fill:url"));
								$pnum = str_replace("fill:url(#_","",$pnum);
								$pnum = str_replace(");","",$pnum);
							}
							$oldclass = substr($oldclass,0,strpos($oldclass, "{"));
						}
						$oldclass = str_replace(".","",$oldclass);
						
						if(!in_array($oldclass,$foundclasses)){
							$foundclasses[]=$oldclass;
							$classes[$classnum]['oldname']=$oldclass;
							$classes[$classnum]['newname']="straboclass-".$straboclassnum;
							$straboclassnum++;
							$classnum++;
						}
					}
				}
				
				//dumpVar($classes);
				//dumpVar($foundclasses);
				//echo "pnum: $pnum";
				
				$rects = count($xmldata->rect);
				for($x=0; $x<count($xmldata->rect); $x++){
					//$classes[] = (string) $xmldata->rect[$x]['class'];
					$thisclass = (string) $xmldata->rect[$x]['class'];
					foreach($classes as $c){
						if($c['oldname']==$thisclass){
							$rectclasses[]=$c['newname'];
						}
					}
				}

				$folder1 = $folder;
				$folder2 = str_replace(".svg","",$svgfile);

				$patternarray[$patternarraynum]['folder1']=$folder1;
				$patternarray[$patternarraynum]['folder2']=$folder2;
				$patternarray[$patternarraynum]['class1']=$rectclasses[0];
				$patternarray[$patternarraynum]['class2']=$rectclasses[1];

				$patternarraynum++;

				$pattern = $xmldata->defs->pattern;
				$pattern = prettyXML($pattern);
				
				$svgout = $style . "\n" . $pattern . "\n";
				
				foreach($classes as $c){
					$svgout = str_replace($c['oldname'],$c['newname'],$svgout);
				}
				
				$svgout = str_replace("#_$pnum","#_$strabopatternnum",$svgout);
				$svgout = str_replace("id=\"_$pnum\"","id=\"_$strabopatternnum\"",$svgout);
				$svgout = str_replace("data-name=\"$pnum\"","data-name=\"$strabopatternnum\"",$svgout);
				
				$strabopatternnum++;

				file_put_contents("cleaned/$folder/$svgfile",$svgout);

			}
		
		}
		
	}
}

//Make blank folder too for polygons with no styles
mkdir("cleaned/Blank");
$line = "<style>.straboclass-$strabopatternnum{fill:#FFFFFF;}</style>\n";
file_put_contents("cleaned/Blank/Blank.svg",$line);
$patternarray[$patternarraynum]['folder1']="Blank";
$patternarray[$patternarraynum]['folder2']="Blank";
$patternarray[$patternarraynum]['class1']="straboclass-$strabopatternnum";
$patternarray[$patternarraynum]['class2']="straboclass-$strabopatternnum";


$patternarray = json_encode($patternarray, JSON_PRETTY_PRINT);
file_put_contents("cleaned/patterninfo.json",$patternarray);
//echo $patternarray;
echo "done.\n";

function prettyXML($simpleXml){
	$dom = new DOMDocument("1.0");
	$dom->preserveWhiteSpace = false;
	$dom->formatOutput = true;
	$dom->loadXML($simpleXml->asXML());
	$out = $dom->saveXML();
	$out = str_replace('<?xml version="1.0"?>'."\n","",$out);
	return $out;
}


?>