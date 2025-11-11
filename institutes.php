<?php
/**
 * File: institutes.php
 * Description: StraboMicro Institutes
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

include("logincheck.php");

$userpkey = $_SESSION['userpkey'];

include("prepare_connections.php");

$credentials = $_SESSION['credentials'];

if(!in_array($userpkey, $admin_pkeys)){ //restrict to admins
	exit();
}

$instituterows = $db->get_results("select * from institute order by institute_name");

include 'includes/header.php';
//get groups based on userpkey
?>

<style type="text/css">

.rowdiv {
	text-align:center;
	padding-top:5px;
}

.rowheader {
	
	color:#333;
	font-size:1.2em;
}

.redred {
	color:#ab1424;
	font-weigth:bold;
	padding-right:5px;
}

.button {
   /* Green */
  
  
  padding: 3px 20px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 14px;
}

</style>

<script src='/assets/js/jquery/jquery.min.js'></script>
<script src="/assets/js/featherlight/featherlight.js"></script>

<div class="rowdiv">
	<h2>StraboMicro Institutes</h2>
</div>

<h3 style="font-size:1.7em;"><strong><a href="add_institute">+</a></strong></h3>

	<div class="strabotable" style="margin-left:0px;margin-bottom:10px;">

		<table>

			<tr>
				<td>&nbsp;</td>
				<td>Name</td>
				<td>Type</td>
			</tr>

			<?php
			foreach($instituterows as $row){
			?>
			<tr>
				<td style="width:60px;" nowrap><a href="edit_institute?p=<?php echo $row->pkey?>">edit</a>&nbsp;&nbsp;&nbsp;<a href="delete_institute?p=<?php echo $row->pkey?>" onclick="return confirm('Are you sure you want to delete this institute?')">delete</a></td>
				<td nowrap><?php echo $row->institute_name?></td>
				<td nowrap><?php echo $row->institute_type?></td>
			</tr>
			<?php
			}
			?>

		</table>
	</div>

<?php
include 'includes/footer.php';
?>