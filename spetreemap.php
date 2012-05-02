<?php

include 'header.php';

$filename=$path.'Taxonomy.txt';

$datalink = checkFile($filename, $db, $prov, $dataset);

$file=file($filename);

$data=array();
$kingdoms=array();
$phyla=array();
$classes=array();
$orders=array();
$families=array();

$tempkingdoms=array();
$colorsadded=0;

foreach($file as $line) {
	$temparray=explode("\t", $line);
	$color='';
	
	if($temparray[0]=='NULL') {
		$temparray[0]='Null kingdom';
	} else {
		$temparray[0]=$temparray[0].' kingdom';
	}
	if($temparray[1]=='NULL') {
		$temparray[1]='Null phylum';
	} else {
		$temparray[1]=$temparray[1].' phylum';
	}
	if($temparray[2]=='NULL') {
		$temparray[2]='Null class';
	} else {
		$temparray[2]=$temparray[2].' class';
	}
	if($temparray[3]=='NULL') {
		$temparray[3]='Null order';
	} else {
		$temparray[3]=$temparray[3].' order';
	}
	if($temparray[4]=='NULL') {
		$temparray[4]='Null family';
	} else {
		$temparray[4]=$temparray[4].' family';
	}
	
	for($i=0; $i<count($tempkingdoms); $i++) {
		if ($temparray[0]==$tempkingdoms[$i][0]) {
			$color=$tempkingdoms[$i][1];
			break;
		}
	}
	
	if ($color=='') {
		$colorsadded++;
		array_push($tempkingdoms, array($temparray[0], $colorsadded));
	}
	
	array_push($data, $temparray);
	array_push($kingdoms, array($temparray[0], 'Taxonomy', $temparray[5], 0));
	array_push($phyla, array($temparray[1].' - '.$temparray[0], $temparray[0], $temparray[5], 0));
	array_push($classes, array($temparray[2].' - '.$temparray[1].' - '.$temparray[0], $temparray[1].' - '.$temparray[0], $temparray[5], 0));
	array_push($orders, array($temparray[3].' - '.$temparray[2].' - '.$temparray[1].' - '.$temparray[0], $temparray[2].' - '.$temparray[1].' - '.$temparray[0], $temparray[5], 0));
	array_push($families, array($temparray[4].' - '.$temparray[3].' - '.$temparray[2].' - '.$temparray[1].' - '.$temparray[0], $temparray[3].' - '.$temparray[2].' - '.$temparray[1].' - '.$temparray[0], $temparray[5], $colorsadded));
}


unset($temparray);

array_shift($data);
array_shift($kingdoms);
array_shift($phyla);
array_shift($classes);
array_shift($orders);
array_shift($families);

$length=count($data);

function array_collapse($array) {
	
	$temparray=array();
	
	for ($temp=0; $temp<count($array); $temp++) {
		
		$success=0;
		$foundkey=0;
		
		$temp0=$array[$temp][0];
		$temp1=$array[$temp][1];
		$temp2=$array[$temp][2];
		
		
		for ($i=0; $i<count($temparray); $i++) {
			if ($temparray[$i][0]==$temp0 and $temparray[$i][1]==$temp1) {
				$success=1;
				$foundkey=$i;
			}
		}
		
		if($success==0) {
			array_push($temparray, $array[$temp]);
		} else {
			$temparray[$foundkey][2]=$temparray[$foundkey][2]+$temp2;
		}
		
	}
	
	return $temparray;
}

$final_kingdoms=array_collapse($kingdoms);
$final_phyla=array_collapse($phyla);
$final_classes=array_collapse($classes);
$final_orders=array_collapse($orders);
$final_families=array_collapse($families);

$levels=array_merge($final_kingdoms, $final_phyla, $final_classes, $final_orders, $final_families);

?>

<html>
  <head>
    <script type='text/javascript' src='https://www.google.com/jsapi'></script>
    <script type='text/javascript'>

	google.load("visualization", "1", {packages:["treemap"]});
	google.setOnLoadCallback(drawChart);
	
	function drawChart() {
	
		var data = new google.visualization.DataTable();
		data.addColumn('string', 'Taxon');
		data.addColumn('string', 'Parent');
		data.addColumn('number', 'Number of Species');
		data.addColumn('number', 'Kingdom');
		
		data.addRows([
			
			["Taxonomy", null, 0, 0],
		
<?php

	for($i=0; $i<count($levels); $i++) {
	
		echo '			["'.$levels[$i][0].'", "'.$levels[$i][1].'", '.$levels[$i][2].', '.$levels[$i][3].'],'."\n";
	
	}

?>
		
		]);
		
		var tree = new google.visualization.TreeMap(document.getElementById('visualization'));
		tree.draw(data, {
			maxDepth: 5,
			headerHeight: 15,
			fontColor: 'black',
			showScale: false,
			// default color: 99FFCC
			maxColor: '#0000FF',
			midColor: '#00FF00',
			minColor: '#FF0000',
			maxDepth: 3
		});
	}

	</script>
  </head>

  <body>
 	<h3>Species Tree Map of the taxonomy of publisher <?php echo $prov; if($dataset<>'all') { echo ', data resource '.$dataset; } ?>.</h3>
	Taxonomically hierarchical tree map from kingdom to family. The size of the squares is proportional to the number of different species.<br>
	Left click a level to zoom in. Right click to zoom out one level. Put mouse over a cell to see complete name.<br><br>
    <div id="visualization" style="width: 1200px; height: 800px;"></div>

	
	<?php echo $datalink."&nbsp &nbsp".$endbutton; ?>
  </body>
</html>