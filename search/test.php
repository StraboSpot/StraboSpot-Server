<?
function dumpVar($var){
	echo "<pre>";
	print_r($var);
	echo "</pre>";
}

$json = file_get_contents("test.json");

$json = json_decode($json);
$json = $json->features;

foreach($json as $j){
	if(!$j->geometry){
		dumpVar($j);
	}
}

//dumpVar($json);


































exit();


$json = '{
  "params": [
    {
      "num": 0,
      "qualifier": "and",
      "constraints": [
        {
          "constraintType": "minYear",
          "constraintValue": "1900"
        },
        {
          "constraintType": "maxYear",
          "constraintValue": "2030"
        }
      ]
    },
    {
      "num": 1,
      "qualifier": "and",
      "constraints": [
        {
          "constraintType": "imageType",
          "constraintValue": "Sketch"
        }
      ]
    },
    {
      "num": 2,
      "qualifier": "and",
      "constraints": [
        {
          "constraintType": "keyword",
          "constraintValue": "testing"
        }
      ]
    },
    {
      "num": 3,
      "qualifier": "and",
      "constraints": [
        {
          "constraintType": "microstructureExists"
        }
      ]
    },
    {
      "num": 4,
      "qualifier": "and",
      "constraints": [
        {
          "constraintType": "orientationExists"
        }
      ]
    },
    {
      "num": 5,
      "qualifier": "and",
      "constraints": [
        {
          "constraintType": "owner",
          "constraintValue": "Walker, Doug"
        }
      ]
    },
    {
      "num": 6,
      "qualifier": "and",
      "constraints": [
        {
          "constraintType": "sampleExists"
        }
      ]
    },
    {
      "num": 7,
      "qualifier": "and",
      "constraints": [
        {
          "constraintType": "sampleID",
          "constraintValue": "Nose hair"
        }
      ]
    },
    {
      "num": 8,
      "qualifier": "and",
      "constraints": [
        {
          "constraintType": "stratColumnExists"
        }
      ]
    }
  ]
}';






$json = json_decode($json);
$json = $json->params;
foreach($json as $param){
	$c = $param->constraints;
	foreach($c as $t){
		//dumpVar($t);
		echo "$t->constraintType<br>";
	}
	
}





























exit();
$datasetid = "15389702059428";
$unixtime = (int) substr($datasetid,0,10);

$timestamp = date("Y-m-d",$unixtime);

echo $timestamp;






exit();
include_once "../includes/config.inc.php";
include "../neodb.php"; //neo4j database abstraction layer

$querystring="match (d:Dataset) where d.id = 14852028649943 return d limit 1";

$rows = $neodb->get_results("$querystring");
$row = $rows[0];
$row = $row->get("d");
$d=$row->values();

$datasetid = $d['id'];
$centroid = $d['centroid'];
$datasetname = $d['name'];
$centroid = str_replace("POINT (","",$centroid);
$centroid = str_replace(")","",$centroid);
$centroid = explode(" ",$centroid);
$longitude = $centroid[0];
$latitude = $centroid[1];


echo '<script type="application/ld+json">
{  
  "@context": {
    "@vocab": "http://schema.org/",
    "re3data": "http://example.org/re3data/0.1/",
    "earthcollab": "https://library.ucar.edu/earthcollab/schema#",
    "geolink": "http://schema.geolink.org/1.0/base/main#",
    "geolink-vocab": "http://schema.geolink.org/1.0/voc/local#",
    "vivo": "http://vivoweb.org/ontology/core#",
    "dcat": "http://www.w3.org/ns/dcat#",
    "dbpedia": "http://dbpedia.org/resource/",
    "geo-upper": "http://www.geoscienceontology.org/geo-upper#"
  },  
  "@type": "Dataset",
  "isAccessibleForFree": true,
  "description": "'.$datasetname.'",
  "includedInDataCatalog": {
    "url": "https://strabospot.org",
    "@id": "https://strabospot.org"
  },
  "keywords": "strabospot, structure, tectonics",
  "name": "'.$datasetname.'",
  "url": "https://strabospot.org/search/ds/'.$datasetid.'",
  "provider": {
    "@type": "Organization",
    "legalName": "StraboSpot",
    "name": "StraboSpot",
    "url": "https://strabospot.org",
    "@id": "https://strabospot.org"
  },
  "publisher": {
    "@type": "Organization",
    "description": "StraboSpot",
    "url": "https://strabospot.org",
    "name": "StraboSpot",
    "@id": "https://strabospot.org"
  },
  "spatialCoverage": [
    {
      "@type": "Place",
      "geo": {
        "latitude": '.$latitude.',
        "longitude": '.$longitude.',
        "@type": "GeoCoordinates"
      }
    }
  ],
  "measurementTechnique": [],
  "@id": "https://strabospot.org/search/ds/'.$datasetid.'"
}
</script> ';







?>