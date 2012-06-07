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

set_time_limit(0);
ini_set("memory_limit","1024M");

$filename='./images/D'.substr($db, 5, 6).'P'.$prov.'R'.$dataset.'T'.strtoupper($_GET['t']).'.png';
$thumbnailPath='./thumbs/D'.substr($db, 5, 6).'P'.$prov.'R'.$dataset.'T'.strtoupper($_GET['t']).'.thumb';

$check=file_exists($filename);

$f=$_GET['f'];
$p=$_GET['p'];
$db=$_GET['db'];
$prov=$_GET['prov'];
$dataset=$_GET['dataset'];
if ($_GET['t']=='m') {
	$t='map';
} else if ($_GET['t']=='d') {
	$t='diatom';
} else {
	die ("Something went wrong, please go back and start over");
}

$path='./data/'.$db.'/'.$prov.'/';
if($dataset!='all') {
	$path.=$dataset.'/';
}
$datafile=$path.$t.'.txt';



if ($check==1) {
	echo '<p>The image has been created. Click on the thumbnail below to see it full-size. You can download it by right-clicking and selecting "Save as..."</p>';
	echo '<p><a href="'.$filename.'" target="_blank"><img src="'.$thumbnailPath.'" /></a></p>'.'<a href="'.$datafile.'">Data</a>&nbsp &nbsp'.$endbutton;
} else {


$x=array();
$y=array();
$cat=array();
$clases=array();

$lienzoX=1;
$lienzoY=1;


if ($t=="map") {

	$maxlat=90;
	$maxlon=180;
	$minlat=-90;
	$minlon=-180;
	$ancholey=43*$f;
	$separaley=10*$f;

	$lienzoX=($maxlon-$minlon)*$f;
	$lienzoY=($maxlat-$minlat)*$f;
	$centroX=-$minlon*$f;
	$centroY=$maxlat*$f;
	
	$ima=imagecreatefromjpeg('./files/FondoMundo.jpg');
	list($width,$height)=getimagesize('./files/FondoMundo.jpg');
	
	$im=imagecreatetruecolor($lienzoX+$ancholey,$lienzoY);
	imagecopyresampled($im,$ima,0,0,(180+$minlon)*$width/360,(90-$maxlat)*$height/180,$lienzoX,$lienzoY,($maxlon-$minlon)*$width/360,($maxlat-$minlat)*$height/180);
}

if ($t=="diatom") {

	$f=$f/2;
	
	$lienzoX=600*$f;
	$lienzoY=600*$f;
	$centroX=$lienzoX/2;
	$centroY=$lienzoY/2;
	
	$ancholey=100*$f;
	$separaley=20*$f;
	
	$im=imagecreatetruecolor($lienzoX+$ancholey,$lienzoY);
}

$lines=file($datafile);

foreach ($lines as $line_num => $line) {
	
	$barra1=strpos($line,'|');
	$barra2=strpos($line,'|',$barra1+1);
	$barra3=strpos($line,'|',$barra2+1);;
	
	$x[$line_num]=$centroX+((substr($line,$barra1+1,$barra2-$barra1-1))*$f);
	
	$y[$line_num]=$centroY+((substr($line,0,$barra1)*(-1))*$f);
	
	$cat[$line_num]=substr($line,$barra3+1,strlen($line)-$barra3)*1;
}

unset($lines);



$roundpointcolor[0]=imagecolorallocate($im,0x00,0x00,0x99);
$roundpointcolor[1]=imagecolorallocate($im,0x00,0x00,0xFF);
$roundpointcolor[2]=imagecolorallocate($im,0x00,0x66,0xFF);
$roundpointcolor[3]=imagecolorallocate($im,0x00,0xCC,0xCC);
$roundpointcolor[4]=imagecolorallocate($im,0x00,0xFF,0x99);
$roundpointcolor[5]=imagecolorallocate($im,0xCC,0xFF,0x66);
$roundpointcolor[6]=imagecolorallocate($im,0xFF,0xFF,0x66);
$roundpointcolor[7]=imagecolorallocate($im,0xFF,0xCC,0x33);
$roundpointcolor[8]=imagecolorallocate($im,0xFF,0x99,0x00);
$roundpointcolor[9]=imagecolorallocate($im,0xFF,0x66,0x00);
$roundpointcolor[10]=imagecolorallocate($im,0xFF,0x00,0x00);
$roundpointcolor[11]=imagecolorallocate($im,180,4,4);


$textcolor=imagecolorallocate($im,0xFF,0xFF,0xFF);
$marcacolor=imagecolorallocate($im,0xAA,0xAA,0xAA);

for ($i=0; $i<count($x); $i++) {

	imagefilledellipse($im,$x[$i],$y[$i],$p,$p,$roundpointcolor[$cat[$i]]);
	
}

$limiteleyenda=12;

for ($i=0; $i<$limiteleyenda; $i++) {

if ($i==0) {
	$texto="1 - ".pow(2,$i+1);
} else if ($i<$limiteleyenda-1) {
	$texto=(pow(2,$i)+1)." - ".pow(2,$i+1);
} else {
	$texto="> ".(pow(2,$i));
}
	
	imagefilledellipse($im,$lienzoX+$separaley,($i+1)*$separaley,$p+5,$p+5,$roundpointcolor[$i]);
	imagestring($im,$f/2,$lienzoX+$separaley+10,(($i+1)*$separaley)-(($p+5)/3),$texto,$textcolor);
}

if ($t=='diatom') {
	imageline($im,$centroX,$centroY-(2*$f),$centroX+(2*$f),0,$marcacolor);
	imagestring($im,$f*$f*2-5,$centroX+(2*$f),$separaley-6,'Jan-1',$marcacolor);
	imagestring($im,$f*$f*2-5,$centroX-(10*(3+$f)),$separaley-6,'Dec-31',$marcacolor);
} else if ($t=='map') {
	imageline($im,$lienzoX/2,0,$lienzoX/2,$lienzoY,$marcacolor);
	imageline($im,0,$lienzoY/2,$lienzoX, $lienzoY/2,$marcacolor);
}

if ($t=='diatom') {
	$t2='D';
} else if ($t=='map') {
	$t2='M';
}
$db2=substr($db, 5,6);
$fileres='./images/D'.$db2.'P'.$prov.'R'.$dataset.'T'.$t2.'.png';
imagepng($im,$fileres);

$thumbnailWidth = 300;
$thumbnailHeight = 300;

$srgbPath = './files/sRGB_v4_ICC_preference.icc';

$image = new Imagick($fileres);

$imwidth = $image->getImageWidth();
$imheight = $image->getImageHeight();

$srgb = file_get_contents($srgbPath);
$image->profileImage('icc', $srgb);

$image->stripImage();

$image->setImageColorspace(Imagick::COLORSPACE_SRGB);

$fitWidth = ($thumbnailWidth / $imwidth) < ($thumbnailHeight / $imheight);

$image->thumbnailImage(
$fitWidth ? $thumbnailWidth : 0,
$fitWidth ? 0 : $thumbnailHeight
);

$imagePathParts = pathinfo($fileres);

if ($t=='diatom') {
	$t2='D';
} else if ($t=='map') {
	$t2='M';
}
$db2=substr($db, 5,6);
$thumbnailPath='./thumbs/D'.$db2.'P'.$prov.'R'.$dataset.'T'.$t2.'.thumb';

$image->writeImage($thumbnailPath);
$image->clear();
$image->destroy();
	
	echo '<p>The image has been created. Click on the thumbnail below to see it full-size. You can download it by right-clicking and selecting "Save as..."</p>';
	echo '<p><a href="'.$fileres.'" target="_blank"><img src="'.$thumbnailPath.'" /></a></p>'.'<a href="'.$datafile.'">Data</a>&nbsp &nbsp'.$endbutton;

imagedestroy($im);
}
?>
