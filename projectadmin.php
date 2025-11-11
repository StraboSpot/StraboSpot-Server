<?php
/**
 * File: projectadmin.php
 * Description: Project Admin
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

include("logincheck.php");
include("prepare_connections.php");

if(!in_array($userpkey, array(3,9,905,2272,4,342))) exit("Not found.");

include "includes/header.php";

?>
<script src='/assets/js/jquery/jquery.min.js'></script>
<script>

	function dosearch() {
		let searchterm = document.getElementById("searchstring").value;
		if(searchterm.length > 2){
			console.log(searchterm);
			$.ajax({
				url : "projectadmingetusers?s="+searchterm,
				type: "GET",
				processData: false,
				contentType: false,
				success:function(data){
					if(data.Error){
						alert("Error!\n" + data.Error);
					}else{
						clearEmailResults();
						if(data.length > 0){
							document.getElementById("emailresults").style.display="inline";
							document.getElementById("notfound").style.display="none";
							for(let x=0; x<data.length; x++){
								let row = data[x];

								console.log(row);

								let html = "";
								if(row.projectcount > 0){
									html = '<tr><td style="text-align:center;width:100px;" nowrap=""><a href="#" onclick="getProjects('+row.pkey+',\''+row.name+'\')">Get Projects</a></td><td nowrap>'+row.name+'</td><td nowrap="">'+row.email+'</td><td nowrap="">'+row.projectcount+'</td></tr>';
								}else{
									html = '<tr><td style="text-align:center;width:100px;" nowrap="">&nbsp;</td><td nowrap="">'+row.name+'</td><td>'+row.email+'</td><td nowrap>'+row.projectcount+'</td></tr>';
								}
								let tab = document.getElementById("emailresultsbody");
								tab.innerHTML+=html;
							}
						}else{
							document.getElementById("emailresults").style.display="none";
							document.getElementById("notfound").style.display="block";
						}
					}
				},
				error: function(){
					//if fails
					console.log("failed.");
				}
			});
		}else{
			document.getElementById("emailresults").style.display="none";
			document.getElementById("notfound").style.display="none";
		}
	}

	function clearEmailResults() {
		let rows = document.getElementById("emailresultsbody").children;
		let rowcount = rows.length - 1;

		for(let x=0; x<rowcount; x++){
			rows[1].remove();
		}

	}

	function clearProjectResults() {
		let rows = document.getElementById("projectresultsbody").children;
		let rowcount = rows.length - 1;

		for(let x=0; x<rowcount; x++){
			rows[1].remove();
		}
	}

	function doAdd() {
		let tab = document.getElementById("emailresultsbody");
		let html = '<tr><td style="text-align:center;width:100px;" nowrap=""><a href="#" onclick="getProjects(3);">Get Projects</a></td><td nowrap="">John Smith</td><td nowrap="">john@gmail.com</td><td nowrap="">33</td></tr>';
		tab.innerHTML+=html;
	}

	function downloadProject(projectid, userpkey){
		window.open("/projectadmindownloadfieldproject?p="+projectid+"&u="+userpkey);
	}

	function getProjects(userpkey, name){

		$.ajax({
			url : "projectadmingetprojects?u="+userpkey,
			type: "GET",
			processData: false,
			contentType: false,
			success:function(data){
				if(data.Error){
					alert("Error!\n" + data.Error);
				}else{
					clearProjectResults();
					if(data.length > 0){

						document.getElementById("searchwrapper").style.display="none";
						clearProjectResults();
						document.getElementById("projectswrapper").style.display="inline";
						document.getElementById("projectstitle").innerHTML = "Projects for "+name+":";

						for(let x=0; x<data.length; x++){
							let row = data[x];

							console.log(row);

							let html = "";
							html = '<tr><td style="text-align:center;width:100px;" nowrap=""><a href="#" onclick="downloadProject('+row.id+','+userpkey+')">Download</a></td><td nowrap>'+row.name+'</td><td nowrap="">'+row.datasetcount+'</td><td nowrap="">'+row.modified_timestamp+'</td></tr>';

							let tab = document.getElementById("projectresultsbody");
							tab.innerHTML+=html;
						}
					}else{
						document.getElementById("emailresults").style.display="none";
						document.getElementById("notfound").style.display="block";
					}
				}
			},
			error: function(){
				//if fails
				console.log("failed.");
			}
		});

	}

	function backInterface(){
		document.getElementById("searchwrapper").style.display="inline";
		document.getElementById("projectswrapper").style.display="none";
	}

	function checkSubmit(e) {

	   console.log("here");

	   if(e && e.keyCode == 13) {
		  dosearch();
	   }

	   let searchterm = document.getElementById("searchstring").value;
	   if(searchterm.length == 0) document.getElementById("emailresults").style.display="none";
	}

</script>
<style type='text/css'>
	.searchbox {
		color:#333333;
		font-weight:normal;
		font-family: 'Raleway', sans-serif;
		font-size:18px;
		padding: 3px;
	}

	.sbutt {
		display: inline-block;
		border-radius: 4px;
		background-color: #f4511e;
		border: none;
		color: #FFFFFF;
		text-align: center;
		font-size: 18px;
		padding: 5px;
		width: 100px;
		transition: all 0.5s;
		cursor: pointer;
		margin: 5px;
	}
</style>

<h2 style="text-align:center;">Project Admin</h2>

<div id="searchwrapper">
	<input type="text" class="searchbox" id="searchstring" onKeyUp="checkSubmit(event);" placeholder="Name or Email..."> <input type="submit" value="Search" class="sbutt" onclick="dosearch();">

	<div id="notfound" style="margin-top:20px;display:none;">No results found.</div>

	<div id="emailresults" style="margin-top:20px;display:none;">

		<div class="strabotable" style="margin-left:0px;margin-top:3px;">
			<table>

				<tbody id="emailresultsbody">
					<tr>
						<td>&nbsp;</td>
						<td>Name</td>
						<td>Email</td>
						<td>Num Projects</td>
					</tr>

					<tr><td style="text-align:center;width:100px;" nowrap=""><a href="#" onclick="getProjects(3);">Get Projects</a></td><td nowrap="">John Smith</td><td nowrap="">john@gmail.com</td><td nowrap="">33</td></tr>

					<tr>
						<td style="text-align:center;width:100px;" nowrap="">
							<a href="#" onclick="getProjects(3);">Get Projects</a>
						</td>
						<td nowrap="">John Smith</td>
						<td nowrap="">john@gmail.com</td>
						<td nowrap="">33</td>
					</tr>

					<tr>
						<td style="text-align:center;width:100px;" nowrap="">
							<a href="#" onclick="getProjects(3);">Get Projects</a>
						</td>
						<td nowrap="">John Smith</td>
						<td nowrap="">john@gmail.com</td>
						<td nowrap="">33</td>
					</tr>

				</tbody>
			</table>
		</div>

	</div>

</div>

<div id="projectswrapper" style="display:none;">
	<input type="submit" value="Back" class="sbutt" onclick="backInterface();">

	<h2 id="projectstitle">Projects for John Smith:</h2>

	<div id="projectresults" style="margin-top:5px;">

		<div class="strabotable" style="margin-left:0px;margin-top:3px;">
			<table>

				<tbody id="projectresultsbody">
					<tr>
						<td>&nbsp;</td>
						<td>Project Name</td>
						<td>Num Datasets</td>
						<td>Date Modified</td>
					</tr>

					<tr><td style="text-align:center;width:100px;" nowrap=""><a href="#" onclick="getProjects(3);">Get Projects</a></td><td nowrap="">John Smith</td><td nowrap="">john@gmail.com</td><td nowrap="">33</td></tr>

					<tr>
						<td style="text-align:center;width:100px;" nowrap="">
							<a href="#" onclick="getProjects(3);">Get Projects</a>
						</td>
						<td nowrap="">John Smith</td>
						<td nowrap="">john@gmail.com</td>
						<td nowrap="">33</td>
					</tr>

					<tr>
						<td style="text-align:center;width:100px;" nowrap="">
							<a href="#" onclick="getProjects(3);">Get Projects</a>
						</td>
						<td nowrap="">John Smith</td>
						<td nowrap="">john@gmail.com</td>
						<td nowrap="">33</td>
					</tr>

				</tbody>
			</table>
		</div>

	</div>

</div>

<div style="padding-top:100px">&nbsp;</div>

<?php
include "includes/footer.php";
?>