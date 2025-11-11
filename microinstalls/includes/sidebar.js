	let rownum=0;
	let ownerSearchVals=[];

/*
	let ownerAutoCompleteOptions = {
	url: function (phrase) {
		return "ownersearch.php?term=" + phrase;
	},

	getValue: "label",

	list: {
		match: {
			enabled: true
		},
		maxNumberOfElements: 10,
		onChooseEvent: function() {
			console.log($("#ownerSearch_0").getSelectedItemData().id);
		}
	},

	theme: "round"
};
*/

	let sampleIDAutoCompleteOptions = {
	url: function (phrase) {
		return 'sampleidsearch.php?term=' + phrase;
	},

	getValue: 'label',

	list: {
		match: {
			enabled: true
		},
		maxNumberOfElements: 10,
		onChooseEvent: function() {
			console.log("Item Chosen!");
			getSearchCount();
		}
	},

	theme: "round"
};

function addRow() {
	$( "#rowContainer" ).append( '			<div class="itemRow" id="itemRow_' + rownum + '">\
	<div class="andOrColumn" id="andOrColumn_' + rownum + '">\
		<select class="searchSelect" id="andOr_' + rownum + '" onchange="getSearchCount()">\
			<option value="and">And</option>\
			<option value="or">Or</option>\
			<option value="not">Not</option>\
		</select>\
	</div>\
	<div class="typeColumn" id="typeColumn_' + rownum + '">\
		<select class="searchSelect" id="typeColSel_' + rownum + '" onchange="updateSearchRow(' + rownum + ')">\
			<option value="">select...</option>\
			<option value="Date Collected">Date Collected</option>\
			<option value="Image Type">Image Type</option>\
			<option value="Keywords">Keywords</option>\
			<option value="Metamorphic Facies">Metamorphic Facies</option>\
			<option value="Microstructure">Microstructure</option>\
			<!--<option value="Mineral">Minerals</option>-->\
			<option value="Orientation">Orientation</option>\
			<option value="Owner">Owner</option>\
			<option value="Rock Type">Rock Type</option>\
			<option value="Sample">Sample</option>\
			<option value="Sample ID">Sample ID</option>\
			<option value="Strat Column">Strat Column</option>\
			<option value="Tectonic Province">Tectonic Province</option>\
		</select>\
	</div>\
	<div class="resultColumn" id="resultColumn_' + rownum + '">&nbsp;</div>\
	<div class="addRowColumn" id="addRowCol_' + rownum + '"></div>\
	<div class="clearColumn"></div>\
</div>' );
	let looknum = rownum-1;
	$("#addRowCol_"+ looknum).html('<input type="button" class="minusButton" value="-" onclick="removeRow(' + looknum + ')">');
	rownum++;
	removeLowestAndSwitch();
}

function removeRow(rownum){
	//clear owner value for this row
	ownerSearchVals[rownum] = null;
	$("#itemRow_" + rownum).remove();
	removeLowestAndSwitch();
	getSearchCount();
}

function removeLowestAndSwitch(){
	let smallestNum = 200;
	for(i = 200; i >= 0; i--){
		if ($("#itemRow_" + i).length ){ smallestNum = i; }
	}
	console.log("smallest: " + smallestNum);
	$("#andOrColumn_" + smallestNum).html("&nbsp;");
}

function updateSearchRow(rownum){

	//clear owner value for this row
	ownerSearchVals[rownum] = null;

	let valueOfSelect = $("#typeColSel_" + rownum).val();

	$("#addRowCol_"+ rownum).html('<input type="button" class="plusButton" value="+" onclick="addRow()">');

	if(valueOfSelect == ""){
		//clear box
		$("#resultColumn_" + rownum).html("&nbsp;");

	}else if(valueOfSelect == "Date Collected"){

		$("#resultColumn_" + rownum).html("");

		$("#resultColumn_" + rownum).append('<Label class="yearLabel" for="minYearSearch_' + rownum + '">Min Year: </Label>');
	let newResult = $("<input></input>");
		newResult.attr("id", 'minYearSearch_' + rownum);
		newResult.attr("class", 'searchText');
		newResult.attr("size", '2');
		$("#resultColumn_" + rownum).append(newResult);
		$( "#minYearSearch_" + rownum ).keyup($.debounce(350, function(e) {
	let thisElementName = e.srcElement.attributes.id.nodeValue;
			if($("#"+thisElementName).val().length == 4){
	let thisElementVal = parseInt($("#"+thisElementName).val());
				if(thisElementVal >= 1111 && thisElementVal <= 9999){
					getSearchCount();
				}
			}
		}));

		$("#resultColumn_" + rownum).append(' <Label class="yearLabel" for="maxYearSearch_' + rownum + '">Max Year: </Label>');
	let newResult = $("<input></input>");
		newResult.attr("id", 'maxYearSearch_' + rownum);
		newResult.attr("class", 'searchText');
		newResult.attr("size", '2');
		$("#resultColumn_" + rownum).append(newResult);
		$( "#maxYearSearch_" + rownum ).keyup($.debounce(350, function(e) {
	let thisElementName = e.srcElement.attributes.id.nodeValue;
			if($("#"+thisElementName).val().length == 4){
	let thisElementVal = parseInt($("#"+thisElementName).val());
				if(thisElementVal >= 1111 && thisElementVal <= 9999){
					getSearchCount();
				}
			}
		}));

	}else if(valueOfSelect == "Image Type"){

		$("#resultColumn_" + rownum).html("");
	let newResult = $('<Select class="searchSelect">\
						<option value="">Select...</option>\
						<option value="photo">Photo</option>\
						<option value="sketch">Sketch</option>\
						<option value="geological_cs">Geological Cross Section</option>\
						<option value="geophysical_cs">Geophysical Cross Section</option>\
						<option value="strat_section">Stratigraphic Section</option>\
						<option value="micrograph_ref">Micrograph Reference</option>\
						<option value="micrograph">Micrograph</option>\
						<option value="sample">Sample</option>\
						<option value="subsample">Subsample</option>\
						<option value="undef_sample">Undeformed Sample</option>\
						<option value="exp_setup">Experimental Setup</option>\
						<option value="def_sample">Deformed Sample</option>\
						<option value="exp_apparatus">Experimental Apparatus</option>\
						<option value="other_image_ty">Other</option>\
					</Select>');
		newResult.attr("id", 'imageTypeSearch_' + rownum);
		$("#resultColumn_" + rownum).append(newResult);
		$("#imageTypeSearch_" + rownum).change(function() {
			console.log( "Image Type Changed." );
			getSearchCount();
		});

	}else if(valueOfSelect == "Keywords"){

		$("#resultColumn_" + rownum).html("");
	let newResult = $("<input></input>");
		newResult.attr("id", 'keywordSearch_' + rownum);
		newResult.attr("class", 'searchText');
		$("#resultColumn_" + rownum).append(newResult);
		/*
		$( "#keywordSearch_" + rownum ).keyup(function() {
		  console.log( "Handler for .change() called." );
		});
		*/
		$( "#keywordSearch_" + rownum ).keyup($.debounce(350, function(e) {
			console.log("Keyword Changed!");
			getSearchCount();
		}));

	}else if(valueOfSelect == "Metamorphic Facies"){

		$("#resultColumn_" + rownum).html("");
	let newResult = $('<Select class="searchSelect">\
						<option value="">Select...</option>\
						<option value="zeolite_facies">Zeolite Facies</option>\
						<option value="prehnite-pumpe">Prehnite-Pumpellyite Facies</option>\
						<option value="greenschist_fa">Greenschist Facies</option>\
						<option value="amphibolite_fa">Amphibolite Facies</option>\
						<option value="epidote_amphib">Epidote Amphibolite Facies</option>\
						<option value="granulite_faci">Granulite Facies</option>\
						<option value="blueschist_fac">Blueschist Facies</option>\
						<option value="eclogite_facie">Eclogite Facies</option>\
						<option value="hornfels_facie">Hornfels Facies</option>\
						<option value="chlorite_zone">Chlorite Zone</option>\
						<option value="biotite_zone">Biotite Zone</option>\
						<option value="garnet_zone">Garnet Zone</option>\
						<option value="staurolite_zon">Staurolite Zone</option>\
						<option value="staurolite-kya">Staurolite-Kyanite Zone</option>\
						<option value="kyanite_zone">Kyanite Zone</option>\
						<option value="sillimanite_zo">Sillimanite Zone</option>\
						<option value="andalusite_zon">Andalusite Zone</option>\
						<option value="sillimanite-k">Sillimanite-K Feldspar Zone</option>\
						<option value="garnet-cordier">Garnet-Cordierite Zone</option>\
						<option value="migmatite_zone">Migmatite Zone</option>\
						<option value="ultra_high_pre">Ultra High Pressure Facies</option>\
						<option value="ultra_high_tem">Ultra High Temperature Facies</option>\
						<option value="other">Other</option>\
					</Select>');
		newResult.attr("id", 'metFaciesSearch_' + rownum);
		$("#resultColumn_" + rownum).append(newResult);
		$("#metFaciesSearch_" + rownum).change(function() {
			console.log( "Met Facies Changed." );
			getSearchCount();
		});

	}else if(valueOfSelect == "Microstructure"){

		// simple exists
		$("#resultColumn_" + rownum).html("");
		//var newResult = $("<input></input>");
		//newResult.attr("id", 'microstructureExistsSearch_' + rownum);
		//newResult.attr("type", 'checkbox');
		//newResult.prop( "checked", true );
		//$("#resultColumn_" + rownum).append(newResult);
		$("#resultColumn_" + rownum).append(' <Label class="existsLabel">Exists</Label>');
		$("#microstructureExistsSearch_" + rownum).change(function() {
			console.log( "Checkbox Changed." );
			getSearchCount();
		});
		getSearchCount();

	}else if(valueOfSelect == "Mineral"){

		$("#resultColumn_" + rownum).html("List from Basil");

	}else if(valueOfSelect == "Orientation"){

		// simple exists
		$("#resultColumn_" + rownum).html("");
		//var newResult = $("<input></input>");
		//newResult.attr("id", 'orientationExistsSearch_' + rownum);
		//newResult.attr("type", 'checkbox');
		//newResult.prop( "checked", true );
		//$("#resultColumn_" + rownum).append(newResult);
		$("#resultColumn_" + rownum).append(' <Label class="existsLabel">Exists</Label>');
		$("#orientationExistsSearch_" + rownum).change(function() {
			console.log( "Checkbox Changed." );
			getSearchCount();
		});
		getSearchCount();

	}else if(valueOfSelect == "Owner"){



		$("#resultColumn_" + rownum).html("");
	let newResult = $("<input></input>");
		newResult.attr("id", 'ownerSearch_' + rownum);
		newResult.attr("placeholder", "Smith, John...");
		$("#resultColumn_" + rownum).append(newResult);


		eval('var ownerAutoCompleteOptions = {\
			url: function (phrase) {\
				return "ownersearch.php?term=" + phrase;\
			},\
			\
			getValue: "label",\
			\
			list: {\
				match: {\
					enabled: true\
				},\
				maxNumberOfElements: 10,\
				onChooseEvent: function() {\
					console.log($("#ownerSearch_0").getSelectedItemData().id);\
					console.log("rownum: "+' + rownum + ');\
					ownerSearchVals[' + rownum + '] = $("#ownerSearch_' + rownum + '").getSelectedItemData().id;\
					getSearchCount();\
				}	\
			},\
			\
			theme: "round"\
		};');


		$('#ownerSearch_' + rownum).easyAutocomplete(ownerAutoCompleteOptions);


		/*
		$("#resultColumn_" + rownum).html("");
	let newResult = $("<input></input>");
		newResult.attr("id", 'ownerSearch_' + rownum);
		newResult.attr("placeholder", "Smith, John...");
		$("#resultColumn_" + rownum).append(newResult);
		$('#ownerSearch_' + rownum).autocomplete({
			source: "ownersearch.php",
			minLength: 2,
			select: function( event, ui ) {
				console.log( "Selected: " + ui.item.value + " aka " + ui.item.id );
			}
		});
		*/

	}else if(valueOfSelect == "Rock Type"){

		$("#resultColumn_" + rownum).html("");
	let newResult = $('<Select class="searchSelect">\
						<option value="">Select...</option>\
						<option value="igneous:hypabyssal:diabase">Igneous:Hypabyssal:Diabase</option>\
						<option value="igneous:hypabyssal:microdiorite">Igneous:Hypabyssal:Microdiorite</option>\
						<option value="igneous:hypabyssal:microgranite">Igneous:Hypabyssal:Microgranite</option>\
						<option value="igneous:hypabyssal:microgranodiorite">Igneous:Hypabyssal:Microgranodiorite</option>\
						<option value="igneous:plutonic:alkali feldspar granite">Igneous:Plutonic:Alkali Feldspar Granite</option>\
						<option value="igneous:plutonic:anorthosite">Igneous:Plutonic:Anorthosite</option>\
						<option value="igneous:plutonic:diorite">Igneous:Plutonic:Diorite</option>\
						<option value="igneous:plutonic:gabbro">Igneous:Plutonic:Gabbro</option>\
						<option value="igneous:plutonic:granite">Igneous:Plutonic:Granite</option>\
						<option value="igneous:plutonic:granodiorite">Igneous:Plutonic:Granodiorite</option>\
						<option value="igneous:plutonic:monzonite">Igneous:Plutonic:Monzonite</option>\
						<option value="igneous:plutonic:other">Igneous:Plutonic:Other</option>\
						<option value="igneous:plutonic:quartz monzonite">Igneous:Plutonic:Quartz Monzonite</option>\
						<option value="igneous:plutonic:syenite">Igneous:Plutonic:Syenite</option>\
						<option value="igneous:plutonic:tonalite">Igneous:Plutonic:Tonalite</option>\
						<option value="igneous:volcanic:andesite">Igneous:Volcanic:Andesite</option>\
						<option value="igneous:volcanic:ash-fall tuff">Igneous:Volcanic:Ash-Fall Tuff</option>\
						<option value="igneous:volcanic:ash-flow tuff">Igneous:Volcanic:Ash-Flow Tuff</option>\
						<option value="igneous:volcanic:basalt">Igneous:Volcanic:Basalt</option>\
						<option value="igneous:volcanic:basaltic-andesite">Igneous:Volcanic:Basaltic-Andesite</option>\
						<option value="igneous:volcanic:dacite">Igneous:Volcanic:Dacite</option>\
						<option value="igneous:volcanic:komatiite">Igneous:Volcanic:Komatiite</option>\
						<option value="igneous:volcanic:lapilli tuff">Igneous:Volcanic:Lapilli Tuff</option>\
						<option value="igneous:volcanic:latite">Igneous:Volcanic:Latite</option>\
						<option value="igneous:volcanic:other">Igneous:Volcanic:Other</option>\
						<option value="igneous:volcanic:pumice">Igneous:Volcanic:Pumice</option>\
						<option value="igneous:volcanic:rhyolite">Igneous:Volcanic:Rhyolite</option>\
						<option value="igneous:volcanic:trachyandesite">Igneous:Volcanic:Trachyandesite</option>\
						<option value="igneous:volcanic:trachyte">Igneous:Volcanic:Trachyte</option>\
						<option value="igneous:volcanic:tuff">Igneous:Volcanic:Tuff</option>\
						<option value="igneous:volcanic:tuff breccia">Igneous:Volcanic:Tuff Breccia</option>\
						<option value="igneous:volcanic:vitrophyre">Igneous:Volcanic:Vitrophyre</option>\
						<option value="igneous:volcanic:volcanic glass">Igneous:Volcanic:Volcanic Glass</option>\
						<option value="metamorphic:amphibolite">Metamorphic:Amphibolite</option>\
						<option value="metamorphic:blackwall">Metamorphic:Blackwall</option>\
						<option value="metamorphic:blueschist">Metamorphic:Blueschist</option>\
						<option value="metamorphic:calc-schist">Metamorphic:Calc-Schist</option>\
						<option value="metamorphic:calc-silicate">Metamorphic:Calc-Silicate</option>\
						<option value="metamorphic:cataclasite">Metamorphic:Cataclasite</option>\
						<option value="metamorphic:cordierite-anthophyllite">Metamorphic:Cordierite-Anthophyllite</option>\
						<option value="metamorphic:eclogite">Metamorphic:Eclogite</option>\
						<option value="metamorphic:garnetite">Metamorphic:Garnetite</option>\
						<option value="metamorphic:glaucophanite">Metamorphic:Glaucophanite</option>\
						<option value="metamorphic:gneiss">Metamorphic:Gneiss</option>\
						<option value="metamorphic:granofels">Metamorphic:Granofels</option>\
						<option value="metamorphic:greenschist">Metamorphic:Greenschist</option>\
						<option value="metamorphic:hornfels">Metamorphic:Hornfels</option>\
						<option value="metamorphic:jadeite">Metamorphic:Jadeite</option>\
						<option value="metamorphic:marble">Metamorphic:Marble</option>\
						<option value="metamorphic:meta-arkose">Metamorphic:Meta-Arkose</option>\
						<option value="metamorphic:meta-iron formation">Metamorphic:Meta-Iron Formation</option>\
						<option value="metamorphic:meta-ultramafic">Metamorphic:Meta-Ultramafic</option>\
						<option value="metamorphic:metabasite">Metamorphic:Metabasite</option>\
						<option value="metamorphic:metacarbonate">Metamorphic:Metacarbonate</option>\
						<option value="metamorphic:metagranite">Metamorphic:Metagranite</option>\
						<option value="metamorphic:metagraywacke">Metamorphic:Metagraywacke</option>\
						<option value="metamorphic:metaigneous">Metamorphic:Metaigneous</option>\
						<option value="metamorphic:metapelite">Metamorphic:Metapelite</option>\
						<option value="metamorphic:metasomatite">Metamorphic:Metasomatite</option>\
						<option value="metamorphic:metavolcanic">Metamorphic:Metavolcanic</option>\
						<option value="metamorphic:migmatite">Metamorphic:Migmatite</option>\
						<option value="metamorphic:mylonite">Metamorphic:Mylonite</option>\
						<option value="metamorphic:other">Metamorphic:Other</option>\
						<option value="metamorphic:phyllite">Metamorphic:Phyllite</option>\
						<option value="metamorphic:pyroxenite">Metamorphic:Pyroxenite</option>\
						<option value="metamorphic:quartzite">Metamorphic:Quartzite</option>\
						<option value="metamorphic:schist">Metamorphic:Schist</option>\
						<option value="metamorphic:serpentenite">Metamorphic:Serpentenite</option>\
						<option value="metamorphic:skarn">Metamorphic:Skarn</option>\
						<option value="metamorphic:slate">Metamorphic:Slate</option>\
						<option value="sediment:alluvium">Sediment:Alluvium</option>\
						<option value="sediment:breccia">Sediment:Breccia</option>\
						<option value="sediment:clay">Sediment:Clay</option>\
						<option value="sediment:colluvium">Sediment:Colluvium</option>\
						<option value="sediment:eolian">Sediment:Eolian</option>\
						<option value="sediment:gravel">Sediment:Gravel</option>\
						<option value="sediment:lacustrine">Sediment:Lacustrine</option>\
						<option value="sediment:loess">Sediment:Loess</option>\
						<option value="sediment:moraine">Sediment:Moraine</option>\
						<option value="sediment:older alluvium">Sediment:Older Alluvium</option>\
						<option value="sediment:other">Sediment:Other</option>\
						<option value="sediment:sand">Sediment:Sand</option>\
						<option value="sediment:silt">Sediment:Silt</option>\
						<option value="sediment:talus">Sediment:Talus</option>\
						<option value="sediment:till">Sediment:Till</option>\
						<option value="sedimentary:breccia">Sedimentary:Breccia</option>\
						<option value="sedimentary:chert">Sedimentary:Chert</option>\
						<option value="sedimentary:claystone">Sedimentary:Claystone</option>\
						<option value="sedimentary:coal">Sedimentary:Coal</option>\
						<option value="sedimentary:conglomerate">Sedimentary:Conglomerate</option>\
						<option value="sedimentary:dolostone">Sedimentary:Dolostone</option>\
						<option value="sedimentary:evaporite">Sedimentary:Evaporite</option>\
						<option value="sedimentary:limestone">Sedimentary:Limestone</option>\
						<option value="sedimentary:mudstone">Sedimentary:Mudstone</option>\
						<option value="sedimentary:other">Sedimentary:Other</option>\
						<option value="sedimentary:sandstone">Sedimentary:Sandstone</option>\
						<option value="sedimentary:shale">Sedimentary:Shale</option>\
						<option value="sedimentary:siltstone">Sedimentary:Siltstone</option>\
						<option value="sedimentary:travertine">Sedimentary:Travertine</option>\
					</Select>');
		newResult.attr("id", 'rockTypeSearch_' + rownum);
		$("#resultColumn_" + rownum).append(newResult);
		$("#rockTypeSearch_" + rownum).change(function() {
			console.log( "Rock Type Changed." );
			getSearchCount();
		});

	}else if(valueOfSelect == "Sample"){

		// simple exists
		$("#resultColumn_" + rownum).html("");
		//var newResult = $("<input></input>");
		//newResult.attr("id", 'sampleExistsSearch_' + rownum);
		//newResult.attr("type", 'checkbox');
		//newResult.prop( "checked", true );
		//$("#resultColumn_" + rownum).append(newResult);
		$("#resultColumn_" + rownum).append(' <Label class="existsLabel">Exists</Label>');
		$("#sampleExistsSearch_" + rownum).change(function() {
			console.log( "Checkbox Changed." );
			getSearchCount();
		});
		getSearchCount();

	}else if(valueOfSelect == "Sample ID"){

		$("#resultColumn_" + rownum).html("");
	let newResult = $("<input></input>");
		newResult.attr("id", 'sampleIDSearch_' + rownum);
		newResult.attr("placeholder", "ABC123...");
		$("#resultColumn_" + rownum).append(newResult);
		$('#sampleIDSearch_' + rownum).easyAutocomplete(sampleIDAutoCompleteOptions);

	}else if(valueOfSelect == "Strat Column"){

		// simple exists
		$("#resultColumn_" + rownum).html("");
		//var newResult = $("<input></input>");
		//newResult.attr("id", 'stratColumnExistsSearch_' + rownum);
		//newResult.attr("type", 'checkbox');
		//newResult.prop( "checked", true );
		//$("#resultColumn_" + rownum).append(newResult);
		$("#resultColumn_" + rownum).append(' <Label class="existsLabel">Exists</Label>');
		$("#stratColumnExistsSearch_" + rownum).change(function() {
			console.log( "Checkbox Changed." );
			getSearchCount();
		});
		getSearchCount();

	}else if(valueOfSelect == "Tectonic Province"){

		$("#resultColumn_" + rownum).html("");
	let newResult = $('<Select class="searchSelect">\
						<option value="">Select...</option>\
						<option value="470">Aaiun-Tarfaya Basin</option>\
						<option value="475">Abu Gharadiq Basin</option>\
						<option value="742">Acre Basin</option>\
						<option value="315">Adana/Sivas</option>\
						<option value="863">Adelaide & Kanmantoo Fold Belts</option>\
						<option value="246">Adirondack Uplift</option>\
						<option value="954">Admiralty Arch</option>\
						<option value="274">Adriatic Basin</option>\
						<option value="305">Aegean</option>\
						<option value="427">Afghan</option>\
						<option value="340">Afghan-Tajik Basin</option>\
						<option value="331">Akita Basin</option>\
						<option value="214">Alakol Basin</option>\
						<option value="266">Alashan Yinshan Fold Belt</option>\
						<option value="333">Alay Basin</option>\
						<option value="866">Albany-Fraser Province</option>\
						<option value="56">Alberta Basin</option>\
						<option value="354">Alborz Fold Belt</option>\
						<option value="350">Albuquerque-Santa Fe Rift</option>\
						<option value="82">Aldan Shield</option>\
						<option value="58">Aldan Uplift</option>\
						<option value="352">Alentejo-Guadalquivir Basin</option>\
						<option value="985">Aleutian Arc</option>\
						<option value="984">Aleutian Arc OCS</option>\
						<option value="228">Algonquin Arch-Michigan Basin</option>\
						<option value="203">Alps</option>\
						<option value="98">Altay-Sayan Folded Region</option>\
						<option value="798">Altiplano Basin</option>\
						<option value="318">Altunshan Fold Belt</option>\
						<option value="840">Amadeus Basin</option>\
						<option value="706">Amazonas Basin</option>\
						<option value="609">Amhara Plateau</option>\
						<option value="304">Amu-Darya Basin</option>\
						<option value="919">Amundsen/Bellingshausen Offshore</option>\
						<option value="7">Anabar Basin</option>\
						<option value="10">Anabar-Olenek High</option>\
						<option value="977">Anadyr Basin</option>\
						<option value="978">Anadyr Basin</option>\
						<option value="388">Anah Graben</option>\
						<option value="356">Andalucia</option>\
						<option value="341">Andarko Basin</option>\
						<option value="735">Andean Province</option>\
						<option value="93">Angara-Lena Terrace</option>\
						<option value="126">Anglo-Dutch Basin</option>\
						<option value="164">Anglo-Paris Basin</option>\
						<option value="217">Aniva Basin</option>\
						<option value="915">Antarctica Peninsula</option>\
						<option value="162">Anticosti Basin</option>\
						<option value="963">Anzhu Uplift</option>\
						<option value="252">Appalachian Basin</option>\
						<option value="161">Appalachian Foldbelt</option>\
						<option value="289">Apulia Platform</option>\
						<option value="226">Aquitaine Basin</option>\
						<option value="482">Arabian Shield</option>\
						<option value="471">Arabian Shield</option>\
						<option value="615">Arabian Shield</option>\
						<option value="729">Arafura Basin-Irian Jaya</option>\
						<option value="325">Araks</option>\
						<option value="751">Araripe Province</option>\
						<option value="929">Arctic Coastal Shelf</option>\
						<option value="925">Arctic Ocean Slope</option>\
						<option value="383">Arkoma Basin</option>\
						<option value="172">Armoricia</option>\
						<option value="831">Arunta Block</option>\
						<option value="503">Assam</option>\
						<option value="310">Atlantic Coastal Plain</option>\
						<option value="267">Atlantic Mesozoic OCS</option>\
						<option value="434">Atlas Basin</option>\
						<option value="368">Atlas Uplift</option>\
						<option value="763">Australian Arafura Basin</option>\
						<option value="599">Aves Ridge</option>\
						<option value="23">Ayon Basin</option>\
						<option value="205">Azov-Kuban Basin</option>\
						<option value="626">Baffa</option>\
						<option value="962">Baffin Basin</option>\
						<option value="507">Bahama Platform</option>\
						<option value="782">Bahia Sul Basin</option>\
						<option value="74">Baikal-Patom Folded Region</option>\
						<option value="452">Baja California Backbone</option>\
						<option value="488">Baja California Backbone</option>\
						<option value="752">Bali Basin</option>\
						<option value="99">Baltic Depression</option>\
						<option value="22">Baltic Shield-Norwegian Caledonides</option>\
						<option value="455">Baluchistan</option>\
						<option value="722">Banda Arc</option>\
						<option value="838">Bangemall and Nabberu Basins</option>\
						<option value="961">Banks Basin</option>\
						<option value="664">Baram Delta/Brunei-Sabah Basin</option>\
						<option value="945">Barents Continental Slope</option>\
						<option value="660">Barinas-Apure Basin</option>\
						<option value="711">Barito Basin</option>\
						<option value="704">Barreieinas Basin</option>\
						<option value="890">Bass Basin</option>\
						<option value="892">Bassian Rise</option>\
						<option value="994">Bau Waters Basin</option>\
						<option value="57">Baykit Arch</option>\
						<option value="594">Beata Ridge</option>\
						<option value="13">Beaufort Shelf OCS</option>\
						<option value="566">Beibuwan Basin</option>\
						<option value="386">Beirut</option>\
						<option value="825">Bellona Plateau</option>\
						<option value="104">Belorussian-Voronezh High</option>\
						<option value="397">Bend Arch-Fort Worth Basin</option>\
						<option value="793">Beni Basin</option>\
						<option value="646">Benue</option>\
						<option value="983">Bering Shelf-Margin Basins OCS</option>\
						<option value="864">Bermejo Basin</option>\
						<option value="326">Betic Zone</option>\
						<option value="620">Bicol Shelf Basin</option>\
						<option value="243">Big Horn Basin</option>\
						<option value="565">Bijianan Basin</option>\
						<option value="708">Bintuni/Sulawati Province</option>\
						<option value="821">Birrindudu Basin and Tanami Block</option>\
						<option value="250">Black Sea Continental Slope</option>\
						<option value="253">Black Sea Continental Slope</option>\
						<option value="259">Black Sea Deep-Water Basin</option>\
						<option value="399">Black Warrior Basin</option>\
						<option value="4">Blagoveshchensk Basin</option>\
						<option value="814">Bligh Water Basin</option>\
						<option value="245">Blue Ridge Thrust Belt</option>\
						<option value="256">Bogdashan Fold Belt</option>\
						<option value="287">Bohaiwan Basin</option>\
						<option value="155">Bohemia</option>\
						<option value="858">Bolsones Basin</option>\
						<option value="525">Bombay</option>\
						<option value="634">Bonaire Basin</option>\
						<option value="760">Bonaparte Gulf Basin</option>\
						<option value="723">Bone Basin</option>\
						<option value="952">Boothia Basin</option>\
						<option value="698">Borbon Basin</option>\
						<option value="539">Bose Basin</option>\
						<option value="829">Bowen Basin</option>\
						<option value="102">Bowser Basin</option>\
						<option value="912">Bransfield Offshore</option>\
						<option value="725">Brazilian Shield</option>\
						<option value="755">Brazilian Shield</option>\
						<option value="796">Brazilian Shield</option>\
						<option value="876">Bremer Basin</option>\
						<option value="213">Bresse Depression</option>\
						<option value="774">Browse Basin</option>\
						<option value="909">Burdwood Bank - North Scotia Ridge</option>\
						<option value="148">Bureya-Dunbey Region</option>\
						<option value="499">Burgos Basin</option>\
						<option value="483">Burros Uplift</option>\
						<option value="709">Caera Basin</option>\
						<option value="573">Cagayan Basin</option>\
						<option value="284">Cambridge Arch-Central Kansas Uplift</option>\
						<option value="533">Campeche-Sigsbee Salt Basin</option>\
						<option value="834">Campos Basin</option>\
						<option value="1">Canada Basin-Beaufort Slope OCS</option>\
						<option value="47">Canadian Cordillera</option>\
						<option value="138">Canadian Cordillera</option>\
						<option value="949">Canadian Shield</option>\
						<option value="894">Canadon Asfalto</option>\
						<option value="486">Canary Islands</option>\
						<option value="810">Canning Basin</option>\
						<option value="756">Cape Vogel Basin</option>\
						<option value="826">Capricorn Basin</option>\
						<option value="648">Cariaco Basin</option>\
						<option value="833">Carnarvon Basin</option>\
						<option value="202">Carpathian-Balkanian Basin</option>\
						<option value="762">Carpentaria Basin</option>\
						<option value="686">Cauca Basin</option>\
						<option value="631">Cauvery</option>\
						<option value="590">Cayman Ridge</option>\
						<option value="578">Cayman Trough</option>\
						<option value="678">Celebes Sea</option>\
						<option value="405">Cen. Iranian Microcontinents</option>\
						<option value="385">Central Afghanistan</option>\
						<option value="52">Central Alaska</option>\
						<option value="92">Central Alaska</option>\
						<option value="95">Central Alaska</option>\
						<option value="48">Central Alaska</option>\
						<option value="36">Central Alaska</option>\
						<option value="46">Central Alaska</option>\
						<option value="43">Central Alaska</option>\
						<option value="50">Central Alaska</option>\
						<option value="75">Central Alaska</option>\
						<option value="80">Central Alaska</option>\
						<option value="77">Central Alaska</option>\
						<option value="937">Central Barents Platform</option>\
						<option value="324">Central California OCS</option>\
						<option value="872">Central Chile Forearc Basin</option>\
						<option value="343">Central Coastal</option>\
						<option value="369">Central Iranian Basins</option>\
						<option value="69">Central Kamchatka Group of Basins</option>\
						<option value="941">Central Kara High</option>\
						<option value="123">Central Kazakhstan Folded Region</option>\
						<option value="534">Central Mesa</option>\
						<option value="96">Central Okhotsk High</option>\
						<option value="552">Central Oman Platform</option>\
						<option value="694">Central Sumatra Basin</option>\
						<option value="612">Central Vietnam Basin</option>\
						<option value="654">Cesar Basin</option>\
						<option value="849">Chaco Basin</option>\
						<option value="535">Chad</option>\
						<option value="882">Challenger Plateau</option>\
						<option value="1003">Chatham Rise</option>\
						<option value="1004">Chatham Rise</option>\
						<option value="357">Cherokee Platform</option>\
						<option value="602">Chiapas Massif</option>\
						<option value="605">Chiapas Massif-Nuclear Central America</option>\
						<option value="558">Chicontepec Basin</option>\
						<option value="463">Chihuahuan Basin and Range</option>\
						<option value="538">Chindwara</option>\
						<option value="619">Choco Pacific Basin</option>\
						<option value="181">Choybalsan Basin</option>\
						<option value="2">Chukchi Borderland OCS</option>\
						<option value="9">Chukchi Shelf OCS</option>\
						<option value="201">Chu-Sarysu Basin</option>\
						<option value="514">Chuxiong Basin</option>\
						<option value="301">Cincinnati Arch</option>\
						<option value="68">Cis-Patom Foredeep</option>\
						<option value="85">Cis-Sayan Basin</option>\
						<option value="853">Clarence-Moreton Basin</option>\
						<option value="489">Coahuila Platform</option>\
						<option value="785">Coen-Yambo Block</option>\
						<option value="889">Colorado Basin</option>\
						<option value="595">Columbian Basin</option>\
						<option value="79">Cook Inlet OCS</option>\
						<option value="957">Cornwallis Foldbelt</option>\
						<option value="280">Corsican-Sardinian Basins</option>\
						<option value="672">Cotabato Basin</option>\
						<option value="380">Crete</option>\
						<option value="241">Crimea High</option>\
						<option value="453">Cuoqing Lunpola Basin</option>\
						<option value="877">Curico Basin</option>\
						<option value="870">Cuyo Basin</option>\
						<option value="457">Cyrenacia Basin</option>\
						<option value="444">Cyrenacia Uplift</option>\
						<option value="792">Daly River Basin</option>\
						<option value="812">Damer Belt</option>\
						<option value="536">Damodar</option>\
						<option value="869">Darling Basin</option>\
						<option value="943">De Long High</option>\
						<option value="272">Denver Basin</option>\
						<option value="108">Deryugin Basin</option>\
						<option value="901">Deseado-Falklands Province</option>\
						<option value="768">Diamantina Province</option>\
						<option value="910">Diego Ramirez Basin</option>\
						<option value="312">Dinaric Alps</option>\
						<option value="160">Dnieper-Donets Basin</option>\
						<option value="225">Dobrogea Foreland</option>\
						<option value="237">Dobrogean Orogen</option>\
						<option value="197">Donbass Foldbelt</option>\
						<option value="835">Drummond Fold Belt and Anakie High</option>\
						<option value="657">East African Rift</option>\
						<option value="220">East Aral Basin</option>\
						<option value="221">East Aral Slope</option>\
						<option value="418">East China Sea Basin</option>\
						<option value="676">Eastern Cordillera Basin</option>\
						<option value="283">Eastern Great Basin</option>\
						<option value="187">Eastern Oregon-Washington</option>\
						<option value="570">East Flank Oman Sub-basin</option>\
						<option value="16">East Greenland Foldbelt</option>\
						<option value="932">East Greenland Foldbelt</option>\
						<option value="933">East Greenland Rift Basins</option>\
						<option value="260">East Ili Basin</option>\
						<option value="730">East Java Basin</option>\
						<option value="109">East Kamchatka Basin</option>\
						<option value="60">East Kamchatka Uplift</option>\
						<option value="688">East Natuna Basin</option>\
						<option value="736">East Ontong Java Rise</option>\
						<option value="883">East Patagonia Basin</option>\
						<option value="166">East Sakhalin Uplift</option>\
						<option value="426">East Texas Basin</option>\
						<option value="650">East Venezuela Basin</option>\
						<option value="675">East Zaire Precambrian Belt</option>\
						<option value="809">E. Kalahari Precambrian Belt</option>\
						<option value="924">Ellesmere Foldbelt</option>\
						<option value="520">Erdis Kufra</option>\
						<option value="238">Erlian Basin</option>\
						<option value="261">Erlian Uplift</option>\
						<option value="824">Eromanga Basin</option>\
						<option value="818">Espirito Santo Basin</option>\
						<option value="415">Essaouni Basin</option>\
						<option value="773">Etosha</option>\
						<option value="859">Eucla Basin</option>\
						<option value="360">Euphrates/Mardin</option>\
						<option value="54">Faeroes-Shetland-Orkney Basin</option>\
						<option value="542">Fahud Salt Basin</option>\
						<option value="641">Falcon Basin</option>\
						<option value="906">Falklands Plateau</option>\
						<option value="847">Familina Province</option>\
						<option value="97">Fennoscandian Border-Danish-Polish Margin</option>\
						<option value="303">Fergana Basin</option>\
						<option value="494">Fezzan Uplift</option>\
						<option value="991">Fiji Islands</option>\
						<option value="992">Fiji Islands</option>\
						<option value="995">Fiji Islands</option>\
						<option value="996">Fiji Islands</option>\
						<option value="998">Fiji Ridge</option>\
						<option value="999">Fiji Ridge</option>\
						<option value="753">Flores Basin</option>\
						<option value="469">Florida Peninsula</option>\
						<option value="531">Florida Peninsula</option>\
						<option value="306">Forest City Basin</option>\
						<option value="26">Foxe Basin</option>\
						<option value="1022">Foz de Amazonas Basin</option>\
						<option value="240">Fundy Basin</option>\
						<option value="273">Galician Basin</option>\
						<option value="832">Galilee Basin</option>\
						<option value="524">Ganges-Brahmaputra Delta</option>\
						<option value="980">Gangut High</option>\
						<option value="979">Gangut High</option>\
						<option value="830">Gascoyne Block</option>\
						<option value="861">Gawler Block</option>\
						<option value="336">Gensan Basin</option>\
						<option value="296">Georges Bank</option>\
						<option value="177">Georgia Basin</option>\
						<option value="804">Georgina Basin</option>\
						<option value="132">German-Polish Basin</option>\
						<option value="555">Ghaba Salt Basin</option>\
						<option value="577">Ghudun-Khasfeh Flank Province</option>\
						<option value="888">Gippsland Basin</option>\
						<option value="67">Gizhigin Basin</option>\
						<option value="234">Gobi Basin</option>\
						<option value="702">Gorontalo Basin</option>\
						<option value="432">Grand Erg/Ahnet Basin</option>\
						<option value="874">Great Australian Bight Basin</option>\
						<option value="257">Great Caucasus Foldbelt</option>\
						<option value="540">Greater Antilles Deformed Belt</option>\
						<option value="519">Greater Ghawar Uplift</option>\
						<option value="669">Greater Sarawak Basin</option>\
						<option value="179">Great Lake Basin</option>\
						<option value="216">Great Lake Uplift</option>\
						<option value="1007">Great South Basin</option>\
						<option value="1005">Great South Basin</option>\
						<option value="928">Greenland Shield</option>\
						<option value="927">Grumant Uplift</option>\
						<option value="637">Guajira Basin</option>\
						<option value="502">Guaymas-Topolobambo Basin</option>\
						<option value="410">Guercif Basin</option>\
						<option value="588">Guerrero Basin</option>\
						<option value="485">Gulf Cenozoic OCS</option>\
						<option value="476">Gulf Mesozoic OCS</option>\
						<option value="78">Gulf of Alaska Shelf OCS</option>\
						<option value="674">Gulf of Guinea</option>\
						<option value="518">Gulf of Oman Basin</option>\
						<option value="668">Guyana Shield</option>\
						<option value="656">Guyana-Suriname Basin</option>\
						<option value="372">Gyeongsang Basin</option>\
						<option value="464">Hail-Ga\'Ara Arch</option>\
						<option value="366">Haleb</option>\
						<option value="806">Halifax Basin</option>\
						<option value="811">Halls Creek Province</option>\
						<option value="703">Halmahera Basin</option>\
						<option value="685">Halmahera Platform</option>\
						<option value="8">Hammerfest-Varanger Basin</option>\
						<option value="461">Hamra Basin</option>\
						<option value="87">Hatton-Rockall Basin</option>\
						<option value="400">Hauts Basin</option>\
						<option value="616">Hays Structural Belt</option>\
						<option value="167">Heilongjiang Basin</option>\
						<option value="898">Hikurani Trough</option>\
						<option value="1023">Himalayan</option>\
						<option value="474">Himalayan Foreland</option>\
						<option value="795">Hodgkinson/Lachlan Fold Belt</option>\
						<option value="515">Hoggar</option>\
						<option value="398">Honshu Ridge</option>\
						<option value="28">Hope Basin OCS</option>\
						<option value="76">Horda-Norwegian-Danish Basin</option>\
						<option value="739">Huallaga Basin</option>\
						<option value="41">Hudson Bay Basin</option>\
						<option value="51">Hudson Strait Basin</option>\
						<option value="329">Huksan Platform</option>\
						<option value="559">Huqf-Haushi Uplift</option>\
						<option value="265">Iberian Massif</option>\
						<option value="286">Iberic Cordillera</option>\
						<option value="189">Idaho-Snake River Downwarp</option>\
						<option value="295">Illinois Basin</option>\
						<option value="478">Illizi Basin</option>\
						<option value="496">Indian Shield</option>\
						<option value="775">Indispensable Reef</option>\
						<option value="526">Indo-Burman</option>\
						<option value="443">Indus</option>\
						<option value="423">Inner Borderland OCS</option>\
						<option value="477">Interior Homocline-Central Arch</option>\
						<option value="251">Iowa Shelf</option>\
						<option value="112">Ireland-Scotland Platform</option>\
						<option value="127">Irish Sea</option>\
						<option value="553">Irrawaddy</option>\
						<option value="270">Ishikari Hidaka Basin</option>\
						<option value="290">Issyk-Kul Basin</option>\
						<option value="556">Iullemmeden</option>\
						<option value="446">Jafr-Tabuk Basin</option>\
						<option value="561">Jalisco-Oaxaca Platform</option>\
						<option value="229">Japan Volcanic Arc/Accreted Terrane</option>\
						<option value="758">Jatoba Basin</option>\
						<option value="718">Java/Banda Sea</option>\
						<option value="460">Jianghan Basin</option>\
						<option value="422">Jiangnan South Jiangsu Fold Belt</option>\
						<option value="320">Jiuquan Minle Wuwei Basin</option>\
						<option value="367">Joban Basin</option>\
						<option value="206">Junggar Basin</option>\
						<option value="208">Jura</option>\
						<option value="799">Kalahari</option>\
						<option value="946">Kane Basin</option>\
						<option value="374">Kanto Basin</option>\
						<option value="291">Karabogaz-Karakum High</option>\
						<option value="199">Karamay Thrust Belt</option>\
						<option value="323">Kardiff/Menders Massif</option>\
						<option value="852">Karoo</option>\
						<option value="40">Kempendiay Region</option>\
						<option value="902">Kerguelen Offshore</option>\
						<option value="1001">Kermadec Ridge</option>\
						<option value="1000">Kermadec Ridge</option>\
						<option value="689">Ketuneau/Sintang Terrane</option>\
						<option value="239">Khanka Basin</option>\
						<option value="575">Khartoum</option>\
						<option value="966">Khatanga Saddle</option>\
						<option value="59">Khatyrka Basin</option>\
						<option value="382">Khleisha Uplift</option>\
						<option value="585">Khorat Platform</option>\
						<option value="789">Kimberley Basin</option>\
						<option value="70">Kinkil Basin</option>\
						<option value="276">Klamath-Sierra Nevada</option>\
						<option value="416">Kohat-Potwar</option>\
						<option value="14">Kola Monocline-Finnmark Platform</option>\
						<option value="65">Koni-Tayganos Uplift</option>\
						<option value="610">Konkan</option>\
						<option value="337">Kopet-Dag Foldbelt</option>\
						<option value="339">Korea Bay Basin</option>\
						<option value="298">Korean Continental Shelf</option>\
						<option value="292">Korean Craton</option>\
						<option value="975">Koryak-Kamchatka Foldbelt</option>\
						<option value="976">Koryak-Kamchatka Foldbelt</option>\
						<option value="61">Kotelnich Arch</option>\
						<option value="604">Krishna-Godavari</option>\
						<option value="361">Kumukulig Basin</option>\
						<option value="338">Kunlunshan Fold Belt</option>\
						<option value="299">Kura Basin</option>\
						<option value="113">Kuril-Kamchatka Slope</option>\
						<option value="697">Kutei Basin</option>\
						<option value="115">Kuznetsk Basin</option>\
						<option value="873">Laboulaye-Macachin Basin</option>\
						<option value="45">Labrador-Newfoundland Shelf</option>\
						<option value="865">Lacklan Fold Belt</option>\
						<option value="623">Lakshadweep</option>\
						<option value="734">Lancones Basin</option>\
						<option value="513">Lanping Simao Basin</option>\
						<option value="953">Laptev Shelf</option>\
						<option value="344">Las Animas Arch</option>\
						<option value="783">Laura Basin</option>\
						<option value="567">Leidong Basin</option>\
						<option value="18">Lena-Vilyuy Basin</option>\
						<option value="580">Lesser Antilles Deformed Belt</option>\
						<option value="635">Lesser Antilles Deformed Belt</option>\
						<option value="302">Lesser Caucasus</option>\
						<option value="401">Levantine Basin</option>\
						<option value="479">Lhasa Basin</option>\
						<option value="421">Lhasa Terrane</option>\
						<option value="769">Lima Basin</option>\
						<option value="227">Lion-Camargue</option>\
						<option value="671">Llanos Basin</option>\
						<option value="145">London-Brabant Platform</option>\
						<option value="440">Longmenshan Dabashan Fold Belt</option>\
						<option value="844">Lord Howe Rise</option>\
						<option value="417">Los Angeles Basin</option>\
						<option value="425">Los Angeles Basin OCS</option>\
						<option value="433">Los Angeles Basin OCS</option>\
						<option value="412">Louisiana-Mississippi Salt Basins</option>\
						<option value="638">Lower Magdelena</option>\
						<option value="827">Loyalty Island Ridge</option>\
						<option value="1015">Ludlov Saddle</option>\
						<option value="771">Luffillian Arch</option>\
						<option value="311">Lusitanian Basin</option>\
						<option value="396">Lut Block and Depression</option>\
						<option value="281">Luxi Jiaoliao Uplift</option>\
						<option value="20">Mackenzie Delta</option>\
						<option value="12">Mackenzie Delta Slope</option>\
						<option value="29">Mackenzie Foldbelt</option>\
						<option value="581">Macuspana Basin</option>\
						<option value="788">Madagascar</option>\
						<option value="856">Madagascar Offshore</option>\
						<option value="905">Madre de Dios Basin</option>\
						<option value="759">Madre dos Dios Basin</option>\
						<option value="903">Magallanes Basin</option>\
						<option value="195">Magdalen Basin</option>\
						<option value="528">Magiscatzin Basin</option>\
						<option value="563">Mahanadi</option>\
						<option value="497">Makran</option>\
						<option value="797">Malakula/Aoba/Banks Basin</option>\
						<option value="645">Malay Basin</option>\
						<option value="658">Malay Peninsula</option>\
						<option value="673">Maldives</option>\
						<option value="908">Malvinas Basin</option>\
						<option value="907">Malvinas Plateau</option>\
						<option value="699">Manabi Basin</option>\
						<option value="255">Mangyshlak-Ustyurt Foldbelt</option>\
						<option value="652">Maracaibo Basin</option>\
						<option value="472">Marathon Thrust Belt</option>\
						<option value="607">Ma\'Rib-Al Jawf Basin</option>\
						<option value="920">Marie Byrd Onshore</option>\
						<option value="823">Marion Terrain</option>\
						<option value="845">Maryborough Basin</option>\
						<option value="862">Mascasin Basin</option>\
						<option value="606">Masila-Jeza Basin</option>\
						<option value="554">Masirah Trough</option>\
						<option value="207">Massif Central</option>\
						<option value="603">Maya Mountains</option>\
						<option value="548">Mazatlan Basin</option>\
						<option value="779">McArthur Basin</option>\
						<option value="328">Mediterranean Basin</option>\
						<option value="653">Mekong/Cuulong/Vung Tau Basin</option>\
						<option value="989">Melanesia Border Plateau</option>\
						<option value="986">Melanesia Border Plateau</option>\
						<option value="777">Melanesia Border Plateau</option>\
						<option value="987">Melanesia Border Plateau</option>\
						<option value="990">Melanesia Border Plateau</option>\
						<option value="781">Melanesia Border Plateau</option>\
						<option value="988">Melanesia Border Plateau</option>\
						<option value="700">Melawi Basin</option>\
						<option value="815">Mellish Reef</option>\
						<option value="713">Meratus High</option>\
						<option value="746">Merauke Platform</option>\
						<option value="878">Mercedes Basin</option>\
						<option value="393">Mesopotamian Foredeep Basin</option>\
						<option value="37">Mezen Basin</option>\
						<option value="211">Michigan Basin</option>\
						<option value="614">Middle America Province</option>\
						<option value="168">Middle Amur Basin</option>\
						<option value="218">Middle Caspian Basin</option>\
						<option value="663">Middle Magdelena</option>\
						<option value="105">Midland Valley-Forth Approaches Basin</option>\
						<option value="1018">Mid-North Sea High</option>\
						<option value="11">Minto Arch</option>\
						<option value="600">Mirbat Precambrian Basement</option>\
						<option value="456">Miyazaki Basin</option>\
						<option value="143">Mohe Basin</option>\
						<option value="192">Molasse Basin</option>\
						<option value="808">Mollendo-Tarapaca Basin</option>\
						<option value="38">Moma Basin</option>\
						<option value="761">Money Shoal Basin</option>\
						<option value="100">Mongol-Okhotsk Folded Region</option>\
						<option value="188">Montana Thrust Belt</option>\
						<option value="807">Moquegua-Tamaruga Basin</option>\
						<option value="778">Morondava</option>\
						<option value="72">Moscow Basin</option>\
						<option value="802">Mozambique Coastal</option>\
						<option value="820">Mt.Isa Block</option>\
						<option value="171">Mugodzhary-South Emba</option>\
						<option value="624">Mukalla Rift Basin</option>\
						<option value="158">Munsterland Basin</option>\
						<option value="871">Murray Basin</option>\
						<option value="505">Murzuk Basin</option>\
						<option value="848">Musgrave Block</option>\
						<option value="522">Nanpanjiang Depression</option>\
						<option value="441">Nanyang Basin</option>\
						<option value="307">Naryn Basin</option>\
						<option value="981">Navarin Basin OCS</option>\
						<option value="451">Nefusn Uplift</option>\
						<option value="308">Nemaha Uplift</option>\
						<option value="597">Neogene Volcanic Belt</option>\
						<option value="39">Nepa-Botuoba Arch</option>\
						<option value="881">Neuquen Basin</option>\
						<option value="819">New Caledonia</option>\
						<option value="210">New England</option>\
						<option value="822">New England Fold Belt</option>\
						<option value="738">New Guinea Foreland Basin-Fold Belt</option>\
						<option value="726">New Guinea Mobile Belt</option>\
						<option value="765">New Hebrides Arc</option>\
						<option value="710">New Ireland Basin</option>\
						<option value="1002">New Zealand East Coast Basin</option>\
						<option value="875">New Zealand Orogenic Belt</option>\
						<option value="839">Ngalia Basin</option>\
						<option value="680">Niger Delta</option>\
						<option value="628">Nigerian Massive</option>\
						<option value="358">Niigata Basin</option>\
						<option value="436">Nile Delta Basin</option>\
						<option value="896">Nirihuau Basin</option>\
						<option value="846">Norfolk Island Ridge</option>\
						<option value="90">North Aleutian Basin OCS</option>\
						<option value="716">North Banda Basin</option>\
						<option value="950">North Barents Basin</option>\
						<option value="506">North Burma</option>\
						<option value="170">North Carpathian Basin</option>\
						<option value="584">North Carribean Deformed Belt</option>\
						<option value="165">North Caspian Basin</option>\
						<option value="190">North-Central Montana</option>\
						<option value="940">North Chukchi Basin</option>\
						<option value="964">North Chukchi Basin</option>\
						<option value="223">North Crimea Basin</option>\
						<option value="249">Northeast Black Sea Shelf</option>\
						<option value="454">North Egypt Basin</option>\
						<option value="21">Northern Alaska</option>\
						<option value="370">Northern Arizona</option>\
						<option value="300">Northern Coastal</option>\
						<option value="17">Northern Interior Basins</option>\
						<option value="701">Northern Irian Jaya Waropen Basin</option>\
						<option value="922">North Greenland Foldbelt</option>\
						<option value="926">North Greenland Platform</option>\
						<option value="431">North Harrah Volcanics</option>\
						<option value="880">Northland Basin</option>\
						<option value="120">North Minusa Basin</option>\
						<option value="589">North Nicaraguan Rise</option>\
						<option value="942">North Novaya Zemlya Basin</option>\
						<option value="83">North Okhotsk Group of Basins</option>\
						<option value="495">North Red Sea Shield</option>\
						<option value="122">North Sakhalin Basin</option>\
						<option value="64">North Sea Graben</option>\
						<option value="661">North Sumatra Basin</option>\
						<option value="222">North Ustyurt Basin</option>\
						<option value="119">Northwest German Basin</option>\
						<option value="731">Northwest Java Basin</option>\
						<option value="803">Northwest Shelf</option>\
						<option value="44">Norton Basin OCS</option>\
						<option value="955">Novaya Zemlya Monocline</option>\
						<option value="944">Novosibirsk Basin</option>\
						<option value="523">Nubian Uplift</option>\
						<option value="204">Nyalga Basin</option>\
						<option value="993">Ocean</option>\
						<option value="997">Ocean</option>\
						<option value="1012">Ocean</option>\
						<option value="921">Ocean</option>\
						<option value="1006">Ocean</option>\
						<option value="982">Ocean</option>\
						<option value="493">Ocean</option>\
						<option value="841">Officer Basin</option>\
						<option value="447">Okinawa Trough</option>\
						<option value="73">Olyutor Basin</option>\
						<option value="517">Oman Mountains</option>\
						<option value="174">Onekotan Basin</option>\
						<option value="817">Orange River Coastal</option>\
						<option value="837">Oran-Olmedo Basin</option>\
						<option value="309">Ordos Basin</option>\
						<option value="895">Osorno-Llanquihue Basin</option>\
						<option value="885">Otway Basin</option>\
						<option value="465">Ougarta Uplift</option>\
						<option value="420">Outer Borderland OCS</option>\
						<option value="321">Ozark Uplift</option>\
						<option value="198">Pacific Northwest OCS</option>\
						<option value="608">Pacific Offshore Basin</option>\
						<option value="630">Palawan Shelf</option>\
						<option value="389">Palmyra Zone</option>\
						<option value="371">Palo Duro Basin</option>\
						<option value="335">Pamir High</option>\
						<option value="687">Pamusian Tarakan Basin</option>\
						<option value="636">Panjang/Cardomomes Basin</option>\
						<option value="183">Pannonian Basin</option>\
						<option value="745">Papuan Basin-Shelf Platform</option>\
						<option value="330">Paradox Basin</option>\
						<option value="800">Parana Basin</option>\
						<option value="770">Parecis Province</option>\
						<option value="314">Park Basins</option>\
						<option value="714">Parnaiba Basin</option>\
						<option value="521">Parras Basin</option>\
						<option value="958">Parry Island Foldbelt</option>\
						<option value="960">Parry Island Foldbelt</option>\
						<option value="836">Paterson Province</option>\
						<option value="551">Pearl River Mouth Basin</option>\
						<option value="387">Pedernal Uplift</option>\
						<option value="364">Pelagian Basin</option>\
						<option value="855">Pelotas Basin</option>\
						<option value="904">Penas Basin</option>\
						<option value="679">Penyu/West Natuna Basin</option>\
						<option value="42">Penzhina Basin</option>\
						<option value="651">Perija-Venezuela-Coastal Ranges</option>\
						<option value="409">Permian Basin</option>\
						<option value="744">Pernambuco Basin</option>\
						<option value="854">Perth Basin</option>\
						<option value="568">Philippine Accretionary Prism</option>\
						<option value="617">Philippine Magmatic Arc</option>\
						<option value="332">Piedmont</option>\
						<option value="828">Pilbara Block</option>\
						<option value="784">Pine Creek Geosyncline</option>\
						<option value="787">Pisco Basin</option>\
						<option value="230">Po Basin</option>\
						<option value="1013">Polar Province</option>\
						<option value="147">Poles Saddle</option>\
						<option value="721">Potigar Basin</option>\
						<option value="235">Powder River Basin</option>\
						<option value="569">Pranhita-Godavari</option>\
						<option value="144">Pripyat Basin</option>\
						<option value="715">Progreso Basin</option>\
						<option value="269">Provence Basin</option>\
						<option value="572">Puerto Rico Trench</option>\
						<option value="511">Purisima-Iray Basin</option>\
						<option value="696">Putumayo-Oriente-Maranon Basin</option>\
						<option value="271">Pyrenean Foothills-Ebro Basin</option>\
						<option value="459">Qabdu Basin</option>\
						<option value="342">Qaidam Basin</option>\
						<option value="504">Qatar Arch</option>\
						<option value="392">Qiangtang Tanggula Basin</option>\
						<option value="435">Qiangtang Terrane</option>\
						<option value="404">Qiangtang Terrane</option>\
						<option value="362">Qiangtang Terrane</option>\
						<option value="327">Qilianshan Fold Belt</option>\
						<option value="390">Qinling Dabieshan Fold Belt</option>\
						<option value="583">Qiongdongnan Basin</option>\
						<option value="133">Queen Charlotte Basin</option>\
						<option value="917">Queen Maud/Enderby Offshore</option>\
						<option value="918">Queen Maud Onshore</option>\
						<option value="794">Queensland Plateau</option>\
						<option value="136">Quesnel Basin</option>\
						<option value="379">Rabat Basin</option>\
						<option value="670">Rajang-Crocker Accretionary Prism</option>\
						<option value="355">Raton Basin-Rio Grande Uplift</option>\
						<option value="780">Reconcavo Basin</option>\
						<option value="481">Red Sea Basin</option>\
						<option value="629">Reed Bank Basin</option>\
						<option value="492">Reggane Basin</option>\
						<option value="509">Reguibate Uplift</option>\
						<option value="175">Rhine Graben</option>\
						<option value="334">Rif Basin</option>\
						<option value="282">Rioni Basin</option>\
						<option value="893">Rocky Cape Block/Dundas Trough</option>\
						<option value="84">Rocky Mountain Deformed Belt</option>\
						<option value="1009">Ross Offshore</option>\
						<option value="1010">Ross Offshore</option>\
						<option value="1011">Ross Offshore</option>\
						<option value="512">Rub Al Khali Basin</option>\
						<option value="754">Russell Basin</option>\
						<option value="141">Russian Craton Margin</option>\
						<option value="428">Rutbah Uplift</option>\
						<option value="468">Ryukyu Volcanic Arc</option>\
						<option value="491">Sabinas Basin</option>\
						<option value="319">Sacramento Basin</option>\
						<option value="394">Sagara Basin</option>\
						<option value="659">Saigon Basin</option>\
						<option value="879">Salado Basin</option>\
						<option value="843">Salar de Atacama Basin</option>\
						<option value="749">Salaverry Basin</option>\
						<option value="285">Salina Basin</option>\
						<option value="579">Saline-Comalcalco Basin</option>\
						<option value="424">Salton Trough</option>\
						<option value="448">Salton Trough</option>\
						<option value="790">Samoa Basin</option>\
						<option value="445">San Diego-Oceanside</option>\
						<option value="438">San Diego-Oceanside</option>\
						<option value="442">San Diego-Oceanside</option>\
						<option value="439">San Diego-Oceanside</option>\
						<option value="414">San Diego-Oceanside</option>\
						<option value="363">San Joaquin Basin</option>\
						<option value="899">San Jorge Basin</option>\
						<option value="359">San Juan Basin</option>\
						<option value="712">San Luis Basin</option>\
						<option value="544">Sanshui Basin</option>\
						<option value="413">Santa Barbara-Ventura Basin OCS</option>\
						<option value="813">Santa Cruz-Tarija Basin</option>\
						<option value="381">Santa Maria Basin</option>\
						<option value="695">Santana Platform</option>\
						<option value="717">Santiago Basin</option>\
						<option value="842">Santos Basin</option>\
						<option value="772">Sao Francisco Basin</option>\
						<option value="537">Satpura_Brahmani</option>\
						<option value="935">Schmidt Basin</option>\
						<option value="233">Scotian Shelf</option>\
						<option value="911">Scotia Ridge Offshore</option>\
						<option value="914">Scott Offshore</option>\
						<option value="232">Sea of Japan Backarc Basin</option>\
						<option value="740">Sechura Basin</option>\
						<option value="348">Sedgwick Basin</option>\
						<option value="550">Senegal</option>\
						<option value="727">Sepik-Ramu Basin</option>\
						<option value="764">Sergipe-Alagoas Basin</option>\
						<option value="934">Severnaya Zemlya High</option>\
						<option value="728">Seychelles</option>\
						<option value="611">Shabwah Basin</option>\
						<option value="322">Shanxi Plateau</option>\
						<option value="618">Sharmah Rift Basin</option>\
						<option value="547">Shiwan Dashan Basin</option>\
						<option value="748">Shorland Basin</option>\
						<option value="91">Shumagin-Kodiak Shelf OCS</option>\
						<option value="450">Sichuan Basin</option>\
						<option value="345">Sicily</option>\
						<option value="215">Sidney Basin</option>\
						<option value="1020">Sierra Madre de Chiapas-Peten Foldbelt</option>\
						<option value="467">Sierra Madre Occidental Volcanic Plateau</option>\
						<option value="582">Sierra Madre Oriental Foldbelt</option>\
						<option value="649">Sierra Nevada de Santa Marta</option>\
						<option value="110">Sikhote-Alin Folded Region</option>\
						<option value="449">Sinai Basin</option>\
						<option value="510">Sinaloa</option>\
						<option value="437">Sinzi Uplift</option>\
						<option value="236">Sioux Arch</option>\
						<option value="402">Sirte Basin</option>\
						<option value="900">Solander-Waiau Basin</option>\
						<option value="705">Solimoes Basin</option>\
						<option value="737">Solomon Islands</option>\
						<option value="640">Somali</option>\
						<option value="632">Somali Deep Sea</option>\
						<option value="184">Songliao Basin</option>\
						<option value="373">Songpan Ganzi Fold Belt</option>\
						<option value="347">Sonoma-Livermore Basin</option>\
						<option value="458">Sonoran Basin and Range</option>\
						<option value="857">South African Coastal</option>\
						<option value="965">South Arctic Basin</option>\
						<option value="741">South Banda Basin</option>\
						<option value="5">South Barents Basin</option>\
						<option value="720">South Bismarck Volcanic Arc</option>\
						<option value="625">South Caribbean Deformed Belt</option>\
						<option value="316">South Caspian Basin</option>\
						<option value="395">South-Central New Mexico</option>\
						<option value="541">South China Continental Shelf Slope</option>\
						<option value="462">South China Fold Belt</option>\
						<option value="587">South China Ocean Basin</option>\
						<option value="639">South China Sea Platform</option>\
						<option value="973">South Chukchi-Hope Basin</option>\
						<option value="972">South Chukchi-Hope Basin</option>\
						<option value="351">Southeast Afghanistan</option>\
						<option value="27">Southeast Greenland Basin</option>\
						<option value="116">Southern Alaska</option>\
						<option value="89">Southern Alaska</option>\
						<option value="107">Southern Alaska</option>\
						<option value="86">Southern Alaska</option>\
						<option value="129">Southern Alaska</option>\
						<option value="154">Southern Alaska</option>\
						<option value="135">Southern Alaska</option>\
						<option value="137">Southern Alaska</option>\
						<option value="94">Southern Alaska</option>\
						<option value="81">Southern Alaska</option>\
						<option value="53">Southern Alaska</option>\
						<option value="121">Southern Alaska</option>\
						<option value="152">Southern Alaska</option>\
						<option value="106">Southern Alaska</option>\
						<option value="111">Southern Alaska</option>\
						<option value="125">Southern Alaska</option>\
						<option value="157">Southern Alaska</option>\
						<option value="140">Southern Alaska</option>\
						<option value="146">Southern Alaska</option>\
						<option value="117">Southern Alaska</option>\
						<option value="114">Southern Alaska</option>\
						<option value="131">Southern Alaska</option>\
						<option value="130">Southern Alaska</option>\
						<option value="128">Southern Alaska</option>\
						<option value="118">Southern Alaska</option>\
						<option value="384">Southern Arizona-Southwestern New Mexico</option>\
						<option value="224">Southern Newfoundland Shelf</option>\
						<option value="406">Southern Oklahoma</option>\
						<option value="500">South Harrah Volcanics</option>\
						<option value="244">South Kuril Basin</option>\
						<option value="724">South Makassar Basin</option>\
						<option value="139">South Minusa Basin</option>\
						<option value="593">South Nicaraguan Rise</option>\
						<option value="150">South Okhotsk Basin</option>\
						<option value="571">South Oman Salt Basin</option>\
						<option value="560">South Red Sea Shield</option>\
						<option value="707">South Sumatra Basin</option>\
						<option value="176">South Turgay Basin</option>\
						<option value="247">Southwestern Wyoming</option>\
						<option value="1019">Southwest German Basin</option>\
						<option value="231">Southwest Montana</option>\
						<option value="258">Spanish Trough-Cantabrian Zone</option>\
						<option value="662">Sri Lanka</option>\
						<option value="930">St. Anna Basin</option>\
						<option value="88">St. George Basin OCS</option>\
						<option value="200">St. Lawrence Lowlands</option>\
						<option value="49">St. Matthew-Hall Basin OCS</option>\
						<option value="867">Stuart Shelf</option>\
						<option value="377">Subei Yellow Sea Basin</option>\
						<option value="743">Sucunduri Province</option>\
						<option value="622">Sud</option>\
						<option value="262">Suifun Basin</option>\
						<option value="430">Sulaiman-Kirthar</option>\
						<option value="693">Sulawesi Accretionary Prism</option>\
						<option value="692">Sulawesi Magmatic Arc</option>\
						<option value="429">Sulongshan Fold Belt</option>\
						<option value="666">Sulu Arch</option>\
						<option value="643">Sulu Sea Basin</option>\
						<option value="667">Sumatra/Java Accretionary Prism</option>\
						<option value="677">Sumatra/Java Fore-Arc Basins</option>\
						<option value="683">Sumatra/Java Magmatic Arc</option>\
						<option value="767">Sumba Province</option>\
						<option value="681">Sunda Platform</option>\
						<option value="185">Superior</option>\
						<option value="850">Surat Basin</option>\
						<option value="212">Susunay Uplift</option>\
						<option value="938">Svalbard High</option>\
						<option value="931">Sverdrup Basin</option>\
						<option value="868">Sydney Basin</option>\
						<option value="248">Syr-Darya Basin</option>\
						<option value="490">Syrian Arch</option>\
						<option value="691">Tacutu Basin</option>\
						<option value="278">Taihangshan Yanshan Fold Belt</option>\
						<option value="391">Taikang Hefei Basin</option>\
						<option value="939">Taimyr-Kara High</option>\
						<option value="530">Taiwan Melange Belt</option>\
						<option value="527">Taiwan Thrust and Fold Belt</option>\
						<option value="545">Taixinan Basin</option>\
						<option value="288">Tajo-Duero Basin</option>\
						<option value="733">Talara Basin</option>\
						<option value="62">Talov Uplift</option>\
						<option value="786">Tamatave</option>\
						<option value="529">Tamaulipas Arch</option>\
						<option value="546">Tampico-Misantla Basin</option>\
						<option value="719">Tanzania Coastal</option>\
						<option value="501">Taoudeni Basin</option>\
						<option value="887">Taranaki Basin</option>\
						<option value="293">Tarim Basin</option>\
						<option value="897">Tasmania Basin</option>\
						<option value="156">Tatar Strait Basin</option>\
						<option value="365">Tellian Foredeep</option>\
						<option value="353">Tellian Uplift</option>\
						<option value="180">Temtsag Hailar Basin</option>\
						<option value="884">Temuco Basin</option>\
						<option value="508">Tenasserim-Shan</option>\
						<option value="816">Tennant Creek Block</option>\
						<option value="173">Terpeniya Bay Basin</option>\
						<option value="627">Thai Basin</option>\
						<option value="557">Thailand Mesozoic Basin Belt</option>\
						<option value="516">Thiemboka Uplift</option>\
						<option value="294">Thrace/Samsun</option>\
						<option value="860">Three Kings Rise</option>\
						<option value="1016">Tian Shan Foldbelt</option>\
						<option value="31">Timan High</option>\
						<option value="15">Timan-Pechora Basin</option>\
						<option value="487">Tindouf Basin</option>\
						<option value="101">Tinro Basin</option>\
						<option value="644">Tobago Trough</option>\
						<option value="169">Tofino Basin</option>\
						<option value="279">Tokachi Basin</option>\
						<option value="801">Tonga Ridge</option>\
						<option value="621">Tonle Sap-Phnom Penh Basin</option>\
						<option value="498">Torrecon-Sierra Madre Oriental Foldbelt</option>\
						<option value="378">Tottori Basin</option>\
						<option value="375">Tottori Basin</option>\
						<option value="1008">Transantarctica Mountains</option>\
						<option value="1014">Transantarctica Mountains</option>\
						<option value="178">Trans-graben</option>\
						<option value="1021">Trans-mexican Neovolcanic Axis</option>\
						<option value="194">Transylvania</option>\
						<option value="407">Trias/Ghadames Basin</option>\
						<option value="6">Troms-Bjornoya Basin</option>\
						<option value="747">Trujillo Basin</option>\
						<option value="549">Truong Son Fold Belt</option>\
						<option value="376">Tsushima Basin</option>\
						<option value="766">Tucano Basin</option>\
						<option value="24">Tunguska Basin</option>\
						<option value="103">Turgay Depression</option>\
						<option value="277">Turpan Basin</option>\
						<option value="30">Turukhan-Igarka Uplift</option>\
						<option value="25">Turukhan-Norilsk Folded Zone</option>\
						<option value="263">Tuscany-Latium-Paola</option>\
						<option value="586">Tuxla Uplift</option>\
						<option value="313">Tuz/Corum</option>\
						<option value="297">Tyrrhenian Basin</option>\
						<option value="750">Ucayali Basin</option>\
						<option value="951">Uedineniya Basin</option>\
						<option value="317">Uinta-Piceance Basins</option>\
						<option value="163">Ukrainian Shield</option>\
						<option value="196">Ulan Bator Basin</option>\
						<option value="159">Upper Bureya Basin</option>\
						<option value="480">Upper Egypt Basin</option>\
						<option value="684">Upper Magdelena</option>\
						<option value="134">Upper Zeya Basin</option>\
						<option value="956">Ural-Novaya Zemlya Foldbelt</option>\
						<option value="936">Ushakov High</option>\
						<option value="142">Ushumun Basin</option>\
						<option value="776">Vanikoro Basin</option>\
						<option value="596">Venezuelan Basin</option>\
						<option value="408">Ventura Basin</option>\
						<option value="419">Ventura Basin</option>\
						<option value="574">Veracruz Basin</option>\
						<option value="947">Verkhoyan-Chukotka Folded Region</option>\
						<option value="974">Verkhoyan-Chukotka Folded Region</option>\
						<option value="967">Verkhoyan-Chukotka Folded Region</option>\
						<option value="35">Vestford-Helgeland</option>\
						<option value="791">Victoria River Basin</option>\
						<option value="576">Villahermosa Uplift</option>\
						<option value="642">Visayan</option>\
						<option value="484">Vizcaino Basin</option>\
						<option value="948">Vize High</option>\
						<option value="63">Volga-Ural Region</option>\
						<option value="633">Volta</option>\
						<option value="403">Wadi-Surhan Basin</option>\
						<option value="886">Waikato Basin</option>\
						<option value="923">Wandel Sea Basin</option>\
						<option value="891">Wanganui Basin</option>\
						<option value="732">Weber Basin</option>\
						<option value="913">Weddell Offshore</option>\
						<option value="655">West African Coastal</option>\
						<option value="543">West African Shield</option>\
						<option value="242">West Black Sea Basin</option>\
						<option value="690">West-central Coastal</option>\
						<option value="665">West-Central Cordillera</option>\
						<option value="254">Western Great Basin</option>\
						<option value="466">Western Gulf</option>\
						<option value="592">Western Nubian Shield</option>\
						<option value="186">Western Oregon-Washington</option>\
						<option value="34">West Greenland Basin</option>\
						<option value="268">West Ili Basin</option>\
						<option value="71">West Kamchatka Basin</option>\
						<option value="153">West Sakhalin Uplift</option>\
						<option value="959">West Siberian Basin</option>\
						<option value="647">West Zaire Precambrian Belt</option>\
						<option value="66">Whitehorse Basin</option>\
						<option value="411">Widyan Basin-Int. Platform</option>\
						<option value="916">Wilkes Onshore</option>\
						<option value="191">Williston Basin</option>\
						<option value="124">Williston Basin</option>\
						<option value="264">Wind River Basin</option>\
						<option value="805">Wiso Basin</option>\
						<option value="19">Wollaston Basin</option>\
						<option value="969">Wrangel Basin</option>\
						<option value="968">Wrangel Basin</option>\
						<option value="970">Wrangel-Herald Uplift</option>\
						<option value="971">Wrangel-Herald Uplift</option>\
						<option value="275">Wyoming Thrust Belt</option>\
						<option value="473">Xichang Yunnan Fold Belt</option>\
						<option value="757">Xingu Province</option>\
						<option value="591">Xisha Trough</option>\
						<option value="598">Yemen Volcanic Basin (North)</option>\
						<option value="613">Yemen Volcanic Basin (South)</option>\
						<option value="3">Yenisey-Khatanga Basin</option>\
						<option value="55">Yenisey Ridge</option>\
						<option value="851">Yilgarn Block</option>\
						<option value="564">Yinggehai Basin</option>\
						<option value="149">Yinshan Da&Xiao Hingganling Uplift</option>\
						<option value="182">Yinshan Da&Xiao Hingganling Uplift</option>\
						<option value="209">Yitong Graben</option>\
						<option value="562">Yucatan Basin</option>\
						<option value="532">Yucatan Platform</option>\
						<option value="33">Yukon Basin</option>\
						<option value="1017">Yunnan Guizhou Hubei Fold Belt</option>\
						<option value="349">Zagros Fold Belt</option>\
						<option value="346">Zagros Thrust Zone</option>\
						<option value="682">Zaire</option>\
						<option value="601">Zambalez/Central Luzon Basin</option>\
						<option value="193">Zaysan Basin</option>\
						<option value="151">Zeya-Bureya Basin</option>\
						<option value="219">Zhangguangcailing Uplift</option>\
						<option value="32">Zyryanka Basin</option>\
					</Select>');
		newResult.attr("id", 'tectonicProvinceSearch_' + rownum);
		$("#resultColumn_" + rownum).append(newResult);
		$("#tectonicProvinceSearch_" + rownum).change(function() {
			console.log( "Tectonic Province Changed." );
			getSearchCount();
		});

	}
}


function doNewSearchTest(){
	//var foo = createQueryString();

	//var e = document.getElementById("ownerSearch_0");
	//var strUser = e.options[e.selectedIndex].value;








	//createQueryString();

	console.log(expandedDatasets);


}



function getSearchCount(){
	//this function parses all inputs and does an AJAX call to the server
	//to get a count of dataset/spot level results
	console.log("Doing search here...");
	console.log("********************");

	//Close all open datasets
	closeAllOpenDatasets();

	$("#searchCountResults").html("");
	$("#performingSearch").fadeIn(400);
	$("#sideNewSearchButton").show();


	querystring = createQueryString();
	console.log("query string: " + querystring);

	if(querystring!=""){
		//send JSON querystring to server

		//clear results
		$("#searchResults").html("");

		$.post( "interfacesearch.php", querystring, function( data ) {
			console.log('did search and received: ' + data.counts.projectcount);

			//handle results from server here
			// update sidebar, map, etc...

			$("#performingSearch").hide();

	let projectCount = data.counts.projectcount;
	let datasetCount = data.counts.datasetcount;
	let spotCount = data.counts.spotcount;

			$("#searchCountResults").html("Results: " + projectCount + " Projects / " + datasetCount + " Datasets / " + spotCount + " Spots");



			//populate results from search
			if(projectCount > 0){
				projects = data.projects;
				projectnum = 0;
				_.each(projects, function(p){

	let projectname = p.project_name;
	let owner = p.owner_firstname + ' ' + p.owner_lastname;

	let newProjectString = '<div class="wrap-collabsible">\
							<input id="collapsible' + projectnum + '" class="toggle" type="checkbox" style="display:none;">\
							<label for="collapsible' + projectnum + '" class="lbl-toggle">' + projectname + '<br><span class="projectOwner">Owned By ' + owner + '</span></label>\
							<div class="collapsible-content">\
								<div class="content-inner">\
									<ul style="padding-left: 15px;margin-top: 0px;margin-bottom: 0px;">\
										';

					datasets = p.datasets;
					_.each(datasets, function(d){
						datasetname = d.dataset_name;
						datasetid = d.dataset_id;
						spotcount = d.spot_count;
						spotlabel = spotcount == 1 ? 'spot' : 'spots';
						newProjectString = newProjectString + '<li><a href="#" onclick="panToDataset(\'' + datasetid + '\')">' + datasetname + ' (' + spotcount + '  ' + spotlabel + ')</a></li>'
					});


					newProjectString = newProjectString + '</ul>\
								</div>\
							</div>\
						</div>';

	let newProject = $(newProjectString)

					$("#searchResults").append(newProject);

					projectnum++;

				});
			}

			//Rebuild map

			//set var newSearchFeatures to the featurecollection
			newSearchFeatures = data.geoJSON;

			newSearchRebuildDatasetsLayer();

			//Replace AllDatasets with results from search
			updateAllDatasets();


		});
	}else{
		$("#searchCountResults").html("");
		$("#performingSearch").hide();
	}

	/*
	setTimeout(function(){
		$("#performingSearch").hide();

	let datasetCount = getRandomInteger(0,99);
	let spotCount = getRandomInteger(1111,99999);

		$("#searchCountResults").html("Results: " + datasetCount + " datasets / " + spotCount + " spots");
	}, 	1000);
	*/
}



function newSearchReset(){
	//this function clears search results and updates map

	//show progress
	$('#spotswaiting').show();

	//hide new search button
	$("#sideNewSearchButton").hide();

	zoomHome();

	//clear owner
	ownerSearchVals=[];

	//clear results
	$("#searchResults").html("");

	//reset search interface
	$( "#rowContainer" ).html("");

	//clear counts
	$("#searchCountResults").html("");

	//close open datasets
	closeAllOpenDatasets();

	//reset count
	rownum = 0;

	//add initial row
	addRow();

	querystring = "";

	$.post( "interfacesearch.php", querystring, function( data ) {
		console.log('did search and received: ' + data.counts.projectcount);

		newSearchFeatures = data.geoJSON;

		newSearchRebuildDatasetsLayer();

		//Replace AllDatasets with results from search
		updateAllDatasets();



		//hide progress
		$('#spotswaiting').hide();

	});

}


function closeAllOpenDatasets(){
	console.log("close all open datasets...")
	_.each(expandedDatasets, function(exdat){
		exid = exdat.get('id');
		console.log("close dataset: "+exid);
		removeDatasetFromArray(exid);
		removeDatasetFromLoadedFeatures(exid);
		updateShownDatasets();
	})
}



function panToDataset(datasetId){  //pan to dataset and highlight

	let foundObject = null;
	_.each(newSearchFeatures.features, function(f){
		if(f.properties.id == datasetId){
			foundObject = f;
		}
	});

	if(foundObject){
		datasetLongitude = foundObject.geometry.coordinates[0];
		datasetLatitude = foundObject.geometry.coordinates[1];

		console.log("Longitude: "+datasetLongitude);
		console.log("Latitude: "+datasetLatitude);


		//mapView.setCenter(ol.proj.transform([datasetLongitude, datasetLatitude], 'EPSG:4326', 'EPSG:3857'));
		//mapView.animate({center: ol.proj.transform([datasetLongitude, datasetLatitude], 'EPSG:4326', 'EPSG:3857'),duration: 2}, null);
		//mapView.setCenter(ol.proj.transform([datasetLongitude, datasetLatitude], 'EPSG:4326', 'EPSG:3857'),{duration: 1000});

		/*
	let pan = ol.animation.pan({
			duration: 2000,
			source: (mapView.getCenter())
		});
		map.beforeRender(pan);
		mapView.setCenter(ol.proj.transform([datasetLongitude, datasetLatitude], 'EPSG:4326', 'EPSG:3857'));
		*/

		//mapView.animate({center: ol.proj.transform([datasetLongitude, datasetLatitude], 'EPSG:4326', 'EPSG:3857'),duration: 1000});

		/*
		mapView.animate(
			{zoom: 3, duration: 500},
			{center: ol.proj.transform([datasetLongitude, datasetLatitude], 'EPSG:4326', 'EPSG:3857'),duration: 500},
			{zoom: 6, duration: 500}

		);
		*/


		//setSelectedDataset(map, datasetLongitude, datasetLatitude);

		mapView.animate({center: ol.proj.transform([datasetLongitude, datasetLatitude], 'EPSG:4326', 'EPSG:3857'),duration: 500});

		mapView.animate({zoom: 8, duration: 500});

	}

}

function panToCoords(longitude, latitude, zoomlevel){



	let lat = parseFloat(latitude);
	let lon = parseFloat(longitude);

	mapView.animate({center: ol.proj.transform([lon, lat], 'EPSG:4326', 'EPSG:3857'),duration: 500});

	mapView.animate({zoom: zoomlevel, duration: 500});


}



















//roll through search rows and build query for server
function testcreateQueryString(){

	return "";

}





















//roll through search rows and build query for server
function createQueryString(){

	let paramArray = [];

	for (let i = 0; i < 100; i++) {

		if( $("#typeColSel_"+i).val() != null && $("#typeColSel_"+i).val() != "" ){

	let addThisParam = false;
	let thisParam = {};
	let thisConstraints = [];

	let thisQualifier = $("#andOr_"+i).val();
			if( typeof thisQualifier === 'undefined' ) thisQualifier = "and";

			thisParam.num = i;
			thisParam.qualifier = thisQualifier;


			//Date Collected
			if( $("#minYearSearch_" + i).val() != "" ||  $("#maxYearSearch_" + i).val() != "" ){
				if( $("#minYearSearch_" + i).val() ){
					thisConstraint = {};
					thisConstraint.constraintType = 'minYear';
					thisConstraint.constraintValue = $("#minYearSearch_" + i).val();
					thisConstraints.push(thisConstraint);
				}
				if( $("#maxYearSearch_" + i).val() ){
					thisConstraint = {};
					thisConstraint.constraintType = 'maxYear';
					thisConstraint.constraintValue = $("#maxYearSearch_" + i).val();
					thisConstraints.push(thisConstraint);
				}

				thisParam.constraints = thisConstraints;
				addThisParam = true;
			}

			//Image Type
			if( $("#imageTypeSearch_" + i).val() != "" ){
				if( $("#imageTypeSearch_" + i).val() ){
					thisConstraint = {};
					thisConstraint.constraintType = 'imageType';
					thisConstraint.constraintValue = $("#imageTypeSearch_" + i).val();
					thisConstraints.push(thisConstraint);
				}

				thisParam.constraints = thisConstraints;
				addThisParam = true;
			}

			//Rock Type
			if( $("#rockTypeSearch_" + i).val() != "" ){
				if( $("#rockTypeSearch_" + i).val() ){
					thisConstraint = {};
					thisConstraint.constraintType = 'rockType';
					thisConstraint.constraintValue = $("#rockTypeSearch_" + i).val();
					thisConstraints.push(thisConstraint);
				}

				thisParam.constraints = thisConstraints;
				addThisParam = true;
			}

			//Metamorphic Facies
			if( $("#metFaciesSearch_" + i).val() != "" ){
				if( $("#metFaciesSearch_" + i).val() ){
					thisConstraint = {};
					thisConstraint.constraintType = 'metFacies';
					thisConstraint.constraintValue = $("#metFaciesSearch_" + i).val();
					thisConstraints.push(thisConstraint);
				}

				thisParam.constraints = thisConstraints;
				addThisParam = true;
			}

			//Tectonic Province
			if( $("#tectonicProvinceSearch_" + i).val() != "" ){
				if( $("#tectonicProvinceSearch_" + i).val() ){
					thisConstraint = {};
					thisConstraint.constraintType = 'tectonicProvince';
					thisConstraint.constraintValue = $("#tectonicProvinceSearch_" + i).val();
					thisConstraints.push(thisConstraint);
				}

				thisParam.constraints = thisConstraints;
				addThisParam = true;
			}

			//Keyword
			if( $("#keywordSearch_" + i).val() != "" ){
				if( $("#keywordSearch_" + i).val() ){
					thisConstraint = {};
					thisConstraint.constraintType = 'keyword';
					thisConstraint.constraintValue = $("#keywordSearch_" + i).val();
					thisConstraints.push(thisConstraint);
				}

				thisParam.constraints = thisConstraints;
				addThisParam = true;
			}

			//MicroStructure
			if( $("#typeColSel_"+i).val() == "Microstructure" ){
				thisConstraint = {};
				thisConstraint.constraintType = 'microstructureExists';
				thisConstraints.push(thisConstraint);

				thisParam.constraints = thisConstraints;
				addThisParam = true;
			}

			//Orientation
			if( $("#typeColSel_"+i).val() == "Orientation" ){
				thisConstraint = {};
				thisConstraint.constraintType = 'orientationExists';
				thisConstraints.push(thisConstraint);

				thisParam.constraints = thisConstraints;
				addThisParam = true;
			}

			//Owner
			if( ownerSearchVals[i] != "" && ownerSearchVals[i] != null ){

					thisConstraint = {};
					thisConstraint.constraintType = 'owner';
					//thisConstraint.constraintValue = $("#ownerSearch_" + i).val();
					//thisConstraint.constraintValue = $("#ownerSearch_" + i).getSelectedItemData().id;
					thisConstraint.constraintValue = ownerSearchVals[i];
					thisConstraints.push(thisConstraint);

					console.log("constraints:");
					console.log(thisConstraints);



					//$(#ownerSearch_" + i).getSelectedItemData().id;


				thisParam.constraints = thisConstraints;
				addThisParam = true;
			}

			//Orientation
			if( $("#typeColSel_"+i).val() == "Sample" ){
				thisConstraint = {};
				thisConstraint.constraintType = 'sampleExists';
				thisConstraints.push(thisConstraint);

				thisParam.constraints = thisConstraints;
				addThisParam = true;
			}

			//Sample ID
			if( $("#sampleIDSearch_" + i).val() != "" ){
				if( $("#sampleIDSearch_" + i).val() ){
					thisConstraint = {};
					thisConstraint.constraintType = 'sampleID';
					thisConstraint.constraintValue = $("#sampleIDSearch_" + i).val();
					thisConstraints.push(thisConstraint);
				}

				thisParam.constraints = thisConstraints;
				addThisParam = true;
			}

			//Strat Column
			if( $("#typeColSel_"+i).val() == "Strat Column" ){
				thisConstraint = {};
				thisConstraint.constraintType = 'stratColumnExists';
				thisConstraints.push(thisConstraint);

				thisParam.constraints = thisConstraints;
				addThisParam = true;
			}

			if(thisParam.constraints.length > 0){
				if(addThisParam == true){
					paramArray.push(thisParam);
				}
			}
		}

	}

	if(paramArray.length > 0){
		outObject = {params: paramArray};
		outJSON = JSON.stringify(outObject);
		console.log("outJSON: ");
		console.log(outJSON);
		return outJSON;
	}else{
		return "";
	}

}

























function getRandomInteger(min, max) {
	return Math.floor(Math.random() * (max - min) ) + min;
}


function oldsidesearch_open() {
	document.getElementById("map").style.marginLeft = "430px";
	document.getElementById("mySidebar").style.width = "430px";
	document.getElementById("mySidebar").style.display = "block";
	//document.getElementById("openNav").style.display = 'none';
}
function oldsidesearch_close() {
	document.getElementById("map").style.marginLeft = "0px";
	document.getElementById("mySidebar").style.display = "none";
	//document.getElementById("openNav").style.display = "inline-block";
}

