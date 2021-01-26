<?
include_once "../includes/config.inc.php"; //credentials, etc
include "../db.php"; //postgres database abstraction layer

//$db->dumpVar($_SERVER);

/*
Array
(
    [class] => igneous,metamorphic,lunar,meteorite
    [type] => volcanic,plutonic,regional metamorphic,contact metamorphic,cumulate,mantle
    [keyword] => foo
)
*/

//set up query

$class=$_GET['class'];
$type=$_GET['type'];
$keyword=$_GET['keyword'];

if($class!=""){
	$classes = explode(",",$class);
	$classesmatch = [];
	foreach($classes as $c){
		$classesmatch[] = "'$c'";
	}
	$classesmatch = implode (", ", $classesmatch);
	$classquery = "and class in ($classesmatch)";
}

if($type!=""){
	$types = explode(",",$type);
	$typesmatch = [];
	foreach($types as $t){
		$typesmatch[] = "'$t'";
	}
	$typesmatch = implode (", ", $typesmatch);
	$typequery = "and rock_type in ($typesmatch)";
}

if($keyword!=""){
	$newkeywords = [];
	$keywords = explode(" ", $keyword);
	//$db->dumpVar($keywords);exit();
	foreach($keywords as $keyword){
		$keyword = str_replace(",", "", $keyword);
		
		$newkeywords[]=$keyword;
	}
	$newkeywords = implode(" & ", $newkeywords);
	
	//WHERE keywords @@ to_tsquery('!rock & !bar');
	
	$keywordquery = "and keywords @@ to_tsquery('$newkeywords')";
}

$query = "
	select * 
	from giga 
	where
	1 = 1
	$classquery
	$typequery
	$keywordquery
	order by pkey
";

//$db->dumpVar($query);exit();

$rows = $db->get_results("$query");

include("header.php");
?>
<h1>Gigapan Index</h1>
<?
if(count($rows)==0){
?>
	<div>Sorry, no results match your search criteria.</div>
<?
}


?>
<table>
<?
$col=1;

foreach($rows as $row){
	$pkey = $row->pkey;
	$image_title = $row->image_title;
	$image_description = $row->image_description;
	$p_gigapan_id = $row->p_gigapan_id;
	$x_gigapan_id = $row->x_gigapan_id;
	$general_location = $row->general_location;
	
	if($col == 1){
	
		echo "<tr>";
		
	}
	
	?>
	<td width="50%">
		<div class="gigarow">
			<table class="gigatable">
				<tr>
					<td width="100px">
						<a href="detail?s=<?=$pkey?>"><img src = "http://static.gigapan.com/gigapans0/<?=$p_gigapan_id?>/images/<?=$p_gigapan_id?>-150x100.jpg" width="100px;"></a>
					</td>
					<td>
						<div class="rowtitle">
							<a href="detail?s=<?=$pkey?>"><?=$image_title?></a>
						</div>
						<div class="rowDescription">
							<?=$image_description?>
						</div>
					<div class="general_location">
						<?=$general_location?>
					</div>
					</td>
				</tr>
			</table>
		</div>
	</td>
	<?
	
	if($col == 1){
		$col = 2;
	}else{
		echo "</tr>";
		$col = 1;
	}
	
}


?>
</table>




<?
include("footer.php");
?>