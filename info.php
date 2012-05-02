<?php

include 'header.php';

$filename=$path.'GeoTempoCompletion.txt';

$datalink = checkFile($filename, $db, $prov, $dataset);

$file=file($filename);

$data=array();

foreach($file as $line) {
	$temparray=explode("\t", $line);
	array_push($data, $temparray);
}

array_shift($data);

// Basic statistic

$resource_no=count($data);

$temp=array();
for ($i=0; $i<count($data); $i++) {
	array_push($temp, $data[$i][1]);
}
$record_no=array_sum($temp);

$temp=array();
for ($i=0; $i<count($data); $i++) {
	array_push($temp, $data[$i][2]);
}
$georecord_no=array_sum($temp);

$temp=array();
for ($i=0; $i<count($data); $i++) {
	array_push($temp, $data[$i][6]);
}
$yearrecord_no=array_sum($temp);

$temp=array();
for ($i=0; $i<count($data); $i++) {
	array_push($temp, $data[$i][8]);
}
$daterecord_no=array_sum($temp);

$temp=array();
for ($i=0; $i<count($data); $i++) {
	array_push($temp, $data[$i][4]);
}
$countryrecord_no=array_sum($temp);

$temp=array();
for ($i=0; $i<count($data); $i++) {
	array_push($temp, $data[$i][10]);
}
$kingdomrecord_no=array_sum($temp);

$temp=array();
for ($i=0; $i<count($data); $i++) {
	array_push($temp, $data[$i][12]);
}
$taxorecord_no=array_sum($temp);

$temp=array();
for ($i=0; $i<count($data); $i++) {
	array_push($temp, $data[$i][14]);
}
$basisrecord_no=array_sum($temp);

$temp=array();
for ($i=0; $i<count($data); $i++) {
	array_push($temp, $data[$i][16]);
}
$unkrecord_no=array_sum($temp);

$temp=array();
for ($i=0; $i<count($data); $i++) {
	array_push($temp, $data[$i][18]);
}
$obsrecord_no=array_sum($temp);

$temp=array();
for ($i=0; $i<count($data); $i++) {
	array_push($temp, $data[$i][20]);
}
$sperecord_no=array_sum($temp);

$temp=array();
for ($i=0; $i<count($data); $i++) {
	array_push($temp, $data[$i][22]);
}
$othrecord_no=array_sum($temp);

// Prepare values for completion plots
function extractPercentages($array, $type) {
	
	$temp=array();
	switch ($type) {
		case 'geo':
			$rounded=array_map('roundGeoPercentage',$array);
			break;
		case 'country':
			$rounded=array_map('roundCountryPercentage',$array);
			break;
		case 'year':
			$rounded=array_map('roundYearPercentage',$array);
			break;
		case 'date':
			$rounded=array_map('roundDatePercentage',$array);
			break;
		case 'kingdom':
			$rounded=array_map('roundKingdomPercentage',$array);
			break;
		case 'taxo':
			$rounded=array_map('roundTaxoPercentage',$array);
			break;
	}
	
	$final=array_count_values($rounded);
	
	for ($i=0; $i<11; $i++) {
		if($final[$i]=='') {
			$final[$i]=0;
		}
		array_push($temp, $final[$i]);
	}
	return $temp;
}

function roundGeoPercentage($item) { return intval(round($item[3],-1)/10); }
function roundCountryPercentage($item) { return intval(round($item[5],-1)/10); }
function roundYearPercentage($item) { return intval(round($item[7],-1)/10); }
function roundDatePercentage($item) { return intval(round($item[9],-1)/10); }
function roundKingdomPercentage($item) { return intval(round($item[11],-1)/10); }
function roundTaxoPercentage($item) { return intval(round($item[13],-1)/10); }

$geoData=extractPercentages($data, 'geo');
$countryData=extractPercentages($data, 'country');
$yearData=extractPercentages($data, 'year');
$dateData=extractPercentages($data, 'date');
$kingdomData=extractPercentages($data, 'kingdom');
$taxoData=extractPercentages($data, 'taxo');

?>

<html><head>

	<script type='text/javascript' src='https://www.google.com/jsapi'></script>
	<script type='text/javascript'>
		google.load('visualization', '1', {packages:['table','corechart']});
		google.setOnLoadCallback(drawCharts);
		function drawCharts() {


			var generalTableData = new google.visualization.DataTable();
			generalTableData.addColumn('number','Resource ID');
			generalTableData.addColumn('number','Records');
			generalTableData.addColumn('number','With Coordinates');
			generalTableData.addColumn('number','With Coordinates (%)');
			generalTableData.addColumn('number','With Country');
			generalTableData.addColumn('number','With Country(%)');
			generalTableData.addColumn('number','With Year');
			generalTableData.addColumn('number','With Year (%)');
			generalTableData.addColumn('number','With Date');
			generalTableData.addColumn('number','With Date (%)');
			generalTableData.addColumn('number','With Kingdom');
			generalTableData.addColumn('number','With Kingdom (%)');
			generalTableData.addColumn('number','With Taxonomy');
			generalTableData.addColumn('number','With Taxonomy (%)');
			<?php if($dataset=='all') { echo "
			generalTableData.addColumn('number','With Basis');
			generalTableData.addColumn('number','With Basis (%)');
			generalTableData.addColumn('number','Unknown');
			generalTableData.addColumn('number','Unknown (%)');
			generalTableData.addColumn('number','Observations');
			generalTableData.addColumn('number','Observations (%)');
			generalTableData.addColumn('number','Specimens');
			generalTableData.addColumn('number','Specimens (%)');
			generalTableData.addColumn('number','Other Basis');
			generalTableData.addColumn('number','Other Basis (%)');
			"; } ?>
			generalTableData.addRows([
			
<?php

for ($i=0; $i<count($data); $i++) {
	echo '[';
	for ($j=0; $j<count($data[$i]); $j++) {
		echo trim($data[$i][$j]).', ';
	}
	echo '],'."\n";
}

?>
			]);
		
			var generalTable = new google.visualization.Table(document.getElementById('table_div'));
			generalTable.draw(generalTableData, {
				showRowNumber: false,
				page: 'enable',
				pageSize: 20
			});
			

			// GeoChart
			var geoScatterData = new google.visualization.DataTable();
 			geoScatterData.addColumn('string','Percentage');
			geoScatterData.addColumn('number','Number of Resources');
			geoScatterData.addRows([
			
<?php
for ($i=0; $i<count($geoData); $i++) {
	echo '[\''.$i.'\', '.$geoData[$i].']';
	if ($i<count($geoData)-1) {
		echo ',';
	}
	echo "\n";
}
?>
			]);
			
			var geoChart = new google.visualization.ColumnChart(document.getElementById('geochart_div'));
			geoChart.draw(geoScatterData, {width: 300, height: 300,
				title: 'Geospatial Completion of publishers',
				hAxis: {title: 'Percentage of completion', minValue: 0, maxValue: 100, textPosition: 'none'},
				vAxis: {title: 'Number of resources', minValue: 0},
				legend: 'none',
				series: [{color: 'grey'}]
			});
			
			
			// CountryChart
			var countryScatterData = new google.visualization.DataTable();
			countryScatterData.addColumn('string','Percentage');
			countryScatterData.addColumn('number','Number of Resources');
			countryScatterData.addRows([
			
<?php

for ($i=0; $i<count($countryData); $i++) {
	echo '[\''.$i.'\', '.$countryData[$i].']';
	if ($i<count($countryData)-1) {
		echo ',';
	}
	echo "\n";
}

?>
			
			]);
			
			var countryChart = new google.visualization.ColumnChart(document.getElementById('countrychart_div'));
			countryChart.draw(countryScatterData, {width: 300, height: 300,
				title: 'Country Completion of publishers',
				hAxis: {title: 'Percentage of completion', minValue: 0, maxValue: 100, textPosition: 'none'},
				vAxis: {title: 'Number of resources', minValue: 0},
				legend: 'none',
				series: [{color: 'grey'}]
			});
			
			
			// YearChart
			var yearScatterData = new google.visualization.DataTable();
			yearScatterData.addColumn('string','Percentage');
			yearScatterData.addColumn('number','Number of Resources');
			yearScatterData.addRows([
			
<?php

for ($i=0; $i<count($yearData); $i++) {
	echo '[\''.$i.'\', '.$yearData[$i].']';
	if ($i<count($yearData)-1) {
		echo ',';
	}
	echo "\n";
}

?>
			
			]);
			
			var yearChart = new google.visualization.ColumnChart(document.getElementById('yearchart_div'));
			yearChart.draw(yearScatterData, {width: 300, height: 300,
				title: 'Year Completion of publishers',
				hAxis: {title: 'Percentage of completion', minValue: 0, maxValue: 100, textPosition: 'none'},
				vAxis: {title: 'Number of resources', minValue: 0},
				legend: 'none',
				series: [{color: 'grey'}]
			});
			
			
			// DateChart
			var dateScatterData = new google.visualization.DataTable();
			dateScatterData.addColumn('string','Percentage');
			dateScatterData.addColumn('number','Number of Resources');
			dateScatterData.addRows([
			
<?php

for ($i=0; $i<count($dateData); $i++) {
	echo '[\''.$i.'\', '.$dateData[$i].']';
	if ($i<count($dateData)-1) {
		echo ',';
	}
	echo "\n";
}

?>
			
			]);
			
			var dateChart = new google.visualization.ColumnChart(document.getElementById('datechart_div'));
			dateChart.draw(dateScatterData, {width: 300, height: 300,
				title: 'Date Completion of publishers',
				hAxis: {title: 'Percentage of completion', minValue: 0, maxValue: 100, textPosition: 'none'},
				vAxis: {title: 'Number of resources', minValue: 0},
				legend: 'none',
				series: [{color: 'grey'}]
			});
			
			
			// KingdomChart
			var kingdomScatterData = new google.visualization.DataTable();
			kingdomScatterData.addColumn('string','Percentage');
			kingdomScatterData.addColumn('number','Number of Resources');
			kingdomScatterData.addRows([
			
<?php

for ($i=0; $i<count($kingdomData); $i++) {
	echo '[\''.$i.'\', '.$kingdomData[$i].']';
	if ($i<count($kingdomData)-1) {
		echo ',';
	}
	echo "\n";
}

?>
			
			]);
			
			var kingdomChart = new google.visualization.ColumnChart(document.getElementById('kingdomchart_div'));
			kingdomChart.draw(kingdomScatterData, {width: 300, height: 300,
				title: 'Kingdom Completion of publishers',
				hAxis: {title: 'Percentage of completion', minValue: 0, maxValue: 100, textPosition: 'none'},
				vAxis: {title: 'Number of resources', minValue: 0},
				legend: 'none',
				series: [{color: 'grey'}]
			});
			
			
			// TaxoChart
			var taxoScatterData = new google.visualization.DataTable();
			taxoScatterData.addColumn('string','Percentage');
			taxoScatterData.addColumn('number','Number of Resources');
			taxoScatterData.addRows([
			
<?php

for ($i=0; $i<count($taxoData); $i++) {
	echo '[\''.$i.'\', '.$taxoData[$i].']';
	if ($i<count($taxoData)-1) {
		echo ',';
	}
	echo "\n";
}

?>
			
			]);
			
			var taxoChart = new google.visualization.ColumnChart(document.getElementById('taxochart_div'));
			taxoChart.draw(taxoScatterData, {width: 300, height: 300,
				title: 'Taxonomic Completion of publishers',
				hAxis: {title: 'Percentage of completion', minValue: 0, maxValue: 100, textPosition: 'none'},
				vAxis: {title: 'Number of resources', minValue: 0},
				legend: 'none',
				series: [{color: 'grey'}]
			});
			
		}
		
	</script>

</head>
<body>
	<h3>Record exploration for publisher <?php echo $prov; if($dataset<>'all') { echo ', data resource '.$dataset; } ?></h3>
	<table><tr><td>
	<?php
		if ($dataset=='all'){
			echo "Number of data resources:</td><td>".$resource_no."</td><td>&nbsp &nbsp &nbsp ";
		}
	?>
	Number of records:</td><td><?php echo $record_no; ?></td></tr>
	<tr><td>Number of records with coordinates:</td><td><?php echo $georecord_no.' ('.round(($georecord_no/$record_no)*100,2).'%)'; ?></td>
	<td>&nbsp &nbsp &nbsp Number of records with country:</td><td><?php echo $countryrecord_no.' ('.round(($countryrecord_no/$record_no)*100,2).'%)'; ?></td></tr>
	<tr><td>Number of records with year:</td><td><?php echo $yearrecord_no.' ('.round(($yearrecord_no/$record_no)*100,2).'%)'; ?></td>
	<td>&nbsp &nbsp &nbsp Number of records with date:</td><td><?php echo $daterecord_no.' ('.round(($daterecord_no/$record_no)*100,2).'%)'; ?></td></tr>
	<tr><td>Number of records with kingdom:</td><td><?php echo $kingdomrecord_no.' ('.round(($kingdomrecord_no/$record_no)*100,2).'%)'; ?></td>
	<td>&nbsp &nbsp &nbsp Number of records with taxonomy:</td><td><?php echo $taxorecord_no.' ('.round(($taxorecord_no/$record_no)*100,2).'%)'; ?></td></tr>
	<tr><td>Number of records with basis:</td><td><?php echo $basisrecord_no.' ('.round(($basisrecord_no/$record_no)*100,2).'%)'; ?></td>
	<tr><td><br></td></tr>
	<tr><td>Number of records with basis unknown:</td><td><?php echo $unkrecord_no.' ('.round(($unkrecord_no/$record_no)*100,2).'%)'; ?></td></tr>
	<tr><td>Number of Observational records:</td><td><?php echo $obsrecord_no.' ('.round(($obsrecord_no/$record_no)*100,2).'%)'; ?></td></tr>
	<tr><td>Number of Specimen records:</td><td><?php echo $sperecord_no.' ('.round(($sperecord_no/$record_no)*100,2).'%)'; ?></td></tr>
	<tr><td>Number of Other records:</td><td><?php echo $othrecord_no.' ('.round(($othrecord_no/$record_no)*100,2).'%)'; ?></td></tr>
	</table>
	<?php
	if ($dataset=='all') {
		echo "<table>";
		echo "<tr><td>\n";
		echo "<div id='geochart_div'></div>\n";
		echo "</td><td>\n";
		echo "<div id='yearchart_div'></div>\n";
		echo "</td><td>\n";
		echo "<div id='kingdomchart_div'></div>\n";
		echo "</td></tr>\n";
		echo "<tr><td>\n";
		echo "<div id='countrychart_div'></div>\n";
		echo "</td><td>\n";
		echo "<div id='datechart_div'></div>\n";
		echo "</td><td>\n";
		echo "<div id='taxochart_div'></div>\n";
		echo "</td></tr>\n";
		echo "</table>";
		echo "<i>Click on a header to sort the table</i>";
		echo "<div id='table_div'></div>";
		
	}
	?>
	<br>
	<?php echo $datalink."&nbsp &nbsp".$endbutton; ?>
</body></html>