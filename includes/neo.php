<?
//*********************************************************
// This file contains functions to connect to the neo4j
// database.
//
// Author: Jason Ash 02/13/2015
//
//*********************************************************



function neoSearch($querystring){

	global $neousername;
	global $neopassword;

	//get the feature from neo4j
	$searchjson="{\"statements\" : [{\"statement\" : \"$querystring\"}]}";

	$url = "http://127.0.0.1:7474/db/data/transaction/commit";
	$headers = array(
		"Content-Type: application/json"
	);
	$rest = curl_init();
	curl_setopt($rest,CURLOPT_URL,$url);
	curl_setopt($rest,CURLOPT_POST,1);
	curl_setopt($rest,CURLOPT_POSTFIELDS,$searchjson);
	curl_setopt($rest,CURLOPT_HTTPHEADER,$headers);
	curl_setopt($rest, CURLOPT_USERPWD, $neousername . ":" . $neopassword);
	curl_setopt($rest,CURLOPT_RETURNTRANSFER, true);
	curl_setopt($rest, CURLOPT_VERBOSE, 1);
	curl_setopt($rest, CURLOPT_HEADER, 1);
	$response = curl_exec($rest);
	$header_size = curl_getinfo($rest, CURLINFO_HEADER_SIZE);
	$header = substr($response, 0, $header_size);
	$body = substr($response, $header_size);
	$body = json_decode($body);

	$featuredata = $body->results[0]->data;
	
	return $featuredata;

}




?>