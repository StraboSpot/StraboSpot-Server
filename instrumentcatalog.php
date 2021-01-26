<?
include("logincheck.php");

//print_r($_SESSION);

$userpkey = $_SESSION['userpkey'];

include("prepare_connections.php");

$credentials = $_SESSION['credentials'];

//$db->dumpVar($_SESSION);exit();

$instcount = $db->get_var("
	select count(*) from instrument_users where users_pkey = $userpkey;
");



if(!in_array($userpkey, $admin_pkeys) && $instcount == 0){
	exit();
}

if(in_array($userpkey, $admin_pkeys)){
	$institutionrows = $db->get_results("
		select * from instrument_institution
	");
}else{
	$institutionrows = $db->get_results("
		select ii.*
		from
		instrument_users iu,
		instrument_institution ii
		where
		iu.institution_pkey = ii.pkey
		and iu.users_pkey = $userpkey
	");
}

include 'includes/header.php';
//get groups based on userpkey
?>

<style type="text/css">



</style>


<script src='/assets/js/jquery/jquery.min.js'></script>
<script src="/assets/js/featherlight/featherlight.js"></script>

<script type='text/javascript'>

</script>

<div align="center">
	<h2>Instrument Catalog</h2>
</div>

<?

foreach($institutionrows as $irow){
	?>
	
	<h3 style="font-size:1.7em;"><?=$irow->name?> Instruments: <strong><a href="add_instrument?i=<?=$irow->pkey?>">+</a></strong></h3>
	
	<?
	$instrumentrows = $db->get_results("
		select i.*
		from
		instrument i
		where i.institution_pkey = $irow->pkey
	");
	
	if(count($instrumentrows) > 0){
	?>
	
	<div class="strabotable" style="margin-left:0px;margin-bottom:10px;">

		<table>

			<tr>
				<td>&nbsp;</td>
				<td>Name</td>
				<td>Type</td>
				<td>Brand</td>
				<td>Model</td>
			</tr>
			
			<?
			foreach($instrumentrows as $row){
			?>
			<tr>
				<td style="width:60px;" nowrap><a href="edit_instrument?ii=<?=$row->pkey?>">edit</a> <a href="delete_instrument?ii=<?=$row->pkey?>" onclick="return confirm('Are you sure you want to delete this instrument?')">delete</a></td>
				<td nowrap><?=$row->instrumentname?></td>
				<td nowrap><?=$row->instrumenttype?></td>
				<td nowrap><?=$row->instrumentbrand?></td>
				<td nowrap><?=$row->instrumentmodel?></td>
			</tr>
			<?
			}
			?>
	
		</table>
	</div>
	
	<?
	}else{
	?>
	<div>No instruments yet exist for <?=$irow->name?>. Please clicke <a href="add_instrument?i=<?=$irow->pkey?>">here</a> to add an instrument.</div>
	<?
	}
}

?>








