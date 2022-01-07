<pre>
<?php

function CreateP($file,$ac){
	global $g2p;
	$file="./pbw/$file.gif";
	$s=ceil(sqrt(count($ac)*4));
	$w=ceil($s/4)*4;
	$h=ceil($s*$s/$w);
	$gd = imagecreatetruecolor($w, $h);
	$c=imagecolorallocate($gd,255,255,255);
	$y=-1;
	$k=0;
	$ar=array();
	foreach($ac as $i =>$g) {
		$p=$g2p[$g];
		$ap=str_split($p);
		foreach($ap as $b){
			if ($k%$w==0) {$x=0;$y++;}
			if ($b>0) imagesetpixel($gd,$x,$y,$c);
			//echo "$x,$y: $b\n";
			$x++;
			$k++;
		}		
	}
	imagegif($gd,$file);
	imagedestroy($gd);
	echo "\n$file ($w): OK!";
}
ini_set('memory_limit',-1);
set_time_limit(-1);
error_reporting(E_ALL);
ini_set('display_errors', 1);

$ag=array("x","t","g","k","c","y","s","b","a","w","r","d","m","h","v","n");
foreach($ag as $k => $g)
	$g2p[$g]=str_pad(decbin($k),4,0,STR_PAD_LEFT);
$g2p['u']=$g2p['t'];
$g2p['1']=$g2p['a'];
$g2p['2']=$g2p['c'];
$g2p['3']=$g2p['g'];
$g2p['4']=$g2p['t'];
$g2p['0']=$g2p['n'];

foreach($g2p as $g => $b)
	$g2p[strtoupper($g)]=$b;

$PN=19;
$I=0;
$spl = new SplFileObject('./test.ped');
while(!$spl->eof()){
	$s=trim($spl->fgets());
	$file=str_replace(" ","_",substr($s,0,$PN));
	$ac=explode(" ",substr($s,$PN));
	if ($file=='') continue;
	CreateP($file,$ac);
	$I++;
	//if ($I>0) break;
}
/*$ag=array('a','c','t','g');
$ac=$AC=$MAF=array();
$N=10000;
for($i=0;$i<$N;$i++) 
	$ac[$i]=$ag[rand(0,3)];
for($i=0;$i<$N;$i++) 
	$MAF[$i]=rand(0,2);

for($k=0;$k<9;$k++) {
	$AC=$ac;
	//for($i=0;$i<$N;$i++) if (rand(0,10000)<$MAF[$i]) {
	if($k==5) for ($j=0;$j<2;$j++){
		$r=rand(0,$N-1);
		do{
			$rc=$ag[rand(0,3)];
			$AC[$r]=$rc;
		}while($ac[$r]==$rc);
		
	}
	CreateP("PI_$N"."_$k",$AC);
}*/
?>
