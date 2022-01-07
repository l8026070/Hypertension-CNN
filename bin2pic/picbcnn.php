<pre>
<?php

ini_set('memory_limit',-1);
set_time_limit(-1);
error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', 1);

function CrPT($Ar,$max,$cF){
	$w=count($Ar[0]);
	$h=count($Ar);
	$gd = imagecreatetruecolor($w, $h);
	$c=imagecolorallocate($gd,255,255,255);
	foreach($Ar as $y =>$ar) foreach($ar as $x=>$v){
		$c=round($v/$max*255);
		$C=imagecolorallocate($gd,$c,$c,$c); 		
		imagesetpixel($gd,$x,$y,$C);
	}
	imagepng($gd,$cF);
	imagedestroy($gd);
	echo "\nCrP: $file\n";
}

for($i=0;$i<24;$i++) for($j=0;$j<4;$j++) for($k=0;$k<4;$k++) $F[$i][$j][$k]=0;
$f=0;
for($i=0;$i<4;$i++) for($j=0;$j<4;$j++) for($k=0;$k<4;$k++) for($w=0;$w<4;$w++) 
	if ($i!=$j && $i!=$k && $i!=$w && $j!=$k && $j!=$w && $k!=$w){
		$F[$f][0][$i]=1;
		$F[$f][1][$j]=1;
		$F[$f][2][$k]=1;
		$F[$f][3][$w]=1;
		$f++;
	}

$width = 2*4;
$height = 60000;

$path='../Genome/p2b/';
$CN=5;
$IN=1000;
$YM=8*pow(2,100);

$spr='';
$armp=array();
$I=1;

$arF=array();
if ($handle = opendir(realpath($path))) {
	while (false !== ($file = readdir($handle))){ 
		$afn=explode(".",$file);
		$ext=$afn[1];
		if ($ext!='gif') continue;
		$af=explode("_",$afn[0]);
		$FC=$af[0];
		$N=$af[7];
		if ($N>0) $arF[$FC][$N]=$file;
		
	}
	closedir($handle);
}
foreach($arF as $FC =>$arN){
	$cF="./b2c/$FC.png";
	if (file_exists($cF)) continue;
	$spr.="\n$cF";
	ksort($arN);
	// Initiate
	$in=array();
	foreach($arN as $N =>$file){		
		$f=$path.$file;
		$img = imagecreatefromgif($f);
		for($y = 0; $y < $height; $y++) for($x = 0; $x < $width; $x++) {
			$rgb = imagecolorat($img,$x,$y);
			$Y=$y+($N-1)*$height;
			if ($Y>=$YM) break 3;
			$in[$Y][$x]=$rgb;			
		}
	}
	//print_r($in);die();
	$TI=$Y;
	$TN=ceil(log($TI/8,2));
	$H=pow(2,$TN)*8;
	echo "$TI,$TN,$H\n";
	$maxp=$in;
	$mw=$width;
	$mh=$H*2;
	$TI*=2;
	$cn=$CN;
	echo "<hr>$TI [$cn] => ($mh,$mw)\n";
	for ($N=0;$N<$TN;$N++){
		$in=$maxp;
		$outF=$maxp=array();
		$mh/=2;
		$TI/=2;
		echo "<hr>$TI [$cn] => ($mh,$mw)\n";
		// Conv 4*4
		if ($cn>0){
			foreach($F as $f =>$filter) 
				for($my=0;$my<$mh;$my++) for($mx=0;$mx<$mw;$mx++) if ($my<$TI) 
					for($ky=0;$ky<4;$ky++) for($kx=0;$kx<4;$kx++) 
						$outF[$my][$mx]+=$filter[$ky][$kx]*$in[$ky+$my-2][$kx+$mx-2];
		}else $outF=$in;
		$cn--;
		// MaxPool 2*1
		for($my=0;$my<$mh;$my+=2) for($mx=0;$mx<$mw;$mx++){
			$inm=array($outF[$my][$mx],$outF[$my+1][$mx]);//,$outF[$my+2][$mx],$outF[$my+3][$mx]
			//echo "\n$my:$mx: ".implode(",",$inm);
			$maxp[$my/2][$mx]=max($inm);
		}

	}
	CrPT($maxp,pow(10,1.9*$CN),$cF);
	print_r($maxp);
	$I++;
	if ($I>$IN) break;
}

echo "\n$spr\nOK!";
?>
