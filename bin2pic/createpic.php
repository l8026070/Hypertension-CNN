<?php
ini_set('memory_limit',-1);
set_time_limit(-1);
error_reporting(E_ALL);
ini_set('display_errors',1);


//echo phpinfo();die();

$ag=array(1=>"00",2=>"01",3=>"10",4=>"11");
$spl = new SplFileObject('../Genotype/405.clean.nodup.omnionly.bin.ped');
$I=1;
$W=1305837;
$PN=25;

$w=ceil(sqrt($W/12));

$gd = imagecreatetruecolor($w,$w);


$spl->seek($I);
$str=trim($spl->current());
$nm=str_replace(" ","_",substr($str,0,$PN));
$str=substr($str,$PN);

$as=explode(" ",$str);

$y=$x=0;
$c='';
$ac=array();
foreach($as as $k=> $v){
	if ($v==0) $v=rand(1,4);
	$c=$ag[$v].$c;
	//echo "$v:$c\n";
	if ($k%4==3){
		$ac[floor(($k%12)/4)]=bindec($c);
		$c='';
	}
	//print_r($ac);
	if ($k%12==11){
		list($r,$g,$b)=$ac;
		$ac=array();
		$cl=imagecolorallocate($gd,$r,$g,$b);
		imagesetpixel($gd,$x,$y,$cl);
		$x++;
	}
	if ($x==$w){
		$x=0;
		$y++;
	}
	//if ($k>100) break;
}


//header('Content-Type: image/gif');
$file="pg/$nm.gif";
imagegif($gd,$file);//,"$I.gif"
imagedestroy($gd);

echo "$file: OK!";
?>
