var currentDB;

function bring(source) {
	
	samplePage = "./files/visualizations/" + source + ".html";
	xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function()
	{
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200)
		{
			document.getElementById("sample_plot").innerHTML = xmlhttp.responseText;
		}
	}
	xmlhttp.open('GET',samplePage, true);
	xmlhttp.send();
	
}
function checker(source) {
	
	var provValue = document.getElementById('provs').value;
	var datasetValue = document.getElementById('datasets').value;
	var dbValue = document.getElementById('db').value;

	if (provValue=='' | provValue=='-') {
		alert('Please, provide a valid data publisher value');

	} else if (datasetValue!='' && datasetValue!='-' && (source=='recsperres' || source=='resperbasis' || source=='resvsdow' || source=='resvsdom') ) {
		alert('This visualization is not suitable for a single collection assessment. Please, select another visualization or remove the value of the collection.');

	} else {
		if (datasetValue == '-') {
			datasetValue = 'all';
		}
		sitio=source+'.php?prov='+provValue+'&dataset='+datasetValue+'&db='+dbValue;
		window.location = sitio;
	}

}

function storeDB() {


	currentDB = document.getElementById('db').value;
}

function checkVersionChange() {
	
	var selectedProvId;
	var selectedDatasetId;
	

	if (document.getElementById('provs').value.substr(0,1) == "-") {

		populatePublishers();

	} else {
		
		selectedProvId = document.getElementById('provs').options[document.getElementById('provs').selectedIndex].value;
			
		var i = 0;
		var pubMatch = 0;
		var provlist;
		
		provlist = prepareList('provs');
		
		for (i = 0; i<provlist.length; i++) {

			var spacePos = provlist[i].indexOf(' ');
			tempvalue = provlist[i].substr(0,spacePos);
			if (selectedProvId == tempvalue) {
				pubMatch = 1;
				break;
			}
		}
		
		if (pubMatch != 1) {

			alert("Publisher #" + selectedProvId + " not found on the new version");

			var temp = 0;
			var dbIndex = -1;

			var dbversions = document.getElementById('db').options;

			for (temp = 0; temp<dbversions.length; temp++) {

				if (dbversions[temp].value == currentDB) {
					dbIndex = temp;
				}
			}
			document.getElementById('db').options[dbIndex].selected = "1"

		} else {
			populatePublishers(selectedProvId);

			if (document.getElementById('datasets').selectedIndex == -1) {
				populateCollections(selectedProvId);
	
			} else {
			
				selectedDatasetId = document.getElementById('datasets').options[document.getElementById('datasets').selectedIndex].value;
					
				var i = 0;
				var setsMatch = 0;
				var setlist;
				
				setlist = prepareList('datasets', selectedProvId);
				
				for (i = 0; i<setlist.length; i++) {
					var spacePos = setlist[i].indexOf(' ');
					tempvalue = setlist[i].substr(0,spacePos);
					if (selectedDatasetId == tempvalue) {
						setsMatch = 1;
						break;
					}
				}
				
				if (setsMatch != 1) {
					alert("Dataset #" + selectedDatasetId + " not found on the new version");
					var temp = 0;
					var dbIndex = -1;
					var dbversions = document.getElementById('db').options;
					for (temp = 0; temp<dbversions.length; temp++) {
						if (dbversions[temp].value == currentDB) {
							dbIndex = temp;
						}
					}
					document.getElementById('db').options[dbIndex].selected = "1"
				
				} else {
					populateCollections(selectedProvId, selectedDatasetId);
				}
			}
		}
	}
}

function populatePublishers(selectedProvId, sessionDB, sessionProv, sessionDS) {
	
	var provlist;
	var i = 0;
	var j = 0;
	
	var elSel = document.getElementById('provs');
	var totalLength=elSel.length;
	for (j=0; j<=totalLength; j++) {
		elSel.remove(0);
	}
	
	provlist = prepareList('provs');
	
	var vars = window.location.search.substring(1).split('&');

	for (i=0; i<provlist.length; i++) {
		var elOptNew = document.createElement('option');
		var tempvalue;
		var spacePos = provlist[i].indexOf(' ');
				
		elOptNew.text = provlist[i];
		tempvalue = provlist[i].substr(0,spacePos);
		elOptNew.value = tempvalue;		
		
		if (tempvalue == selectedProvId) {
			elOptNew.selected = "selected";
			populateCollections(sessionProv, sessionDS, 1);
		} else if (tempvalue == sessionProv) {
			elOptNew.selected = "selected";
			populateCollections(sessionProv, sessionDS, 1);
		}
		
		try {
			elSel.add(elOptNew, null);
		}	
		catch(ex) {
			elSel.add(elOptNew);
		}
	}

	oldDiv = document.getElementById("availablePublishers");
	document.body.firstChild.removeChild(oldDiv);
	var newValue = document.createElement("div");
	newValue.id="availablePublishers";
	var numberofitems = document.getElementById("provs").length;
	if (document.getElementById('filterprov').value == '') {
		numberofitems = numberofitems - 1;
	}
	var newValueText = document.createTextNode("Start typing to filter the select box. Available publishers (" + numberofitems + "):");
	newValue.appendChild(newValueText);
	var previousElement = document.getElementById("provspar");
	document.body.firstChild.insertBefore(newValue, previousElement);
	
}

function populateCollections(selectedProvId, selectedDatasetId, nocheck) {

	var datasetslist;
	var i = 0;
	var j = 0;
	
	if (nocheck != 1 & document.getElementById('provs').selectedIndex == -1) {
		alert("Please, select a data publisher from the list above.")
	} else if (selectedProvId != '-') {
		var elSel = document.getElementById('datasets');
		var totalLength=elSel.length;
		for (j=0; j<=totalLength; j++) {
			elSel.remove(0);
		}
		
		datasetslist = prepareList('datasets', selectedProvId);
		
		for (i=0; i<datasetslist.length; i++) {
			var elOptNew = document.createElement('option');
			var tempvalue;
			var spacePos = datasetslist[i].indexOf(' ');
					
			elOptNew.text = datasetslist[i];
			tempvalue = datasetslist[i].substr(0,spacePos);
			elOptNew.value = tempvalue;
			
			if (tempvalue == selectedDatasetId) {
				elOptNew.selected = "selected";
			}
			
			try {
				elSel.add(elOptNew, null);
			}
			catch(ex) {
				elSel.add(elOptNew);
			}
		}
	
	oldDiv = document.getElementById("availableCollections");
	document.body.firstChild.removeChild(oldDiv);
	var newValue = document.createElement("div");
	newValue.id="availableCollections";
	var numberofitems = document.getElementById("datasets").length;
	if (document.getElementById('filterdataset').value == '') {
		numberofitems = numberofitems - 1;
	}
	var newValueText = document.createTextNode("Start typing to filter the select box. Available collections (" + numberofitems + "):");
	newValue.appendChild(newValueText);
	var previousElement = document.getElementById("datasetspar");
	document.body.firstChild.insertBefore(newValue, previousElement);
	
	}
}

function prepareList(type, selectedProvId) {
	
	var oRequest = new XMLHttpRequest();
	var finalList;
	var provfiltertext = document.getElementById('filterprov').value.toUpperCase();
	var datasetfiltertext = document.getElementById('filterdataset').value.toUpperCase();
	var i = 0;
	finalList = new Array();
	
	if (type == 'datasets') {
		var sURL = "http://"
			+ self.location.hostname
			+ "/Mariposas/biddsat/data/"
			+ document.getElementById('db').value
			+ "/"
			+ selectedProvId	
			+ "/datasets_codes.txt"
	} else if (type == 'provs') {
		var sURL = "http://"
			+ self.location.hostname
			+ "/Mariposas/biddsat/data/"
			+ document.getElementById('db').value + "/prov_codes.txt"
	}
	
	oRequest.open("GET",sURL,false);
	oRequest.setRequestHeader("User-Agent",navigator.userAgent);
	oRequest.send(null)
	
	if (oRequest.status==200) {
		origList = oRequest.responseText.split("\n");
	}
	
	if (type == 'provs' & provfiltertext != '') {
		for (i = 0; i < origList.length; i++) {
			if (origList[i].toUpperCase().indexOf(provfiltertext, 0) != -1) {
				finalList.push(origList[i]);
			}
		}
		
	} else if (type == 'datasets' & datasetfiltertext != '') {
		for (i = 0; i < origList.length; i++) {
			if (origList[i].toUpperCase().indexOf(datasetfiltertext, 0) != -1) {
				finalList.push(origList[i]);
			}
		}
	} else {
		origList.pop();
		finalList = origList;
	}
	
	if (type == 'provs' & provfiltertext == '') {
		finalList.unshift("- - Select one or start typing above - - ");
	} else if (type == 'datasets' & datasetfiltertext =='') {
		finalList.unshift("- - Select one or start typing above - - ");
	}
	
	return finalList;
}

function removeFilter(type) {
	
	if (type == 'publishers') {
		document.getElementById('filterprov').value = ''
		populatePublishers();
		var elSel = document.getElementById('datasets');
		var totalLength=elSel.length;
		for (j=0; j<=totalLength; j++) {
			elSel.remove(0);
		}
	} else if (type == 'collections') {
		document.getElementById('filterdataset').value = ''
		populateCollections();
	}
}