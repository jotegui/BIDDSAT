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

$filename=$path.'RecordsByWeekdayAndMonth.txt';

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

$url='./recsdowvsmonth.php?prov='.$prov.'&dataset='.$dataset.'&db='.$db;

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

		echo 'data.addColumn(\'number\', \'Mon\');'."\n";
		echo 'data.addColumn(\'number\', \'Tue\');'."\n";
		echo 'data.addColumn(\'number\', \'Wed\');'."\n";
		echo 'data.addColumn(\'number\', \'Thu\');'."\n";
		echo 'data.addColumn(\'number\', \'Fri\');'."\n";
		echo 'data.addColumn(\'number\', \'Sat\');'."\n";
		echo 'data.addColumn(\'number\', \'Sun\');'."\n";
		
		
		echo 'data.addRows(12);'."\n";
		
		
		for ($i=0; $i<$length; $i++ ) {
			if ($data[$i][0]>0 and $data[$i][1]!='NULL') {
				switch ($data[$i][1]) {
					case 'Monday':
						$dayvalue=0;
						break;
					case 'Tuesday':
						$dayvalue=1;
						break;
					case 'Wednesday':
						$dayvalue=2;
						break;
					case 'Thursday':
						$dayvalue=3;
						break;
					case 'Friday':
						$dayvalue=4;
						break;
					case 'Saturday':
						$dayvalue=5;
						break;
					case 'Sunday':
						$dayvalue=6;
						break;
				}
				if ($log=='true') {
					$tempvalue=log(floatval(trim($data[$i][2])));
				} else {
					$tempvalue=trim($data[$i][2]);
				}
				echo 'data.setCell('.($data[$i][0]-1).','.($dayvalue).','.round($tempvalue,4).');'."\n";
			}
		}
		
?>
        var table = new google.visualization.Table(document.getElementById('table_div'));
		
		var formatter = new google.visualization.ColorFormat();
		formatter.addGradientRange(<?php echo $min+0; ?>, <?php echo $max+1; ?>, 'transparent', 'white', 'green');
		<?php
		for ($i=0; $i<7; $i++) {
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
 	<h3>Record density for each day of the week and each month.</h3>
	Data from publisher <?php echo $prov; if($dataset<>'all') { echo ', data resource '.$dataset; } ?>. The greener, the more records that fall in that day.<br>
    <div id='table_div'></div><br>
	<input type="radio" name="logscale" value="Arithmetic" onclick="window.location='<?php echo $url; ?>&log=false';" <?php echo $aritchecked; ?>>Arithmetic &nbsp &nbsp
	<input type="radio" name="logscale" value="Logarithmic" onclick="window.location='<?php echo $url; ?>&log=true';" <?php echo $logchecked; ?>>Logarithmic
	<br><?php echo $datalink."&nbsp &nbsp".$endbutton; ?>
  </body>
</html>