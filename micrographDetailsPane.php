<?php
/**
 * File: micrographDetailsPane.php
 * Description: Displays detailed micrograph information and metadata
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

SESSION_START();
include("prepare_connections.php");
include 'microdb/microLandingClass.php';

function generateRandomString($length = 10) {
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, $charactersLength - 1)];
	}
	return $randomString;
}

$type = $_GET['type'] ?? '';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$pkey = isset($_GET['pkey']) ? (int)$_GET['pkey'] : 0;

$row = $db->get_row_prepared("SELECT * FROM micro_projectmetadata WHERE id = $1 AND (ispublic OR userpkey=$2)", array($pkey, $userpkey));

if($row->id == ""){
	echo "Error! Project not found.";
	exit();
}

$json = $row->projectjson;
$json = json_decode($json);
$json->pkey = $pkey;
$ml = new MicroLanding($json);

if($type == "spot"){

	foreach($json->datasets as $d){
		foreach($d->samples as $s){
			foreach($s->micrographs as $m){
				foreach($m->spots as $spot){
					if($spot->id == $id){
						$sampleData = $s;
						$outData = $spot;
						$header = "".$spot->name;
					}
				}
			}
		}
	}
}else{
	foreach($json->datasets as $d){
		foreach($d->samples as $s){
			foreach($s->micrographs as $m){
				if($m->id == $id){
					$sampleData = $s;
					$outData = $m;
					$header = "".$m->name;
				}
			}
		}
	}
}

?>
<!DOCTYPE html>
<html>
<head>

</head>
<body>
<link rel='stylesheet' type='text/css' href='/assets/files/main_style.css%3F1422559220.css' title='wsite-theme-css' />
<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,700,400italic,700italic&amp;subset=latin,latin-ext' rel='stylesheet' type='text/css' />
<link href='https://fonts.googleapis.com/css?family=Alice&amp;subset=latin,latin-ext' rel='stylesheet' type='text/css' />
<link href='https://fonts.googleapis.com/css?family=Raleway:400,300,200,700&amp;subset=latin,latin-ext' rel='stylesheet' type='text/css' />
<link rel="stylesheet" href="/assets/js/featherlight/featherlight.css" type="text/css">
<link rel="stylesheet" href="/assets/js/micro-ui/jquery-ui.css">
<script src='/assets/js/jquery/jquery.min.js'></script>
<script src="/assets/js/micro-ui/jquery-ui.js"></script>
<div style="width:295px;height:800px;">
	<div style="text-align:center;font-weight:bold;"><?php echo $header?></div>
	<div id="accordion">
		<?php $ml->showSideDetails($type, $outData, $sampleData, null, $json); ?>
	</div>
</div>
<script>
	jQuery( function() {
		jQuery( "#accordion" ).accordion({
			active: false,
			collapsible: true,
			heightStye: "fill"
		});
	} );
</script>
</body>
</html>