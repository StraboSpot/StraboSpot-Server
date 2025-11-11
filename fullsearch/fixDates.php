<?php
/**
 * File: fixDates.php
 * Description: Handles fixDates operations
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

ini_set('max_execution_time', '0');
include("searchWorker.php");
include("../includes/straboClasses/straboOutputClass.php");

exit();

/*
$foo = "https:\/\/strabospot.org\/db\/image\/15266781363342";
$foo = str_replace("\/db\/image\/", "\/pi\/", $foo);
echo $foo;
exit();
*/

$rows = $db->get_results("
	select s.spotjson
	from
	spot s, image i
	where
	s.spot_pkey = i.spot_pkey
	and
	(
	((i.caption is not null and i.caption != '') or (i.title is not null and i.title != ''))
	or s.has_rosetta)
	and ST_Contains(ST_GeomFromText('POLYGON((-117.5125 38.977500343323,-113.2375 40.552500343323,-113.63125 37.346250343323,-117.5125 38.977500343323))'), s.location)
	group by s.spotjson;
");


foreach($rows as $row){
	$json = $row->spotjson;
	$json = str_replace("\/db\/image\/", "\/pi\/", $json);
	$db->dumpVar($json);
}



























































exit();
$strabo = new StraboSpot($neodb,999,$db);
$straboOut = new straboOutputClass($strabo,$_GET);

/*

select array_length(string_to_array(caption,' '),1) from image limit 5;

select array_length(string_to_array(caption,' '),1) as count, caption from image where caption is not null and caption != '' limit 5;


*/

//$projectrows = $neodb->get_results("match (p:Project) return p order by id(p) skip 2000 limit 50");
//1067 421205 Big Bend

//$projectrows = $neodb->get_results("match (p:Project) return p, id(p) as pid order by id(p)");
$projectrows = $neodb->get_results("match (p:Project) where id(p) > 1009835 return p, id(p) as pid order by id(p)");

$x = 1;


foreach($projectrows as $projectrow){

	$neoprojectid = $projectrow->get("pid");

	$projectrow=$projectrow->get("p");
	$projectrow=(object)$projectrow->values();

	$userpkey = $projectrow->userpkey;
	$projectid = $projectrow->id;
	$projectname = $projectrow->desc_project_name;


	$strabo->setuserpkey($userpkey);

	$data = $straboOut->doiDataOut($projectid);

	$projectDb = $data->projectDb;
	$projectData = $projectDb->project;
	$tags = $projectData->tags;

	foreach($tags as $tag){
		if($tag->type == "rosetta"){
			foreach($tag->spots as $spotid){
				$db->prepare_query("UPDATE spot SET has_rosetta = true WHERE strabo_spot_id = $1 AND user_pkey = $2", array($spotid, $userpkey));
			}
		}
	}

	$spotsDb = $data->spotsDb;
	foreach($spotsDb as $spot){
		foreach($spot->properties->images as $image){
			if($image->caption != "" || $image->title != ""){
				$caption = $image->caption;
				$title = $image->title;
				$imageid = $image->id;
				$db->prepare_query("UPDATE image SET caption=$1, title=$2 WHERE strabo_image_id=$3 AND user_pkey = $4", array($caption, $title, $imageid, $userpkey));
			}
		}
	}


	echo "$x $neoprojectid $projectname\n";

	flush();
	ob_flush();

	$x++;
}

exit();
foreach($projectrows as $projectrow){

	$projectrow=$projectrow->get("p");
	$projectrow=(object)$projectrow->values();

	$userpkey = $projectrow->userpkey;
	$projectid = $projectrow->id;
	$projectname = $projectrow->desc_project_name;


	$strabo->setuserpkey($userpkey);

	$data = $straboOut->doiDataOut($projectid);

	$projectDb = $data->projectDb;
	$projectData = $projectDb->project;
	$tags = $projectData->tags;

	foreach($tags as $tag){
		if($tag->type == "rosetta"){
			foreach($tag->spots as $spotid){
				$db->prepare_query("UPDATE spot SET has_rosetta = true WHERE strabo_spot_id = $1 AND user_pkey = $2", array($spotid, $userpkey));
			}
		}
	}

	$spotsDb = $data->spotsDb;
	foreach($spotsDb as $spot){
		foreach($spot->properties->images as $image){
			if($image->caption != "" || $image->title != ""){
				$caption = $image->caption;
				$title = $image->title;
				$imageid = $image->id;
				$db->prepare_query("UPDATE image SET caption=$1, title=$2 WHERE strabo_image_id=$3 AND user_pkey = $4", array($caption, $title, $imageid, $userpkey));
			}
		}
	}


	echo "$x $projectname\n";

	flush();
	ob_flush();

	$x++;
}






























//$rows = $db->get_results("select to_char ( last_modified, 'MM/DD/YYYY' ) from project limit 10");


/*

neo4j:
modified_timestamp:
1717767348
1645234436296
1673458567059

INSERT INTO foo VALUES ( to_timestamp(1342986162) )


project_pkey = 7198


update project set last_modified = to_timestamp(1342986162) where project_pkey = 7198;
*/

/*
$rows = $neodb->get_results("match (p:Project) return p order by id(p) limit 1");

foreach($rows as $row){

	$row=$row->get("p");
	$row=(object)$row->values();


	$userpkey = $row->userpkey;
	$id = $row->id;

	$modified_timestamp = $row->modified_timestamp;
	$modified_timestamp = substr($modified_timestamp, 0, 10);
	if(strlen($modified_timestamp) == 10){
		echo "$userpkey $id $modified_timestamp<br>";
		$db->query("update project set last_modified = to_timestamp($modified_timestamp) where strabo_project_id = '$id' and user_pkey = $userpkey;");
	}

}
*/

//$rows = $db->get_results("select * from project where last_modified != '2024-11-04 11:18:07.145413-05' limit 20");

//2024-11-04 11:18:07.145413-05


//$rows = $db->get_results("select * from project where last_modified = '2024-11-04 11:18:07.145413-05' order by project_pkey limit 1000");
//$count = count($rows);

/*
foreach($rows as $row){
	$userpkey = $row->user_pkey;
	$id = $row->strabo_project_id;

	$modified_timestamp = $row->strabo_project_id;
	$modified_timestamp = substr($modified_timestamp, 0, 10);
	if(strlen($modified_timestamp) == 10){
		echo "$userpkey $id $modified_timestamp<br>";
		$db->query("update project set last_modified = to_timestamp($modified_timestamp) where strabo_project_id = '$id' and user_pkey = $userpkey;");
	}
}
*/

/*
$rows = $neodb->get_results("match (p:Project) return p order by id(p) limit 10000");

foreach($rows as $row){

	$row=$row->get("p");
	$row=(object)$row->values();



	$userpkey = $row->userpkey;
	$id = $row->id;


	if($row->desc_notes != ""){
		$notes = pg_escape_string($row->desc_notes);
		$db->query("update project set notes = '$notes' where strabo_project_id = '$id' and user_pkey = $userpkey;");
		echo $notes."<br>";
	}


}
*/









?>