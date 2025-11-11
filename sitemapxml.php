<?php
/**
 * File: sitemapxml.php
 * Description: Handles sitemapxml operations
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


include_once "includes/config.inc.php";
include "neodb.php"; //neo4j database abstraction layer

$querystring="match (u:User)-[HAS_PROJECT]->(p:Project {public:1})-[pdr:HAS_DATASET]->(d)-[:HAS_SPOT]->(s:Spot)
with u,p,d,count(s) as c
with u,c,p,collect(d) as d
with u,c,d,collect(p) as p
with {u:u,c:c,d:d,p:p} as u
return u";

$rows = $neodb->get_results("$querystring");

foreach($rows as $row){

	$row = $row->get("u");
	$d=$row["d"][0];
	$d=$d->values();
	$datasetid=$d['id'];

	$out.="\t<url>\n\t\t<loc>https://strabospot.org/search/?datasetid=$datasetid</loc>\n\t</url>\n";
}

$outxml='<?xml version="1.0" encoding="UTF-8"?>'."\n";
$outxml.='<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";
$outxml.=$out;
$outxml.='</urlset>';

header('Content-Type: application/xml; charset=utf-8');
echo $outxml;

?>