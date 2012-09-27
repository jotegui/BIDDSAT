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

$filename=$path.'MeanRecordsByDayofyear.txt';

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
        data.addColumn('number', 'Day of Year');
        data.addColumn('number', 'Average number of records among years');
        data.addColumn('number', 'Average number + Standard Error');
		data.addColumn('number', 'Average number - Standard Error');
        data.addRows(<?php echo $length;?>);

		<?php
		
		for ($i=0; $i<$length; $i++) {
		echo 'data.setValue('.$i.', 0, '.$data[$i][0].');'."\n";
		echo 'data.setValue('.$i.', 1, '.floatval(trim($data[$i][1])).');'."\n";
		$tempvalue1=floatval(trim($data[$i][1]));
		$tempvalue2=sqrt(trim($data[$i][2]));
		$tempvalue3=$tempvalue1+$tempvalue2;
		$tempvalue4=$tempvalue1-$tempvalue2;
		if($tempvalue4<0) {
			$tempvalue4=0;
		}
		echo 'data.setValue('.$i.', 2, '.$tempvalue3.');'."\n";
		echo 'data.setValue('.$i.', 3, '.$tempvalue4.');'."\n";
		}
		
		?>
		
        var chart = new google.visualization.ScatterChart(document.getElementById('chart_div'));
        chart.draw(data, {width: 900, height: 500,
                          title: 'Average records per day-of-year +/- Standard Error for publisher <?php echo $prov; if($dataset<>'all') { echo ', data resource '.$dataset; } ?>',
                          hAxis: {title: 'Day of year',
						  viewWindowMode: 'explicit',
						  viewWindow: {
							max: 366,
							min: 0
							}
						  },
                          vAxis: {title: 'Average records among years', minValue: 0},
                          legend: 'none',
						  pointSize: 2,
						  series: {
							0: {color: 'black', lineWidth: 1},
							1: {color: 'grey', lineWidth: 0},
							2: {color: 'grey', lineWidth: 0}
						  }
                         });

		var tableData = new google.visualization.DataTable();
        tableData.addColumn('string', 'Day of year');
        tableData.addColumn('number', 'Average Records');
		tableData.addColumn('number', 'Standard Error');
		
<?php

		echo 'tableData.addRows('.$length.');'."\n";
		for ($i=0; $i<$length; $i++) {
		
		$dia=1;
		$fecha='';
		for ($j=1; $j<=12; $j++) {
			if ($j==1 or $j==3 or $j==5 or $j==7 or $j==8 or $j==10 or $j==12) {
				for ($k=1; $k<=31; $k++) {
					if ($data[$i][0]==$dia) {
						$fecha=$k.'/'.$j;
					}
					$dia++;
				}
			} else if ($j==4 or $j==6 or $j==9 or $j==11) {
				for ($k=1; $k<=30; $k++) {
					if ($data[$i][0]==$dia) {
						$fecha=$k.'/'.$j;
					}
					$dia++;
				}
			} else if ($j==2) {
				for ($k=1; $k<=29; $k++) {
					if ($data[$i][0]==$dia) {
						$fecha=$k.'/'.$j;
					}
					$dia++;
				}
			}
			
		}
		
			echo 'tableData.setValue('.$i.', 0, \''.$fecha.' ('.$data[$i][0].')\');'."\n";
			echo 'tableData.setValue('.$i.', 1, '.trim($data[$i][1]).');'."\n";
			echo 'tableData.setValue('.$i.', 2, '.round(sqrt(trim($data[$i][2])),4).');'."\n";
		}

?>
		
		var tableChart = new google.visualization.Table(document.getElementById('table_div'));
        tableChart.draw(tableData, {
			page: 'enable',
			pageSize: 20,
			width: 300,
			title: 'Average records per day-of-year for publisher <?php echo $prov; ?>'
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
    <td><div id="chart_div"></div></td>
	<td><div id="table_div"></div></td>
	</tr></table>
	<br><?php echo $datalink."&nbsp &nbsp".$endbutton; ?>	
  </body>
</html>
