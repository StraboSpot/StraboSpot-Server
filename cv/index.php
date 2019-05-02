<?
//this script take xml files pulled from kobotoolbox and turns them into JSON suitable
//for defining columns for shapefile upload - JMA 05/07/2015




//$xmltypes = array("contact","fault","fold","orientation","other_notes","rock_description","sample_locality","shear_zone");

//$xmltypes = array("contacts_and_traces","fault","fold","measurements_and_observations","rock_description","shear_zone");

$xmltypes = array("contacts_and_traces","general","measurements_and_observations","other_notes","rock_description","sample_locality");

$typecount=0;

$types=array();

foreach($xmltypes as $type){

	$lowtype = strtolower($type);
	
	$dom = new DOMDocument();
	$dom->preserveWhiteSpace=false;
	$dom->load($lowtype.".xml");


	$instances = $dom->getElementsByTagName('instance');
	foreach ($instances as $instance) {

		$foo = $instance->firstChild;
		$typename=$foo->getAttribute('id');

	}


	$heads = $dom->getElementsByTagName('head');
	foreach ($heads as $head) {

		$title = $head->firstChild;
		$types[$typename]['typelabel']=$title->textContent;

	}


	//build array of itemname/required
	unset($reqarray);
	$reqarray=array();
	$binds = $dom->getElementsByTagName('bind');
	foreach($binds as $bind){
		$tname = end(explode("/",$bind->getAttribute('nodeset')));
		$req = str_replace("()","",$bind->getAttribute('required'));
		if($tname != "" && $req != ""){
			$reqarray[$tname]=$req;
		}
	}

	//print_r($reqarray);
	
	//exit();
	
	$bodys = $dom->getElementsByTagName('body');
	foreach ($bodys as $body) {
		
		//echo $body->childNodes->length;
		
		$nodes = $body->childNodes;
		
		$nodecount=0;
		
		foreach($nodes as $node){
		
			$cvlist = array();
			//echo $node->tagName."<br>";
			
			$itemname = end(explode("/",$node->getAttribute('ref')));
			$types[$typename]['columns'][$nodecount]['name'] = $itemname;
			
			$label=$node->firstChild;

			$types[$typename]['columns'][$nodecount]['label']=$label->textContent;

			//get required yes/no here
			$types[$typename]['columns'][$nodecount]['required']=$reqarray[$itemname];

			$items = $node->getElementsByTagName('value');
			foreach($items as $item){
				array_push($cvlist,$item->textContent);
			}
			
			if(count($cvlist)>0){
				$types[$typename]['columns'][$nodecount]['cv']=$cvlist;
			}
			
			//print_r($cvlist);exit();
			
			//$types[$typecount]['columns'][$nodecount]=$node->tagName;
			
			$nodecount++;
		
		}

	}


	$typecount++;

}

//exit();

$jsonout = json_encode($types);

echo $jsonout;


exit();












































































//exit();


echo "<br><br><br><br><br>\n\n\n";


$newout = json_decode($jsonout);

print_r($newout);

exit();

echo "<br><br><br><br><br>\n\n\n";

$foo = $newout->orientation;

print_r($foo);






















































exit();
$xmltypes = array('Contact','Orientation');

$typecount=0;

$types=array();

foreach($xmltypes as $type){

	$lowtype = strtolower($type);
	
	$dom = new DOMDocument();
	$dom->preserveWhiteSpace=false;
	$dom->load($lowtype.".xml");

	$models = $dom->getElementsByTagName('model');
	foreach ($models as $model) {

		//echo $model->childNodes->length;

		$instance = $model->firstChild;
		$typenamenode = $instance->firstChild; //get type name from id attribute (i.e. <orientation )
		$typename = $typenamenode->getAttribute('id');
		
		$types[$typecount]['typename']=$typename;
		
		//echo $typenamenode->childNodes->length;
		
		$nodes = $typenamenode->childNodes;
		
		$nodecount=0;
		
		foreach($nodes as $node){
		
			//echo $node->tagName."<br>";
			
			$types[$typecount]['columns'][$nodecount]=$node->tagName;
			
			$nodecount++;
		
		}

	}


	$typecount++;

}

$jsonout = json_encode($types);

echo $jsonout;

























exit();




$xmltypes = array('Contact');

$typecount=0;

$types=array();

foreach($xmltypes as $type){

	$lowtype = strtolower($type);
	
	$dom = new DOMDocument();
	$dom->preserveWhiteSpace=false;
	$dom->load($lowtype.".xml");
	
	$types[$typecount]['typename']=$typename; //fix this to get typename
	
	$bodys = $dom->getElementsByTagName('body');
	foreach ($bodys as $body) {
		
		echo $body->childNodes->length;exit();
		
		$nodes = $body->childNodes;
		
		$nodecount=0;
		
		foreach($nodes as $node){
		
			echo $node->tagName."<br>";
			
			$types[$typecount]['columns'][$nodecount]=$node->tagName;
			
			$nodecount++;
		
		}

	}


	$typecount++;

}

$jsonout = json_encode($types);

echo $jsonout;





//******************************************************************************************


$types=array();
$types[0]['typename']="contact";
$types[0]['columns'][0]="foo";
$types[0]['columns'][1]="bar";
$types[0]['columns'][2]="blah";
$types[0]['columns'][3]="blah";
$types[0]['columns'][4]="blah";

$types[1]['typename']="orientation";
$types[1]['columns'][0]="foo";
$types[1]['columns'][1]="bar";
$types[1]['columns'][2]="blah";
$types[1]['columns'][3]="blah";
$types[1]['columns'][4]="blah";



$jsonout = json_encode($types);

echo $jsonout;









exit();


exit();
$types = array('Contact');

foreach($types as $type){

	$lowtype = strtolower($type);
	
	$dom = new DOMDocument();
	$dom->preserveWhiteSpace=false;
	$dom->load($lowtype.".xml");

	$bodys = $dom->getElementsByTagName('body');
	foreach ($bodys as $body) {
		print_r($body);
	}




}





?>
