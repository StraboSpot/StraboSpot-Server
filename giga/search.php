<?php
/**
 * File: search.php
 * Description: in class
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

include_once "../includes/config.inc.php"; //credentials, etc
include "../db.php"; //postgres database abstraction layer

//set up query

$valid_classes = ['igneous', 'metamorphic', 'lunar', 'meteorite', 'sedimentary'];
$valid_types = ['volcanic', 'plutonic', 'regional metamorphic', 'contact metamorphic', 'cumulate', 'mantle'];

$class = isset($_GET['class']) ? $_GET['class'] : '';
$type = isset($_GET['type']) ? $_GET['type'] : '';
$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';

$classquery = "";
$typequery = "";
$keywordquery = "";

if($class != ""){
	$classes = explode(",", $class);
	$classesmatch = [];
	foreach($classes as $c){
		$c = trim($c);
		if(in_array($c, $valid_classes)){
			$classesmatch[] = pg_escape_literal($c);
		}
	}
	if(count($classesmatch) > 0){
		$classesmatch = implode(", ", $classesmatch);
		$classquery = "and class in ($classesmatch)";
	}
}

if($type != ""){
	$types = explode(",", $type);
	$typesmatch = [];
	foreach($types as $t){
		$t = trim($t);
		if(in_array($t, $valid_types)){
			$typesmatch[] = pg_escape_literal($t);
		}
	}
	if(count($typesmatch) > 0){
		$typesmatch = implode(", ", $typesmatch);
		$typequery = "and rock_type in ($typesmatch)";
	}
}

if($keyword != ""){
	$newkeywords = [];
	$keywords = explode(" ", $keyword);
	foreach($keywords as $kw){
		$kw = preg_replace('/[^a-zA-Z0-9]/', '', $kw);
		if($kw != ""){
			$newkeywords[] = $kw;
		}
	}
	if(count($newkeywords) > 0){
		$newkeywords = implode(" & ", $newkeywords);
		$safe_keywords = pg_escape_literal($newkeywords);
		$keywordquery = "and keywords @@ to_tsquery($safe_keywords)";
	}
}

$query = "
	SELECT *
	FROM giga
	WHERE
	1 = 1
	$classquery
	$typequery
	$keywordquery
	ORDER BY pkey
";

$rows = $db->get_results($query);

include("header.php");
?>
<h1>Gigapan Index</h1>
<?php
if(count($rows)==0){
?>
	<div>Sorry, no results match your search criteria.</div>
<?php
}

?>
<table>
<?php
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
						<a href="detail?s=<?php echo $pkey?>"><img src = "http://static.gigapan.com/gigapans0/<?php echo $p_gigapan_id?>/images/<?php echo $p_gigapan_id?>-150x100.jpg" width="100px;"></a>
					</td>
					<td>
						<div class="rowtitle">
							<a href="detail?s=<?php echo $pkey?>"><?php echo $image_title?></a>
						</div>
						<div class="rowDescription">
							<?php echo $image_description?>
						</div>
					<div class="general_location">
						<?php echo $general_location?>
					</div>
					</td>
				</tr>
			</table>
		</div>
	</td>
	<?php

	if($col == 1){
		$col = 2;
	}else{
		echo "</tr>";
		$col = 1;
	}

}

?>
</table>

<?php
include("footer.php");
?>