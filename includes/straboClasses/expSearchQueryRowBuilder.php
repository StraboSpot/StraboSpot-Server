<?php
/**
 * File: expSearchQueryRowBuilder.php
 * Description: expSearchQueryRowBuilder class
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


class expSearchQueryRowBuilder {

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

					$thisitem .= "p.keywords @@ to_tsquery('$newkeywords')";
				}else{
					$thisitem .= "1 = 2";
				}
			}

			$thisitem .= " )";
			$allitems[] = $thisitem;
		}

		$searchrows = implode("\n", $allitems);

		return $searchrows;

	}

}

?>