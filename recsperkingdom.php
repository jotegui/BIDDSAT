<?php

include 'header.php';

$filename=$path.'RecordsByKingdom.txt';

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
        data.addColumn('string', 'Kingdom');
        data.addColumn('number', 'Records');
        
<?php

		echo 'data.addRows('.$length.');'."\n";
		
		for ($i=0; $i<$length; $i++) {
			echo 'data.setValue('.$i.', 0,\''.$data[$i][0].'\');'."\n";
			echo 'data.setValue('.$i.', 1,'.trim($data[$i][1]).');'."\n";
		}

?>

        var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
        chart.draw(data, {width: 750, height: 600, title: 'Records per kingdom for publisher <?php echo $prov; if($dataset<>'all') { echo ', data resource '.$dataset; } ?>'});
		
		var tableData = new google.visualization.DataTable();
        tableData.addColumn('string', 'Kingdom');
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
			title: 'Records per dataset for publisher <?php echo $prov; ?>'
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