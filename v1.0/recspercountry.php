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

$filename=$path.'RecordsByCountry.txt';

$datalink = checkFile($filename, $db, $prov, $dataset);

$file=file($filename);

$data=array();

foreach($file as $line) {
	$temparray=explode("\t", $line);
	array_push($data, $temparray);
}

array_shift($data);

$length=count($data);

$temp=array();

for ($i=0; $i<$length;$i++) {
	array_push($temp, trim($data[$i][2]));
}

?>

<html>
  <head>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart", "table"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Country');
        data.addColumn('number', 'Records');
        
<?php

		echo 'data.addRows('.$length.');';
		
		for ($i=0; $i<$length; $i++) {
			echo 'data.setValue('.$i.', 0,\''.$data[$i][0].'\');';
			echo 'data.setValue('.$i.', 1,'.$data[$i][1].');';
		}

?>

        var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
        chart.draw(data, {width: 750, height: 600, title: 'Records per country for publisher <?php echo $prov; if($dataset<>'all') { echo ', data resource '.$dataset; } ?>'});
		
		var tableData = new google.visualization.DataTable();
        tableData.addColumn('string', 'Country');
        tableData.addColumn('number', 'Records');
		
<?php

		echo 'tableData.addRows('.$length.');'."\n";
		for ($i=0; $i<$length; $i++) {
			echo 'tableData.setValue('.$i.', 0, \''.$data[$i][0].'\');'."\n";
			echo 'tableData.setValue('.$i.', 1, '.trim($data[$i][1]).');'."\n";
		}

?>
		
		var tableChart = new google.visualization.Table(document.getElementById('table_div'));
        tableChart.draw(tableData, {
			page: 'enable',
			pageSize: 20,
			width: 250,
			title: 'Records per country for publisher <?php echo $prov; ?>'
			});
      }
    </script>
  </head>

  <body>
    <table><tr>
    <td><div id="chart_div"></div></td>
	<td><div id="table_div"></div></td>
	</tr></table>
	<br><?php echo $datalink."&nbsp &nbsp".$endbutton; ?>
  </body>
</html>