<?php

include 'header.php';

$filename=$path.'RecordsByDayAndMonth.txt';

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
	$min=floor(log($min));
	$max=ceil(log($max));
} else {
	$aritchecked='checked';
	$logchecked='';
}

$url='./recsdomvsmonth.php?prov='.$prov.'&dataset='.$dataset.'&db='.$db;

?>

<html>
  <head>
    <script type='text/javascript' src='https://www.google.com/jsapi'></script>
    <script type='text/javascript'>
      google.load('visualization', '1', {packages:['table']});
      google.setOnLoadCallback(drawTable);
      function drawTable() {
        var data = new google.visualization.DataTable();
<?php

		echo 'data.addColumn(\'number\', \'Jan\');';
		echo 'data.addColumn(\'number\', \'Feb\');';
		echo 'data.addColumn(\'number\', \'Mar\');';
		echo 'data.addColumn(\'number\', \'Apr\');';
		echo 'data.addColumn(\'number\', \'May\');';
		echo 'data.addColumn(\'number\', \'Jun\');';
		echo 'data.addColumn(\'number\', \'Jul\');';
		echo 'data.addColumn(\'number\', \'Aug\');';
		echo 'data.addColumn(\'number\', \'Sep\');';
		echo 'data.addColumn(\'number\', \'Oct\');';
		echo 'data.addColumn(\'number\', \'Nov\');';
		echo 'data.addColumn(\'number\', \'Dec\');';

		echo 'data.addRows(31);'."\n";

		for ($i=0; $i<$length; $i++ ) {
			if ($data[$i][0]!=0 or $data[$i][1]!=0) {
				if ($log=='true') {
					$tempvalue=log(floatval(trim($data[$i][2])));
				} else {
					$tempvalue=trim($data[$i][2]);
				}
				echo 'data.setCell('.($data[$i][0]-1).','.($data[$i][1]-1).','.round($tempvalue,4).');'."\n";
			}
		}

?>
        var table = new google.visualization.Table(document.getElementById('table_div'));
		
		var formatter = new google.visualization.ColorFormat();
		formatter.addGradientRange(<?php echo $min; ?>, <?php echo $max+1; ?>, 'transparent', 'white', 'green');
		<?php
		for ($i=0; $i<12; $i++) {
			echo 'formatter.format(data, '.$i.');'."\n";
		}
		?>
	        table.draw(data, {
			showRowNumber: true,
			page: 'disable',
			sort: 'disable',
			allowHtml: true,
			width: '66%'
			});
		}
    </script>
  </head>

  <body>
	<h3>Record density for each day of the year.</h3>
	Data from publisher <?php echo $prov; if($dataset<>'all') { echo ', data resource '.$dataset; } ?>. The greener, the more records that fall in that day.<br>
    <div id='table_div'></div><br>
	<input type="radio" name="logscale" value="Arithmetic" onclick="window.location='<?php echo $url; ?>&log=false';" <?php echo $aritchecked; ?>>Arithmetic &nbsp &nbsp
	<input type="radio" name="logscale" value="Logarithmic" onclick="window.location='<?php echo $url; ?>&log=true';" <?php echo $logchecked; ?>>Logarithmic
	<br><?php echo $datalink."&nbsp &nbsp".$endbutton; ?>
  </body>
</html>