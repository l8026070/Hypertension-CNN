<pre>
<?php
ini_set('memory_limit',-1);
set_time_limit(-1);
error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors',1);

$t = -microtime(1);


$ag=array(1=>"0001",2=>"0010",3=>"0100",4=>"1000",0=>"0000");
$spl = new SplFileObject('../Genotype/405.clean.nodup.omnionly.bin.ped');

$W=1305838;
$SNP=$W/2;
$PN=25;

$w=4*2;
$h=60000;

$I=0;
while(!$spl->eof()){
	$str=trim($spl->fgets());
	$nm=str_replace(" ","_",substr($str,0,$PN));
	$str=substr($str,$PN);
	$as=explode(" ",$str);
	$arB=array();
	$N=0;
	foreach($as as $i=> $v)
		$arB[floor($i/2)].=$ag[$v];
	foreach($arB as $n => $sb){
		if ($n%$h==0){
			$gd = imagecreatetruecolor($w,$h);
			$cl=imagecolorallocate($gd,255,255,255);
			$y=-1;
		}
		$y++;
		$ca=str_split($sb);
		foreach($ca as $x=>$b) if ($b>0) 
			imagesetpixel($gd,$x,$y,$cl);
		if ($n%$h==$h-1){
			$N++;
			$file="p2b/$nm"."_$N.gif";
			imagegif($gd,$file);
			imagedestroy($gd);	
		}
	}
	$N++;
	$file="p2b/$nm"."_$N.gif";
	imagegif($gd,$file);
	imagedestroy($gd);
	echo "\n$file";
	$I++;
	//if ($I>10) break;
}
echo "\n$I:".($t+microtime(1));
?>