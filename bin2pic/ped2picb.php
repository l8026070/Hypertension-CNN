<pre>
<?php

function CreateP($file,$ac){
	global $g2p;
	$file="./pgb/$file.png";
	$w=ceil(sqrt(count($ac)/2));
	$gd = imagecreatetruecolor($w, $w);
	imagefill($gd, 0, 0, imagecolorallocate($gd, 0, 0, 0)); 
	$y=-1;
	$k=0;
	for($i=0;$i<2;$i++) $ac[]='x';
	foreach($ac as $i =>$g) {	
		if ($i%2==1) {
			$g=$ac[$i].$ac[$i-1];
			$p=$g2p[$ac[$i]].$g2p[$ac[$i-1]];
			$b=bindec($p);			
			//echo "\n$g=$p:$b";
			if ($k%$w==0) {$x=0;$y++;}
			$r=$g=$b;
			$c=imagecolorallocate($gd,$r,$g,$b); 		
			imagesetpixel($gd,$x,$y,$c);
			//echo "\n$x,$y: $r,$g,$b";
			$x++;
			$k++;
		}
	}
	imagepng($gd,$file);
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
$ac=array();
$N=10000000;
for($i=0;$i<$N;$i++) $ac[$i]=$ag[rand(0,3)];
CreateP("PI_$N",$ac);*/
?>
