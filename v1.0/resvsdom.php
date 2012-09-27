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

$filename=$path.'ResourcesVsDayofmonth.txt';

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
		if ($temp[$j]==$data[$i][0]) {
			$match=1;
			break;
		}
	}
	
	if ($match==0) {
		array_push($temp, $data[$i][0]);
	}
	
}

$setno=$temp;
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
		
		for ($i=1; $i<=31; $i++) {
			echo 'data.addColumn(\'number\', \''.$i.'\');'."\n";
		}


		echo 'data.addRows('.count($setno).');'."\n";
		for ($i=0; $i<count($setno); $i++) {
			echo 'data.setCell('.$i.', 0, '.$setno[$i].');'."\n";
		}
		for ($i=0; $i<count($data); $i++ ) {
			echo 'data.setCell('.array_search($data[$i][0], $setno).', '.$data[$i][1].', '.trim($data[$i][2]).');'."\n";
		}

?>
        var table = new google.visualization.Table(document.getElementById('table_div'));
		
		var formatter = new google.visualization.ColorFormat();
		formatter.addGradientRange(<?php echo $min; ?>, <?php echo $max+1; ?>, 'transparent', 'white', 'green');
		<?php
		for ($i=1; $i<32; $i++) {
			echo 'formatter.format(data, '.$i.');'."\n";
		}
		?>
	        table.draw(data, {
			showRowNumber: false,
			page: 'disable',
			sort: 'disable',
			allowHtml: true,
			width: '100%'
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