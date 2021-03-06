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

$filename=$path.'RecordsByYearAll.txt';

$datalink = checkFile($filename, $db, $prov, $dataset);

$file=file($filename);

$data=array();
$temp=array();

foreach($file as $line) {
	$temparray=explode("\t", $line);
	array_push($data, $temparray);
	array_push($temp, $temparray[0]);
}

array_shift($data);
array_shift($temp);

$length=count($data);

$min=min($temp);
$max=max($temp);

if (!$_GET['log'] or ($_GET['log']<>'true' and $_GET['log']<>'false')) {
	$log='false';
} else {
	$log=$_GET['log'];
}

if ($log=='true') {
	$logchecked='checked';
	$aritchecked='';
} else {
	$aritchecked='checked';
	$logchecked='';
}

$url='./recsperyearall.php?prov='.$prov.'&dataset='.$dataset.'&db='.$db;
?>

<html>
  <head>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart", "table"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('number', 'Year');
        data.addColumn('number', 'Records');
        data.addRows(<?php echo $length;?>);

		<?php
		
		for ($i=0; $i<$length; $i++) {
		echo 'data.setValue('.$i.', 0, '.$data[$i][0].');'."\n";
		echo 'data.setValue('.$i.', 1, '.$data[$i][1].');'."\n";
		}
		
		?>
		
        var chart = new google.visualization.ScatterChart(document.getElementById('chart_div'));
        chart.draw(data, {width: 800, height: 480,
                          title: 'Records per year for publisher <?php echo $prov; if($dataset<>'all') { echo ', data resource '.$dataset; } ?>',
                          hAxis: {title: 'Year', minValue: <?php echo $min; ?>, maxValue: <?php echo $max; ?>},
                          vAxis: {title: 'Records', minValue: 0, logScale: <?php echo $log; ?>},
                          legend: 'none',
						  lineWidth: 0,
						  pointSize: 2,
						  series: [{color:'black'}]
                         });
		
		
		var tableData = new google.visualization.DataTable();
        tableData.addColumn('number', 'Year');
        tableData.addColumn('number', 'Records');
        tableData.addRows(<?php echo $length;?>);

		<?php
		
		for ($i=0; $i<$length; $i++) {
		echo 'tableData.setValue('.$i.', 0, '.$data[$i][0].');'."\n";
		echo 'tableData.setValue('.$i.', 1, '.$data[$i][1].');'."\n";
		}
		
		?>
		
        var tableChart = new google.visualization.Table(document.getElementById('table_div'));
        tableChart.draw(tableData, {width: 200,
			page: 'enable',
			pageSize: 20,
			sortAscending: false,
			sortColumn: 1,
			title: 'Records per year for publisher <?php echo $prov; if($dataset<>'all') { echo ', data resource '.$dataset; } ?>',
            });
      }
    </script>
  </head>

  <body>
    <?php
	if ($length==0) {
		echo "<b>WARNING:</b> This publisher has not published any occurrence record.";
	}
	?>
	<table><tr>
    <td><div id="chart_div"></div><br><center>
	<input type="radio" name="logscale" value="Arithmetic" onclick="window.location='<?php echo $url; ?>&log=false';" <?php echo $aritchecked; ?>>Arithmetic &nbsp &nbsp
	<input type="radio" name="logscale" value="Logarithmic" onclick="window.location='<?php echo $url; ?>&log=true';" <?php echo $logchecked; ?>>Logarithmic
	</center></td>
	<td><div id="table_div"></div></td>
	</tr></table>
  <br><?php echo $datalink."&nbsp &nbsp".$endbutton; ?>
  </body>
</html>