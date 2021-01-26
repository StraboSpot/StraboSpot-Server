<?

class searchQueryRowBuilder {

	function dumpVar($var){
		echo "<pre>";
		print_r($var);
		echo "</pre>";
	}

	function setDb($db){
		$this->db = $db;
	}
	
	function buildSearchQueryRows($injson){


		$json = json_decode($injson);

		$json = $json->params;
		
		//$this->dumpVar($json);exit();

		$allitems = [];
		


		foreach($json as $searchitem){
			$qual = $searchitem->qualifier;
			if($qual == "") $qual = "and";
			if($qual=="or"){
				$prequalifier = "OR";
				$notword = "";
				$notsymbol = "";
			}elseif($qual=="not"){
				$prequalifier = "AND";
				$notword = "NOT";
				$notsymbol = "!";
			}else{
				$prequalifier = "AND";
				$notword = "";
				$notsymbol = "";
			}
	
			$thisitem = " $prequalifier (";
	
			$constraints = $searchitem->constraints;
	
			//constraintValue
			
	
			//Year Min / Year Max
			if($constraints[0]->constraintType == "minYear" || $constraints[0]->constraintType == "minYear"){
				if($constraints[0]->constraintType == "minYear"){
					$minyear = $constraints[0]->constraintValue;
					if($constraints[1]->constraintType == "maxYear"){
						$maxyear = $constraints[1]->constraintValue;
					}else{
						$maxyear = 9999;
					}
				}elseif($constraints[0]->constraintType == "maxYear"){
					$minyear = 1111;
					$maxyear = $constraints[0]->constraintValue;
				}
		
				$thisitem .= "spot.date_created $notword BETWEEN '".$minyear."-1-1' AND '".$maxyear."-12-31'";
		
			}
	
			//Image Type
			if($constraints[0]->constraintType == "imageType" ){
				if($constraints[0]->constraintValue != ""){
					$imagetype = $constraints[0]->constraintValue;
			
					$thisitem .= "image.image_type ". $notsymbol ."= '$imagetype'";
				}
			}

			//Rock Type
			if($constraints[0]->constraintType == "rockType" ){
				if($constraints[0]->constraintValue != ""){
					$rocktype = $constraints[0]->constraintValue;
			
					$thisitem .= "rock_type.strabo_rock_type ". $notsymbol ."= '$rocktype'";
				}
			}
			
			//Metamorphic Facies
			if($constraints[0]->constraintType == "metFacies" ){
				if($constraints[0]->constraintValue != ""){
					$metfacies = $constraints[0]->constraintValue;
			
					$thisitem .= "rock_type.metamorphic_facies ". $notsymbol ."= '$metfacies'";
				}
			}
			
			//Tectonic Province
			if($constraints[0]->constraintType == "tectonicProvince" ){
				if($constraints[0]->constraintValue != ""){
					$gid = $constraints[0]->constraintValue;
			
					//get polygon
					$polygon = $this->db->get_var("select ST_AsText(the_geom) from shapegeology where gid=$gid");
					//$this->dumpVar($polygon);
					
					$thisitem .= " ". $notsymbol ."ST_Contains(st_geomfromtext('$polygon'), spot.location)";
/*
and ST_Contains(st_geomfromtext('MULTIPOLYGON(((139.71907082342 39.0442304283206,139.686198608511 39.1123690841548,139.678645321841 39.1848020758597,139.686996546518 39.2571636272405,139.700840825911 
39.3289819525422,139.716041584694 39.4005936920531,139.732052282212 39.4720574148382,139.750069723 39.5431908262704,139.770485528086 39.6138922773846,139.791863844707 39.6844461304745,139.823514606828 
39.7532806080661,139.891864706923 39.802499007493,139.983395704681 39.8154610619296,140.075047828605 39.7947958682578,140.119055829706 39.7347943892904,140.137162940622 39.6625149703758,140.182405081604 
39.6008637808434,140.245696504179 39.5469830024697,140.306106157425 39.4908068526185,140.335320994919 39.4235177818589,140.334615585589 39.3504126196049,140.313392237601 39.2795865464027,140.26388977629 
39.2186039075289,140.192394761021 39.1715658092557,140.110137013265 39.137140915199,140.021579763369 39.114137739312,139.929976988848 39.0996040092277,139.839963154871 39.0814656116387,139.71907082342 
39.0442304283206)))'),the_geom)
*/
					//$this->dumpVar($thisitem);
					
					//exit();
					
				}
			}

			//Keyword
			if($constraints[0]->constraintType == "keyword" ){
				if($constraints[0]->constraintValue != ""){
					$thiskeyword = "";
					$newkeywords = [];
					$keyword = $constraints[0]->constraintValue;
					$keyword = trim($keyword);
					$keyword = explode(" ", $keyword);
					foreach($keyword as $k){
						$newkeywords[] = $notsymbol.$k;
					}
			
					//$db->dumpVar($newkeywords);
			
					$newkeywords = implode(" & ", $newkeywords);
			
					//WHERE keywords @@ to_tsquery('!rock & !bar');
			
					$thisitem .= "spot.keywords @@ to_tsquery('$newkeywords')";
				}else{
					$thisitem .= "1 = 2";
				}
			}
	
			//Microstructures
			if($constraints[0]->constraintType == "microstructureExists" ){
				if($notword=="NOT"){
					$lookbool = "FALSE";
				}else{
					$lookbool = "TRUE";
				}
				$thisitem .= "spot.micro_exists = $lookbool";
			}

			//Orientation
			if($constraints[0]->constraintType == "orientationExists" ){
				if($notword=="NOT"){
					$lookbool = "FALSE";
				}else{
					$lookbool = "TRUE";
				}
				$thisitem .= "spot.orientation_exists = $lookbool";
			}
	
			//Owner
			if($constraints[0]->constraintType == "owner" ){
				$ownerpkey = $constraints[0]->constraintValue;
				$thisitem .= "users.pkey ".$notsymbol."= $ownerpkey";
				$userpkey=0;
			}

			//Sample
			if($constraints[0]->constraintType == "sampleExists" ){
				if($notword=="NOT"){
					$lookbool = "FALSE";
				}else{
					$lookbool = "TRUE";
				}
				$thisitem .= "spot.sample_exists = $lookbool";
			}

			//Sample ID
			if($constraints[0]->constraintType == "sampleID" ){
				$sampleid = strtolower($constraints[0]->constraintValue);
				$thisitem .= "lower(sample.sample_id) ".$notsymbol."= '$sampleid'";
				$userpkey=0;
			}

			//Strat Column Exists
			if($constraints[0]->constraintType == "stratColumnExists" ){
				if($notword=="NOT"){
					$lookbool = "FALSE";
				}else{
					$lookbool = "TRUE";
				}
				$thisitem .= "spot.strat_exists = $lookbool";
			}
	
			$thisitem .= " )";
			$allitems[] = $thisitem;
		}


		$searchrows = implode("\n", $allitems);

		return $searchrows;

	}

}

?>