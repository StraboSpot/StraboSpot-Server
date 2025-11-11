<?php
/**
 * File: my_jwts.php
 * Description: JSON Web Token (JWT) management interface
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


include("logincheck.php");

include("includes/config.inc.php");
include("db.php");

include("includes/mheader.php");

?>

			<!-- Main -->
				<div id="main" class="wrapper style1">
					<div class="container">

						<header class="major">
							<h2>Strabo JSON Web Tokens</h2>
						</header>

<style type="text/css">

 /* The switch - the box around the bslider */
.switch {
  position: relative;
  display: inline-block;
  width: 34px;
  height: 14px;
  border: 1px solid #333;
}

/* Hide default HTML checkbox */
.switch input {display:none;}

.selecty {
	-webkit-appearance: none;
	-moz-appearance: none;
	appearance: none;
	padding: 1px;
}

.curlBox {
	background-color:#333;
	border-radius:10px;
	word-wrap: break-word;
	padding: 5px;
	margin-bottom: 10px;
}

</style>

<div>
<p>
JSON Web Token (JWT) is an open standard (<a href="https://tools.ietf.org/html/rfc7519" target="_blank">RFC 7519</a>) that defines a compact and self-contained way for securely transmitting information between parties as a JSON object.
JWTs can be used as an alternate form of authentication for the StraboSpot ecosystem, allowing end users to access all system APIs. Please note that the endpoint for JWT calls is "/jwtdb/" and NOT "/db/". More information can be found
in the <a href="/api">API documentation</a>.
</p>
</div>

<div style="text-align: center;" class="row">
	<div style="margin: auto;" class="col-8 col-12-xsmall">
		Authenticating with a JSON Web Token requires sending a custom HTTP authorization header.<br>For example, using cURL:
		<div class="curlBox">
			curl -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c" https://strabospot.org/jwtdb/profile
		</div>
	</div>
</div>

<div style="text-align: center;" class="row">
	<div style="margin: auto;" class="col-6 col-12-xsmall">
	WARNING!! JWTS ARE CREDENTIALS, WHICH WILL GRANT ACCESS TO YOUR STRABOSPOT ACCOUNT. DO NOT SHARE OR DISTRIBUTE YOUR TOKENS WITH ANYONE YOU DO NOT WISH TO ACCESS YOUR ACCOUNT!
	</div>
</div>

<div>
	<div style="float:right;margin-top:5px;">
		<input class="primary" type="submit" onclick="window.location.href='add_jwt'" value="+ Add New JWT"></input>
	</div>
	<div style="clear:both">
		&nbsp;
	</div>
</div>

<script src='/assets/js/jquery/jquery.min.js'></script>

<script>
	function myCopy(hash) {
		var copyText = document.getElementById(hash);
		copyText.select();
		document.execCommand("copy");
		$("#successmessage").html('Code&nbsp;'+hash+'&nbsp;copied<br>to&nbsp;clipboard.');
		$("#successmessage").fadeIn();
		$("#successmessage").fadeOut(2000);
	}

	var clipboard = new ClipboardJS('.btn');

	clipboard.on('success', function(e) {
		console.info('Action:', e.action);
		console.info('Text:', e.text);
		console.info('Trigger:', e.trigger);

		$("#successmessage").html('JWT&nbsp;copied<br>to&nbsp;clipboard.');
		$("#successmessage").fadeIn();
		$("#successmessage").fadeOut(2000);

		e.clearSelection();
	});

	function  mapPub(maphash){

		if(document.getElementById('switch_'+maphash).checked){
			console.log("https://strabospot.org/map_public?maphash="+maphash+"&state=public");
			$.get("/map_public?maphash="+maphash+"&state=public");
		}else{
			console.log("https://strabospot.org/map_public?maphash="+maphash+"&state=private");
			$.get("/map_public?maphash="+maphash+"&state=private");
		}

	}

	function doJWTOption(uuid, mapname){
		var selected = $('#jwt-'+uuid).find(":selected").val();
		$('#jwt-'+uuid).find(":selected").prop('selected', false);

		switch(selected){
			case "edit":
				window.location='/edit_jwt?u='+uuid;
				break;
			case "delete":
				if (confirm("Are you sure you want to delete this?") == true) {
					window.location='/delete_jwt?u='+uuid;
				}
				break;
		}
	}

/*
https://strabospot.org/geotiff/detail/66e09349881bf
https://strabospot.org/geotiff/edit/66e09349881bf
https://strabospot.org/geotiff/delete/66e09349881bf
*/

</script>

<div id="successmessage"></div>

<?php

$userpkey = $_SESSION['userpkey'];

$rows = $db->get_results("select * from jwts where user_pkey=$userpkey order by date desc");

if(count($rows)>0){
?>

	<table class="myDataTable">
		<thead>
		<tr>
			<th>&nbsp;</th>

			<th>Note</th>
			<th class="hideSmall">JWT</th>
			<th></th>
			<th class="hideSmall">Created</th>
		</tr>
		</thead>
<?php

foreach($rows as $row){

	$jwt=$row->jwt;
	$jwtuuid=$row->uuid;
	$note=$row->note;
	$uploaddate=substr($row->date,0,10);

	?>
	<tr>
		<td nowrap>

			<select class="myDataSelect hideBigNineEighty" id="jwt-<?php echo $jwtuuid?>" onchange="doJWTOption('<?php echo $jwtuuid?>','<?php echo $note?>');">
				<option value="" style="display:none">Options...</option>
				<option value="edit">Edit</option>
				<option value="delete">Delete</option>
			</select>

			<span class="hideSmallNineEighty">
				<a href="edit_jwt?u=<?php echo $jwtuuid?>">Edit</a>&nbsp;&nbsp;&nbsp;<a href="delete_jwt?u=<?php echo $jwtuuid?>" onclick="return confirm('Are you sure want to delete this?');">Delete</a>
			</span>

		</td>
		<td><?php echo $note?></td>
		<td class="hideSmall" nowrap>

			<input type="text" id="<?php echo $jwt?>" value="<?php echo $jwt?>" size="8" style="height: 1.7em; display: inline;" readonly>
		</td>
		<td>
			<button class="btn" data-clipboard-text="<?php echo $jwt?>">
				<img class="clippy" src="/includes/images/clippy.svg" alt="Copy to clipboard" width="13">
			</button>

		</td>

		<td class="hideSmall"><?php echo $uploaddate?></td>
	</tr>
	<?php

}
?>
	</table>

<?php
}else{
	?>
	No JWTs found. Click <a href="add_jwt">here</a> to create a new JWT.
	<?php
}
?>

					<div class="bottomSpacer"></div>

					</div>
				</div>

<?php
include("includes/mfooter.php");

?>