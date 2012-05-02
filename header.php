<?php

session_start();

if (!$_GET['db'] or $_GET['db']=='latest') {
	
	$datadb=scandir('./data/');
	$latest=count($datadb)-1;
	$db=$datadb[$latest];

} else {
	$db=$_GET['db'];
}

if (!$_GET['prov'] or $_GET['prov']=='') {
	$prov=57;
} else {$prov=$_GET['prov'];}

if (!$_GET['dataset'] or $_GET['dataset']=='') {
	$dataset='all';
} else {$dataset=$_GET['dataset'];}

$path='./data/'.$db.'/'.$prov.'/';

if($dataset!='all') {
	$path.=$dataset.'/';
}

$_SESSION['db'] = $db;
$_SESSION['prov'] = $prov;
$_SESSION['dataset'] = $dataset;

$endbutton='<input type="button" value="Back" onclick="window.location=\'http://www.unav.es/unzyec/mzna/biddsat/biddsat.php\'">';

function checkFile($filename, $db, $prov, $dataset) {

	if(file_exists($filename)==0 or filesize($filename)==0) {
		die('There is no data on that version for that publisher<br>'.$endbutton);
	} else {
		$datalink = '<a href="'.$filename.'">Data</a>';
		return $datalink;
	}
}

?>