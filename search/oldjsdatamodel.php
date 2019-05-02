<?
include_once("../straboModelClass/straboModelClass.php");

$sm = new straboModelClass();

//$sm->dumpVar($sm->fields);exit();

//$sm->dumpVar($sm->controlledlist);exit();

$groups = array();
foreach($sm->fields as $f){
	$group = $f['group'];
	if(!in_array($group,$groups)){
		$groups[]=$group;
	}
	$items[$f['num']]=$f['name'];
}

$cvoutsidedelim = "\t";

foreach($groups as $g){

	$groupvarsdelim = "\t";
	
	$groupvars .= "var $g"."_vars = {\n";
	
	foreach($sm->fields as $f){
		if($f['group']==$g){

			$groupvars .= $groupvarsdelim.$f['name'].": '".$f['label']."'";
			$groupvarsdelim = ",\n\t";

			
			
			if($sm->controlledlist[$f['num']]){
			
				$cv .= $cvoutsidedelim.$g."_".$f['name'].": {\n";
				
				$cvinsidedelim = "\t\t";
				foreach($sm->controlledlist[$f['num']] as $l){
					$cv .= $cvinsidedelim."'".$l['name']."': '".addslashes($l['label'])."'";
					$cvinsidedelim=",\n\t\t";
				}
				
				$cv .= "\n\t}";
				$cvoutsidedelim = ",\n\t";
			}


		}
	}
	
	$groupvars .= "\n}\n\n";
}

$cv = "var controlledVocab = {\n$cv\n}";

//$sm->dumpVar($items);

header("Content-type: text/javascript");

echo $groupvars;

echo "\n\n";

echo $cv;

?>