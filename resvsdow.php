<?php

include 'header.php';

$filename=$path.'ResourcesVsDayofweek.txt';

$datalink = checkFile($filename, $db, $prov, $dataset);

$file=file($filename);

$data=array();

foreach($file as $line) {
	$temparray=explode("\t", $line);
	array_push($data, $temparray);
}

array_shift($data);

$length=count($data);

$maxmintemp=array();
$temp=array();

for ($i=0; $i<$length;$i++) {
	array_push($maxmintemp, trim($data[$i][2]));
	
	$match=0;
	
	for ($j=0; $j<count($temp); $j++) {
	
		if ($data[$i][0]==$temp[$j][0]) {
			$match=1;
			$pos=$j;
			break;
		}
	
	}
	
	$mon=0;
	$tue=0;
	$wed=0;
	$thu=0;
	$fri=0;
	$sat=0;
	$sun=0;
	
	switch ($data[$i][1]) {
	
		case 'Monday':
			$mon=trim($data[$i][2]);
			$daypos=1;
			break;
		case 'Tuesday':
			$tue=trim($data[$i][2]);
			$daypos=2;
			break;
		case 'Wednesday':
			$wed=trim($data[$i][2]);
			$daypos=3;
			break;
		case 'Thursday':
			$thu=trim($data[$i][2]);
			$daypos=4;
			break;
		case 'Friday':
			$fri=trim($data[$i][2]);
			$daypos=5;
			break;
		case 'Saturday':
			$sat=trim($data[$i][2]);
			$daypos=6;
			break;
		case 'Sunday':
			$sun=trim($data[$i][2]);
			$daypos=7;
			break;
	}
	
	if ($match==0) {
		array_push($temp, array($data[$i][0], $mon, $tue, $wed, $thu, $fri, $sat, $sun));
	} else {
		$temp[$pos][$daypos]=trim($data[$i][2]);
	}
}

$chartdata=$temp;

$min=0;
$max=max($maxmintemp);

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

		echo 'data.addColumn(\'number\', \'Resource ID\');'."\n";
		echo 'data.addColumn(\'number\', \'Mon\');'."\n";
		echo 'data.addColumn(\'number\', \'Tue\');'."\n";
		echo 'data.addColumn(\'number\', \'Wed\');'."\n";
		echo 'data.addColumn(\'number\', \'Thu\');'."\n";
		echo 'data.addColumn(\'number\', \'Fri\');'."\n";
		echo 'data.addColumn(\'number\', \'Sat\');'."\n";
		echo 'data.addColumn(\'number\', \'Sun\');'."\n";


		echo 'data.addRows('.count($chartdata).');'."\n";

		for ($i=0; $i<count($chartdata); $i++ ) {
			for ($j=0; $j<count($chartdata[$i]); $j++) {
				echo 'data.setCell('.$i.', '.$j.', '.$chartdata[$i][$j].');'."\n";
			}
		}

?>
        var table = new google.visualization.Table(document.getElementById('table_div'));
		
		var formatter = new google.visualization.ColorFormat();
		formatter.addGradientRange(<?php echo $min; ?>, <?php echo $max+1; ?>, 'transparent', 'white', 'green');
		<?php
		for ($i=1; $i<8; $i++) {
			echo 'formatter.format(data, '.$i.');'."\n";
		}
		?>
	        table.draw(data, {
			showRowNumber: false,
			page: 'disable',
			sort: 'disable',
			allowHtml: true,
			width: '50%'
			});
		}
    </script>
  </head>

  <body>
	<h3>Record density for each day of the week among data resources.</h3>
	Data from publisher <?php echo $prov; if($dataset<>'all') { echo ', data resource '.$dataset; } ?>. The greener, the more records that fall in that day.<br>
    <div id='table_div'></div>
	<br><?php echo $datalink."&nbsp &nbsp".$endbutton; ?>
  </body>
</html>