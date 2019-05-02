<?
//parse data model

function dumpVar($var){
	echo "<pre>";
	print_r($var);
	echo "</pre>";
}

function fixString($string){

	$parts = explode("_",$string);
	$delim="";
	$newstring="";
	
	foreach($parts as $part){
		$part=ucfirst($part);
		$newstring.=$delim.$part;$delim=" ";
	}
	
	$newstring=trim($newstring);
	
	return $newstring;
}

$itemlist = array();
$itemnum = 0;

function getVals($values){
	global $itemlist, $itemnum;
	
	$newarray=array();
	foreach($values as $key=>$val){
		if(is_array($val)||is_object($val)){
			getVals($val);
		}else{
			$itemlist[$itemnum]["key"]=$key;
			$parts=explode("Label: ",$val);
			$label = $parts[1];
			if($label==""){$label=fixString($key);}
			$itemlist[$itemnum]["label"]=$label;
			$itemnum++;
		}
	}
}


$data = file_get_contents("test.json");
$data = json_decode($data);

$newdata=array();
$newdata["spot"]=array();

$spotnum=0;

foreach($data as $key=>$value){
	unset($thesevalues);
	$thesevalues=array();

	if(!is_array($value) && !is_object($value)){

		$newdata["spot"][$spotnum]["key"]=$key;
		$label=explode("; ",$value)[1];
		
		$newdata["spot"][$spotnum]["label"]=fixString($key);
		
		$spotnum++;
		
	}else{
	
		if($key!="images"){
		
			$section=$key;
		
			$newdata[$section]=array();
		
			unset($itemlist);
			$itemlist=array();
			$itemnum=0;
			getVals($value);

			unset($founditems);
			$founditems=array();
		
			unset($newitemlist);
			$newitemlist=array();
		
			foreach($itemlist as $item){
				if(!in_array($item["key"],$founditems)){
					$newitemlist[]=$item;
					$founditems[]=$item["key"];
				}
			}

			sort($newitemlist);
			
			$newdata[$section]=$newitemlist;
		
		}
	
	}
	
}


foreach($newdata as $key=>$value){
	$section=$key;
	$x=0;
	foreach($value as $v){
		$key=$v["key"];
		$newdata[$section][$x]["key"]=$section."_".$key;
		$x++;
	}
}

//dumpVar($newdata);exit();

//build php code:

echo "\$cvvals = new stdClass();<br><br>";

foreach ($newdata as $key=>$values){

	echo "<br>unset(\$cvarray);<br>";
	echo "\$cvarray=array();<br><br>";
	
	echo "\$cvarray['".fixString($key).":']='selectgroup';<br>";
	
	foreach($values as $val){
	
		echo "\$cvarray['".$val["label"]."']='".$val["key"]."';<br>";
	
	}

	echo "\$cvvals->$key = \$cvarray;<br><br>";

}






































?>