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