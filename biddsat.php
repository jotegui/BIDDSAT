<?php
session_start();

$db = $_SESSION['db'];
$prov = $_SESSION['prov'];
$dataset = $_SESSION['dataset'];

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>
<head>
<title>BINDCAT: Biodiversity Institutions' Data Collections Assessment Tool</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<script src="./files/scripts.js" type="text/javascript"></script>
<!-- Imported from UNAV -->
<link rel="stylesheet" href="./files/style.css" type="text/css" />
<link rel="stylesheet" href="./files/interna_sin_anexo.css" type="text/css" />
<link rel="shortcut icon" href="http://www.unav.es/favicon.ico" />
<!-- End of import block -->
</head>
<body onload="populatePublishers('',<?php echo "'".$db."','".$prov."','".$dataset."'"; ?>)"><div id="documento">
			<div id="cabecera">
				<!--<div id="foto-esquina"><div style="position:relative; z-index:1; width:100%;">
					<img src="http://www.unav.es/evento/voluntarios-ambientales/files/slideshowmodule/interiores_ES/blanco_160_75.jpg" />
				</div></div>-->
				<div id="nombre-catedra">
					<table>
						<tr>
							<td id="catedra-td">
								<h1 id="catedra">
									Biodiversity Datasets Assessment Tool
								</h1>
								<span id="fac">
									<a href="http://www.unav.es/departamento/unzyec/" title="Dept. Zoology and Ecology">
										DEPT. ZOOLOGY AND ECOLOGY
									</a>
								</span>
							</td>
						</tr>
					</table>
				</div>
				<div id="logotipo"><a href="http://www.unav.es" title="Universidad de Navarra"><img src="http://www.unav.es/evento/voluntarios-ambientales/themes/unavcongresotheme/imagenes/marca-page.png" alt="Universidad de Navarra" /></a></div>
				</div>

<p id="instructions">
First, select the database version you want to assess. Then, start typing the name or ID of a data publisher. If you want to assess the content of a specific collection, start typing its name or ID. Be careful: some visualizations are not allowed for individual collections.
</p>

<p id="divbase">
Database version:<br>
<select name="db" id="db" onClick="storeDB()" onChange="checkVersionChange()" tabindex="1">
<?php

$datadb=scandir('./data/');
array_shift($datadb);
array_shift($datadb);
$datadb=array_reverse($datadb);


for ($i=0; $i<count($datadb); $i++) {
	echo "\t".'<option value="'.$datadb[$i].'"';
	if ($db==$datadb[$i]) {
		echo ' selected="selected" ';
	}
	echo '>'.substr($datadb[$i],5,4)." / ".substr($datadb[$i],9,2).'</option>'."\n";
}
?>
</select>
</p>

<p id="divprov_new">
<div id="availablePublishers">Start typing to filter the select box. Available publishers:</div>
<p id="provspar">
<input type="text" name="filterprov" id="filterprov" size="50%" onkeyup="if(this.value!=''){populatePublishers()}" autocomplete="off" tabindex="2">
<input type="button" value="Clear" onclick="removeFilter('publishers')"><br>
<select id="provs" size="1" onfocus="populateCollections(this.options[this.selectedIndex].value)" onclick="populateCollections(this.options[this.selectedIndex].value)" tabindex="3">
</select>
</p>

<p id="divdataset_new">
<div id="availableCollections">Start typing to filter the select box. Available collections:</div>
<p id="datasetspar">
<input type="text" name="filterdataset" id="filterdataset" size="50%" onkeyup="if(this.value!=''){populateCollections()}" autocomplete="off" tabindex="4">
<input type="button" value="Clear" onclick="removeFilter('collections')"><br>
<select id="datasets" size="1" tabindex="5">
</select>
</p>

<input type="button" value="Restart" onclick="window.location='./logout.php?redir=main'" tabindex="6"> &nbsp &nbsp &nbsp
<input type="button" value="Exit" onclick="window.location='./logout.php?redir=index'" tabindex="7">

<hr>

Now, please click on the visualization type (hover to see longer description):<br><br>

<table width="100%" bgcolor="lightgrey" style="border-width:thin; border-style:solid;"><tr height="30"></tr>
<td width="20%"
title="General volume and feature completion information on the publisher/collection. If only publisher is selected, completion plots are shown."
align="center" onmouseover="this.bgColor='grey'" onmouseout="this.bgColor='lightgrey'" onclick="checker('info')"><img src="./icons/info3.png" width="100" /><br><font size="1">Info</font></td>
<td width="20%"
title="Number of records for each collection. This assessment can only be performed over the general content of a publisher, not over individual collections. Type: Pie Chart."
align="center" onmouseover="this.bgColor='grey'" onmouseout="this.bgColor='lightgrey'" onclick="checker('recsperres')"><img src="./icons/pie1.png" width="100"/><br><font size="1">Records per dataset</font></td>
<td width="20%"
title="Number of records declared as Observations, Museum specimens or any of the other types by the publisher. Type: Pie Chart."
align="center" onmouseover="this.bgColor='grey'" onmouseout="this.bgColor='lightgrey'" onclick="checker('recsperbasis')"><img src="./icons/types4.png" width="100"/><br><font size="1">Records per type</font></td>
<td width="20%"
title="Number of data collections according to the declared type of record (observations, specimens...). This assessment can only be performed over the general content of a publisher, not over individual collections. Type: Pie Chart."
align="center" onmouseover="this.bgColor='grey'" onmouseout="this.bgColor='lightgrey'" onclick="checker('resperbasis')"><img src="./icons/pie2.png" width="100"/><br><font size="1">Collections per type of record</font></td>
<td width="20%"
title="Map showing the density of records. Projection is equirectangular. Color follows a cold-hot exponential scale, dark blue representing only 1 record and brown representing more than 2048 records for the same lat-lon combination."
align="center" onmouseover="this.bgColor='grey'" onmouseout="this.bgColor='lightgrey'" onclick="checker('map')"><img src="./icons/map.png" width="100"/><br><font size="1">Map</font></td>
</tr>
<tr height="10"></tr>
<tr>
<td width="20%"
title="Distribution of records among declared countries. Country values follow the ISO-3166-1 alpha-2 code, which assigns a unique combination of two letters to each country. Type: Pie Chart."
align="center" onmouseover="this.bgColor='grey'" onmouseout="this.bgColor='lightgrey'" onclick="checker('recspercountry')"><img src="./icons/countries.png" width="100"/><br><font size="1">Records per country</font></td>
<td width="20%"
title="Number of records declared to be sampled each year from 1750 to present. Volume of records can be represented over an arithmetic or logarithmic axis. Type: Scatter Plot."
align="center" onmouseover="this.bgColor='grey'" onmouseout="this.bgColor='lightgrey'" onclick="checker('recsperyear')"><img src="./icons/filtered.png" width="100"/><br><font size="1">Records per year (Filtered)</font></td>
<td width="20%"
title="Number of records declared to be sampled each year, with no filtering for feasible years. Volume of records can be represented over an arithmetic or logarithmic axis. Type: Scatter Plot."
align="center" onmouseover="this.bgColor='grey'" onmouseout="this.bgColor='lightgrey'" onclick="checker('recsperyearall')"><img src="./icons/unfiltered.png" width="100"/><br><font size="1">Records per year (All)</font></td>
<td width="20%"
title="Distribution of record density for each day-of-year. Type:Hebdoplot."
align="center" onmouseover="this.bgColor='grey'" onmouseout="this.bgColor='lightgrey'" onclick="checker('recsdomvsmonth')"><img src="./icons/Year.png" width="100"/><br><font size="1">Record density, day of year</font></td>
<td width="20%"
title="Distribution of record density for each day-of-week and each month. Type:Hebdoplot."
align="center" onmouseover="this.bgColor='grey'" onmouseout="this.bgColor='lightgrey'" onclick="checker('recsdowvsmonth')"><img src="./icons/Week.png" width="100"/><br><font size="1">Record density, day of week</font></td>
</tr>
<tr height="10"></tr>
<tr>
<td width="20%"
title="Average value of the record volume for each day-of-year among years, +/- Standard Error. Type: Scatter Plot."
align="center" onmouseover="this.bgColor='grey'" onmouseout="this.bgColor='lightgrey'" onclick="checker('recsperdayofyear')"><img src="./icons/avg.png" width="100"/><br><font size="1">Average records among years</font></td>
<td width="20%"
title="Temporal distribution of records. Each dot represents a unique date. Radius reflects year (center=1750, perimeter=now) and clockwise angle from vertical reflects day-of-year (0º=Jan-1, 359º=Dec-31). Color follows a cold-hot exponential scale, dark blue representing only 1 record and brown representing more than 2048 records."
align="center" onmouseover="this.bgColor='grey'" onmouseout="this.bgColor='lightgrey'" onclick="checker('diatom')"><img src="./icons/chronh.png" width="100"/><br><font size="1">Chronhorogram</font></td>
<td width="20%"
title="Number of records declared to belong to each taxonomic kingdom. Type: Pie Chart."
align="center" onmouseover="this.bgColor='grey'" onmouseout="this.bgColor='lightgrey'" onclick="checker('recsperkingdom')"><img src="./icons/kingdom.png" width="100"/><br><font size="1">Records per kingdom</font></td>
<td width="20%"
title="Nested surface map where size of the cell reflects number of species name strings belonging to that taxonomic group. Color enhances comparisons between kingdoms."
align="center" onmouseover="this.bgColor='grey'" onmouseout="this.bgColor='lightgrey'" onclick="checker('spetreemap')"><img src="./icons/treemapT.png" width="100"/><br><font size="1">Tree Map of Taxonomy</font></td>
<td width="20%"
title="Nested surface map where size of the cell reflects number of records belonging to that taxonomic group. Color enhances comparisons between kingdoms."
align="center" onmouseover="this.bgColor='grey'" onmouseout="this.bgColor='lightgrey'" onclick="checker('rectreemap')"><img src="./icons/treemapR.png" width="100"/><br><font size="1">Tree Map of Records</font></td>
</tr><tr height="30"></tr></table>
<p>
<hr>
<i><table width="100%"><tr><td>
For any help you need, <a href="mailto:javier.otegui@SPAMBLOCKgmail.com">give a little whistle</a>
</td><td align="right">
v0.3 (<a href="changelog.txt" target="_blank">changelog</a>)
</td></tr></table></i>
</div>
</body>
</html>
