<?php
/**
 * File: searchQueryRowBuilder.php
 * Description: searchQueryRowBuilder class
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


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

					$thisitem .= " ". $notsymbol ."ST_Contains(st_geomfromtext('$polygon'), spot.location)";

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

					$newkeywords = pg_escape_string(implode(" & ", $newkeywords));

					$thisitem .= "(spot.keywords @@ to_tsquery('$newkeywords') or project.keywords @@ to_tsquery('$newkeywords'))";
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