





function doCancelDeleteModal() {
	let modal = document.getElementById("deleteModal");
	modal.style.display = "none";
}


function doOpenDeleteModal(uuid, projectName) {
	let modal = document.getElementById("deleteModal");

	console.log("uuid: " + uuid);
	console.log("projectName: " + projectName);

	document.getElementById("deleteTitle").innerHTML = "Delete Project:<br>" + projectName;

	document.getElementById("deleteUUID").value = uuid;

	let randNum = Math.floor(Math.random()*90000) + 10000;

	document.getElementById("randImage").src = "/doi/ri_"+randNum+".jpg";
	document.getElementById("staticRandTextBox").value = randNum;

	document.getElementById("deleteProjectButton").setAttribute('onclick','doDeleteProjectFromServer(\''+uuid+'\')');

	modal.style.display = "inline";
}

function doDeleteProjectFromServer(uuid){
	let staticRand = document.getElementById("staticRandTextBox").value;
	let providedRand = document.getElementById("randTextBox").value;

	if(staticRand == providedRand){




		jQuery.ajax({
			url : "/delete_doi?u=" + uuid,
			type: "GET",
			processData: false,
			contentType: false,
			success:function(data){
				if(data.Error){
					alert("Error!\n" + data.Error);
				}else{
					console.log(data);
					window.location.href = "/my_dois";
				}
			},
			error: function(){
				//if fails
			}
		});



	}else{
		alert("Invalid code entered.\nPlease try again.");
	}
}


/*
		jQuery.ajax({
			url : "<?=$url?>?p=<?=$projectid?>",
			type: "GET",
			processData: false,
			contentType: false,
			success:function(data){
				if(data.Error){
					alert("Error!\n" + data.Error);
				}else{
					console.log(data);
					let newloc = 'edit_doi?u='+data.uuid+'&n=1';
					window.location.href = newloc;
				}
			},
			error: function(){
				//if fails
			}
		});
*/

