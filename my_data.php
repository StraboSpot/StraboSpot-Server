<?
include("logincheck.php");

//print_r($_SESSION);

include("prepare_connections.php");

$credentials = $_SESSION['credentials'];

$projectrows = $neodb->get_results("match (p:Project {userpkey:$userpkey}) optional match (p)-[HAS_DATASET]->(d:Dataset) optional match (d)-[HAS_SPOT]->(s:Spot) with p,d,count(s) as count with p,collect ({d:d,count:count}) as d return p,d order by p.modified_timestamp desc;");

$total=0;

$admin_pkeys = array(3,9,500);



//$neodb->dumpVar($projectrows);exit();

include 'includes/header.php';
//get groups based on userpkey
?>

<style type="text/css">
 /* The switch - the box around the slider */
.switch {
  position: relative;
  display: inline-block;
  width: 34px;
  height: 14px;
  border: 1px solid #333;
}

/* Hide default HTML checkbox */
.switch input {display:none;}

/* The slider */
.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #f00;
  -webkit-transition: .4s;
  transition: .4s;
  
}

.slider:before {
  position: absolute;
  content: "";
  height: 6px;
  width: 6px;
  left: 3px;
  bottom: 3px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #79b22e;
}

input:focus + .slider {
  box-shadow: 0 0 1px #79b22e;
}

input:checked + .slider:before {
  -webkit-transform: translateX(20px);
  -ms-transform: translateX(20px);
  transform: translateX(20px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}

.selecty {
	-webkit-appearance: none;
	-moz-appearance: none;
	appearance: none;
	padding: 1px;
}


</style>


<script src='/assets/js/jquery/jquery.min.js'></script>
<script src="/assets/js/featherlight/featherlight.js"></script>

<script type='text/javascript'>

	function  moveDataset(datasetid){
		var e = document.getElementById("dataset"+datasetid);
		var newproject = e.options[e.selectedIndex].value;
	
		if(newproject != "" && newproject != "null"){
			document.location.href = "move_dataset?did="+datasetid+"&pid="+newproject;
		}
	
	}

	function  projectPub(projectid){
		if(document.getElementById('switch_'+projectid).checked){
			//console.log("switch "+projectid+" checked");
			console.log("https://strabospot.org/project_public?projectid="+projectid+"&state=public");
			$.get("/project_public?projectid="+projectid+"&state=public");
		}else{
			//console.log("switch "+projectid+" not checked");
			console.log("https://strabospot.org/project_public?projectid="+projectid+"&state=private");
			$.get("/project_public?projectid="+projectid+"&state=private");
		}
	}

	function doDownload(id){
	
		//var selected = $('#dl-'+id).find(":selected").text();
		var selected = $('#dl-'+id).find(":selected").val();

		$('#dl-'+id).find(":selected").prop('selected', false);
		//window.location='http://www.example.com';
		
		if(selected=="shapefile"){
			//window.location='/dl/shapefile/'+id;
			window.location='/searchdownload?type=shapefile&userpkey=<?=$userpkey?>&dsids='+id;
		}else if(selected=="kml"){
			//window.location='/dl/kml/'+id;
			window.location='/searchdownload?type=kml&userpkey=<?=$userpkey?>&dsids='+id;
		}else if(selected=="xls"){
			//window.location='/dl/xls/'+id;
			window.location='/searchdownload?type=xls&userpkey=<?=$userpkey?>&dsids='+id;
		}else if(selected=="stereonet"){
			//window.location='/dl/stereonet/'+id;
			window.location='/searchdownload?type=stereonet&userpkey=<?=$userpkey?>&dsids='+id;
		}else if(selected=="fieldbook"){
			//window.location='/dl/fieldbook/'+id;
			window.location='/searchdownload?type=fieldbook&userpkey=<?=$userpkey?>&dsids='+id;
			//var win = window.open('/labbook/'+id, '_blank');
			//win.focus();
		}else if(selected=="strat_sections"){
			//window.location='/dl/fieldbook/'+id;
			//window.location='/dataset_strat_sections?userpkey=<?=$userpkey?>&dataset_id='+id;
			window.location='/dataset_strat_sections?dataset_id='+id;
			//var win = window.open('/dataset_strat_sections?userpkey=<?=$userpkey?>&dataset_id='+id, '_blank');
			//win.focus();
		}else if(selected=="fieldbookdev"){
			//window.location='/dl/fieldbook/'+id;
			//window.location='/dataset_strat_sections?userpkey=<?=$userpkey?>&dataset_id='+id;
			window.location='/searchdownload?type=fieldbookdev&userpkey=<?=$userpkey?>&dsids='+id;
			//var win = window.open('/dataset_strat_sections?userpkey=<?=$userpkey?>&dataset_id='+id, '_blank');
			//win.focus();
		}else if(selected=="shapefiledev"){
			//window.location='/dl/fieldbook/'+id;
			//window.location='/dataset_strat_sections?userpkey=<?=$userpkey?>&dataset_id='+id;
			window.location='/searchdownload?type=shapefiledev&userpkey=<?=$userpkey?>&dsids='+id;
			//var win = window.open('/dataset_strat_sections?userpkey=<?=$userpkey?>&dataset_id='+id, '_blank');
			//win.focus();
		}else if(selected=="landing_page"){
			//window.location='/dl/fieldbook/'+id;
			//window.location='/dataset_strat_sections?userpkey=<?=$userpkey?>&dataset_id='+id;
			window.location='/landingpage?dsid='+id;
			//var win = window.open('/dataset_strat_sections?userpkey=<?=$userpkey?>&dataset_id='+id, '_blank');
			//win.focus();
		}else if(selected=="xlsdev"){
			//window.location='/dl/xls/'+id;
			window.location='/searchdownload?type=xlsdev&userpkey=<?=$userpkey?>&dsids='+id;
		}else if(selected=="debug"){
			alert(id);
		}
	}
	
	

</script>

<h2>My Projects: <strong><a href="new_project">+</a></strong></h2>
<div style ="padding-left:0px;padding-top:20px;">
<?

if(count($projectrows)==0){
	?>
		No Projects found. Click <a href="new_project">here</a> to add project.
	<?
}else{


	
	foreach($projectrows as $projectrow){
		
		$pvals = $projectrow->get("p")->values();
		
		if($pvals["public"]){
			$checked = " checked";
		}else{
			$checked = "";
		}
		
		$projectid=$pvals["id"];
		$uploaddate = date("F j, Y, g:i a T P", $pvals["uploaddate"]);
		$projectname = $pvals["desc_project_name"];
		
		$drows=$projectrow->get("d");
		
		//$neodb->dumpVAr($drows);exit();
		
		$datasetcount = count($drows);	
	
		?>

		<span>
		
		<h3 style="font-size:1.7em;"><?=$projectname?></h3>
		
		<div align="left"><h3>Last Changed: <?=$uploaddate?> &nbsp;&nbsp;&nbsp;
		
		<?
		$link = "https://app.strabospot.org/indexWeb.html#/app/manage-project?credentials=".$credentials."&projectid=".$projectid."&r=".rand(111111111,999999999);
		?>

		<a href="<?=$link?>" onclick="window.open('<?=$link?>','_blank', 'width=800,height=600,menubar=yes,toolbar=yes'); return false;">View/Edit/Add Data</a>
		
		<?
		/*
		<a href="https://app.strabospot.org/indexWeb.html#/app/manage-project?credentials=<?=$credentials?>&projectid=<?=$projectid?>&r=<?=rand(111111111,999999999)?>" >View/Edit/Add Data</a>&nbsp;|&nbsp;
		*/
		?>
		
		&nbsp;|&nbsp;<a href="delete_project?id=<?=$projectid?>" OnClick="return confirm('Are you sure you want to delete <?=$projectname?>?')">Delete</a>
		
		<?
		if(in_array($userpkey,$admin_pkeys)){
		?>
		| <a href="debugproject/<?=$projectid?>" target="_blank">Debug</a>
		<?
		}
		?>
	
		| <span style="color:red;">Public?</span> 

		<label class="switch"><input type="checkbox" name="switch_<?=$projectid?>" id="switch_<?=$projectid?>" onclick="projectPub(<?=$projectid?>)"<?=$checked?>><div class="slider"></div></label>
		<!--<label class="switch"><input type="checkbox"><div class="slider round"></div></label>-->

		</h3></div>
		
		</span>
		
		<br>
	
		<?
	
		if($drows[0]["d"]){
	
			
	
		?>
		
			<div class="strabotable" style="margin-left:0px;">

			<table>

			<tr>
				<td>&nbsp;</td>
				<td>Download</td>
				<td>Dataset Name</td>
				<td>Num Features</td>
				<?
				if(count($projectrows)>1){
				?>
				<td>&nbsp;</td>
				<?
				}
				?>
				<td>Upload Date</td>
			</tr>

			<?
			foreach($drows as $d){
			
			//$neodb->dumpVar($d);exit();
			
			$featurecount = $d["count"];

			if($d["d"]){
			
			$dvals = $d["d"]->values();
			
			}

			$id = $dvals["id"];
			$featuretype = ucfirst($dvals["featuretype"]);
			$uploaddate = date("F j, Y, g:i a T P", $dvals["datecreated"]);
			//$folder = $row->row[0]->folder;
			//$shapefile = $row->row[0]->shapefile;
			$name = $dvals["name"];


			?>



			<tr>
				<td style="width:100px;">
					<?
					/*
					if($featurecount > 0){
						$total=$total+$featurecount;
					?>
					<a href="view_dataset?id=<?=$id?>">View</a>
					<?
					}else{
					?>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<?
					}
					*/
					?>
					&nbsp;&nbsp;&nbsp;
					<a href="delete_dataset?id=<?=$id?>" OnClick="return confirm('Are you sure you want to delete <?=$name?>?')">Delete</a>
					&nbsp;
					&nbsp;
					<a href="edit_dataset?id=<?=$id?>" >Edit</a>
				</td>
				<td style = "white-space: nowrap;">
					<?
					if($featurecount > 0){
						
					?>
					<!--
					<a href="exportshapefile?id=<?=$id?>">Shapefile</a>&nbsp;&nbsp;&nbsp;
					<a href="exportkml?id=<?=$id?>">KML</a>&nbsp;&nbsp;&nbsp;
					<a href="datasetxls?id=<?=$id?>">XLS</a>&nbsp;&nbsp;&nbsp;
					<a href="stereonetoutput/<?=$id?>">Stereonet</a>&nbsp;<img width="15" height="15" src="includes/images/questionmark.png" onClick="$.featherlight('https://strabospot.org/stereonetdoc');" />
					-->
					
					Download:
					<select class="selecty" id="dl-<?=$id?>" onChange="doDownload(<?=$id?>);">
						<option value="">Select...</option>
						<option value="shapefile">Shapefile</option>
						<option value="kml">KMZ</option>
						<option value="xls">XLS</option>
						<option value="stereonet">Stereonet Mobile</option>
						<option value="fieldbook">Field Book</option>
						<option value="strat_sections">Strat Section(s)</option>
						<option value="landing_page">Landing Page</option>
						<?
						if($userpkey==3 || $userpkey == 21004444){
						?>
						<option value="fieldbookdev">Field Book Dev</option>
						<option value="shapefiledev">Shapefile Dev</option>
						<option value="xlsdev">XLS Dev</option>
						<?
						}
						?>
						<!--<option value="debug">Debug</option>-->
					</select>

					<?
					}else{
					?>
					No Spots.
					<?
					}
					?>
				</td>
				<td>
					<?=$name?>
				</td>
				<td>
					<?=$featurecount?>
				</td>
				<?
				if(count($projectrows)>1){
				?>
				<td>
				<select id="dataset<?=$id?>" onchange="moveDataset(<?=$id?>)" style ="-webkit-appearance: none; -moz-appearance: none; appearance: none; padding: 1px;">
					<option value="">Move to...
					<?

					foreach($projectrows as $pr){
		
						$pvals = $pr->get("p")->values();
						$thisprojectid=$pvals["id"];
						$thisprojectname = $pvals["desc_project_name"];
						
						if($thisprojectid!=$projectid){
						?>
						<option value="<?=$thisprojectid?>"><?=$thisprojectname?>
						<?
						}
					}

					?>
				</select>
				</td>
				<?
				}
				?>
				<td>
					<?=$uploaddate?>
				</td>
			</tr>

			<?
			}
			?>

			</table>

			</div><br><br>
		<?

		}else{
	
		?>
		<div style="padding-left:30px;"><h3>No datasets exist in this project. </h3></div>
		<?
	
		}
	

	
	}
	


}// end if count projectrows
?>

</div>

<!--
<?=$total?>
-->



<?
//echo "userpkey: $userpkey";
include 'includes/footer.php';
?>