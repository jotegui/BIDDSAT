<?php

include 'header.php';

$filename=$path.'map.txt';

$datalink = checkFile($filename, $db, $prov, $dataset);

session_start();

$tipo='m';
$factor=10;
$punto=4;

$filename='./images/D'.substr($db, 5, 6).'P'.$prov.'R'.$dataset.'TM.png';
$thumbnailPath='./thumbs/D'.substr($db, 5, 6).'P'.$prov.'R'.$dataset.'TM.thumb';
$check=file_exists($filename);

if ($check==1) {
	echo '<p>The image has been created. Click on the thumbnail below to see it full-size. You can download it by right-clicking and selecting "Save as..."</p>';
	echo '<p><a href="'.$filename.'" target="_blank"><img src="'.$thumbnailPath.'" /></a></p>'.$datalink.'&nbsp &nbsp'.$endbutton;
} else {
	echo '
	<html>
	<head>
	</head>
	<body>
	<p>Generating image...</p>
	<form action="./muestrares.php?db='.$db.'&prov='.$prov.'&dataset='.$dataset.'" method="GET" name="frm">
	<input type="hidden" name="t" value="'.$tipo.'" />
	<input type="hidden" name="f" value="'.$factor.'" />
	<input type="hidden" name="p" value="'.$punto.'" />
	<input type="hidden" name="db" value="'.$db.'" />
	<input type="hidden" name="prov" value="'.$prov.'" />
	<input type="hidden" name="dataset" value="'.$dataset.'" />
	</form>
	<p>
	<script language="JavaScript">
		document.frm.submit();
	</script>
	</body>
	</html>
	';
}
?>