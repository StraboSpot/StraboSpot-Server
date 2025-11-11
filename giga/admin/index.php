<?php
/**
 * File: index.php
 * Description: Main page or directory index
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

include_once "../../includes/config.inc.php"; //credentials, etc
include "../../db.php"; //postgres database abstraction layer

$rows = $db->get_results("select * from giga order by pkey");

include("../header.php");
?>
<h1>Gigapan Admin Interface</h1>

<div style="padding-bottom:5px;">
	<span><a href="add">(+ Add New Entry)</a></span>
	<span style="padding-left:50px;"><a href="uploadjson">Upload JSON Data</a></span>
</div>

<?php

foreach($rows as $row){
	$pkey = $row->pkey;
	$image_title = $row->image_title;
	$image_description = $row->image_description;
	$p_gigapan_id = $row->p_gigapan_id;
	$x_gigapan_id = $row->x_gigapan_id;
	$general_location = $row->general_location;

	?>
	<div class="gigarow">
		<table class="gigatable">
			<tr>
				<td width="100px">
					<a href="edit?s=<?php echo $pkey?>"><img src = "http://static.gigapan.com/gigapans0/<?php echo $p_gigapan_id?>/images/<?php echo $p_gigapan_id?>-150x100.jpg" width="100px;"></a>
				</td>
				<td>
					<div class="rowtitle">
						<a href="edit?s=<?php echo $pkey?>"><?php echo $image_title?></a>
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
	<?php

}

?>

<?php
include("../footer.php");
?>