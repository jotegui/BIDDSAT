<?php
// BIDDSAT - BIoDiversity DataSet Assessment Tool
// Copyright (C) 2012 Javier Otegui

// This file is part of BIDDSAT.

// BIDDSAT is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.

// BIDDSAT is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.

// You should have received a copy of the GNU General Public License
// along with BIDDSAT.  If not, see <http://www.gnu.org/licenses/>.

// Contact: javier.otegui@gmail.com

include 'header.php';

$filename=$path.'RecordsByBasisCreated.txt';

$datalink = checkFile($filename, $db, $prov, $dataset);

$file=file($filename);

$data=array();

foreach($file as $line) {
	$temparray=explode("\t", $line);
	array_push($data, $temparray);
}

array_shift($data);

$length=count($data);

$dates=array();
$basis=array();

for ($i=0; $i<$length;$i++) {
	array_push($basis, trim($data[$i][1]));
}

$basis=array_keys(array_count_values($basis));
$bases=count($basis);

$temp=array();

for ($i=0; $i<$length; $i++) {
	
	$temparray=array();
	array_push($temparray, $data[$i][0]);
	
	for ($j=0; $j<$bases; $j++) {
		
		if($i==0) {
			$k=0;
		} else {
			$k=$temp[$i-1][$j+1];
		}
		if ($data[$i][1]==$basis[$j]) {
			$tempvalue=trim($data[$i][2])+$k;
		} else {
			$tempvalue=0+$k;
		}
		
		array_push($temparray, $tempvalue);
	}
	
	array_push($temp, $temparray);
}

$chartdata=$temp;
?>


<html>
	<head>
		<script type="text/javascript" src="https://www.google.com/jsapi"></script>
		<script type="text/javascript">
			google.load("visualization", "1", {packages:["corechart"]});
			google.setOnLoadCallback(drawChart);
			function drawChart() {
				var data = new google.visualization.DataTable();
				data.addColumn('string', 'Indexation');

<?php

for ($i=0; $i<$bases; $i++) {
	echo 'data.addColumn(\'number\',\''.$basis[$i].'\');'."\n";
}

?>

				data.addRows([

<?php

for ($i=0; $i<$length; $i++) {
	echo '[\''.$chartdata[$i][0].'\', ';
	for ($j=0; $j<$bases; $j++) {
		echo $chartdata[$i][$j+1];
		if ($j!=$bases-1) {
			echo ', ';
		}
	}
	echo ']';
	if ($i!=$length-1) {
		echo ',';
	}
	echo "\n";
}

?>

				]);

				var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
				chart.draw(data, {
					width: 1200,
					height: 600,
					hAxis: {
						title: 'Date of indexation'
					},
					vAxis: {
						title: 'Number of Records'
					},
					title: 'Indexation per type of record',
					isStacked: true
				});
			}
		</script>
	</head>
	
	<body>
		<div id="chart_div"></div>
	<?php echo $datalink."&nbsp &nbsp".$endbutton; ?>
	</body>
</html>