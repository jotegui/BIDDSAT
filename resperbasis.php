<?php

include 'header.php';

$filename=$path.'ResourcesByBasis.txt';

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
        data.addColumn('string', 'Basis of record');
        data.addColumn('number', 'Resources');
        
<?php

		echo 'data.addRows('.$length.');';
		
		for ($i=0; $i<$length; $i++) {
			switch ($data[$i][0]) {
			case 0:
				$data[$i][0]='Unknown';
				break;
			case 1:
				$data[$i][0]='Observation';
				break;
			case 2:
				$data[$i][0]='Specimen';
				break;
			case 3:
				$data[$i][0]='Living';
				break;
			case 4:
				$data[$i][0]='Germplasm';
				break;
			case 5:
				$data[$i][0]='Fossil';
				break;
			case 6:
				$data[$i][0]='Literature';
				break;
			default:
				$data[$i][0]='Other';
			}
			echo 'data.setValue('.$i.', 0, \''.trim($data[$i][0]).'\');'."\n";
			echo 'data.setValue('.$i.', 1, '.trim($data[$i][1]).');'."\n";
		}

?>
        var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
        chart.draw(data, {width: 750, height: 600, title: 'Datasets per type of record for publisher <?php echo $prov; ?>'});

		var tableData = new google.visualization.DataTable();
        tableData.addColumn('string', 'Basis of record');
        tableData.addColumn('number', 'Collections');
		
<?php

		echo 'tableData.addRows('.$length.');'."\n";
		for ($i=0; $i<$length; $i++) {
			echo 'tableData.setValue('.$i.', 0, \''.$data[$i][0].'\');'."\n";
			echo 'tableData.setValue('.$i.', 1, '.trim($data[$i][1]).');'."\n";
		}

?>
		
		var tableChart = new google.visualization.Table(document.getElementById('table_div'));
        tableChart.draw(tableData, {width: 250, title: 'Datasets per type of record for publisher <?php echo $prov; ?>'});
		
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