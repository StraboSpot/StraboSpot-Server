<?

include("includes/header.php");

$dsid = $_GET['dsid'];

?>
<style type="text/css">
.button {
    background-color: #666; 
    border: none;
    color: white;
    padding: 7px 32px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    border-radius: 5px;
}
.button:hover {
    background-color: #000;
    color: white;
}

	#map {
		width:960px;
		height:500px;
		background-color:#EEE;
	}

#successmessage {
	background-color:#30ae4f;
	color:#FFF;
	padding:5px;
	display:none;
	position: absolute;
	left: 50%;
	top: 450px;
	transform: translate(-50%,-50%);
	width: 500px;
	z-index: 30000;
	padding: 0.5em 0.5em 0.5em 0.5em;
	border: 2px solid #666666;
	border-radius: 8px;
	font-size:24px;
	text-align:center;
}

.copybutton {
	width:25px;
	height:25px;
}

</style>

<script src='/assets/js/jquery/jquery.min.js'></script>

<script>
	function myCopy() {
		var copyText = document.getElementById('copybox');
		copyText.select();
		document.execCommand("copy");
		//alert("Copied code: " + copyText.value + " to clipboard");
		$("#successmessage").html('Code&nbsp;'+copyText.value+'&nbsp;copied<br>to&nbsp;clipboard.');
		$("#successmessage").fadeIn();
		$("#successmessage").fadeOut(2000);
	}

	var clipboard = new ClipboardJS('.btn');

	clipboard.on('success', function(e) {
		console.info('Action:', e.action);
		console.info('Text:', e.text);
		console.info('Trigger:', e.trigger);
		
		$("#successmessage").html('Code&nbsp;'+e.text+'&nbsp;copied<br>to&nbsp;clipboard.');
		$("#successmessage").fadeIn();
		$("#successmessage").fadeOut(2000);

		e.clearSelection();
	});

</script>

<div id="successmessage"></div>


<div>
	<div align="center" style="padding-top:10px; font-size:1.7em;">
		Use this URL as a landing page for publication:
	</div>
	<div align="center" style="padding-top:5px;">
		<input id="copybox" style="font-size:1.8em;" size="50" type="text" value="https://strabospot.org/search/?datasetid=<?=$dsid?>" readonly>
		
		<!--<a href="javascript:void(0);" onclick="myCopy();"><img class="copybutton" src="/includes/images/copy-icon.png"></img></a>-->

			<button class="btn" data-clipboard-text="https://strabospot.org/search/?datasetid=<?=$dsid?>">
				<img class="copybutton" src="/includes/images/copy-icon.png"></img>
			</button>

	</div>
</div>

<?

include("includes/footer.php");

?>