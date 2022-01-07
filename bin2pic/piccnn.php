<style>
td {width:25px;height:25px;padding:0px;border: 1px solid #000;text-align:center;}
table {display:inline-table;padding:5px;}
</style>
<pre>
<?php

ini_set('memory_limit',-1);
set_time_limit(-1);
error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', 1);

function ArT($Ar,$max=100){
	echo "<table border=0 cellpadding=0 cellspacing=0>\n";
	foreach($Ar as $ar){
		echo "<tr>";
		foreach($ar as $v){
			//if ($v>$max) $v=$max;			
			//$c=round(200*$v/$max)+55;
			
			$v-=$max/2;
			$c=round(2*$v/$max*255);
			$v=round(2*$v/$max*100);
			echo "<td style='background-color: rgb($c,$c,$c)'>$v</td>";
		}
		echo "</tr>\n";
	}
	echo "</table>";
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
//foreach($F as $f => $Ar)  ArT($Ar,1);

$path='../Genome/pgbw/';
$CN=4;
$TN=8;
$IN=0;

$spr='';
$armp=array();
$I=0;
if ($TN<$CN) $TN=$CN;
if ($handle = opendir(realpath($path))) {
	while (false !== ($file = readdir($handle))){ 
		$astr=array();
		$afn=explode(".",$file);
		$ext=$afn[count($afn)-1];
		if ($ext!='gif') continue;
		$spr.="\n$file";
		$f=$path.$file;
		$img = imagecreatefromgif($f);
		$width = imagesx($img);
		$height = imagesy($img);
		//$width=$height=200;
		// Initiate
		$in=array();
		for($y = 0; $y < $height; $y++) for($x = 0; $x < $width; $x++) {
			$rgb = imagecolorat($img,$x,$y);
			$cols = imagecolorsforindex($img, $rgb);
			$in[$y][$x]=0;
			if (array_sum($cols)>10) $in[$y][$x]=1;			
		}
		//print_r($in);die();
		$maxp=$in;
		$mw=$width*2;
		$mh=$height*2;
		$cn=$CN;
		for ($N=0;$N<$TN;$N++){			
			$in=$maxp;
			$outF=$maxp=array();
			$mw=floor($mw/2);
			$mh=floor($mh/2);

			//ArT($in);echo "<hr>";

			// Conv 4*4
			if ($cn>0) foreach($F as $f =>$filter)
				for($my=0;$my<$mh;$my++) for($mx=0;$mx<$mw;$mx++) 
					for($ky=0;$ky<4;$ky++) for($kx=0;$kx<4;$kx++) 
						$outF[$my][$mx]+=$filter[$ky][$kx]*$in[$ky+$my-2][$kx+$mx-2];
			else $outF=$in;
			$cn--;
			// MaxPool 2*2
			for($my=0;$my<$mh;$my+=2) for($mx=0;$mx<$mw;$mx+=2)
				$maxp[$my/2][$mx/2]=max($outF[$my][$mx],$outF[$my+1][$mx],$outF[$my][$mx+1],$outF[$my+1][$mx+1]);
		}
		
		// Output		
		$armp[$I]=$maxp;
		$I++;
		if ($I>$IN) break;
	}
	closedir($handle);
}
//echo max(max(max($armp)));die();
foreach($armp as $I =>$maxp) ArT($maxp,pow(10,1.9*$CN)); die();

$max_min=array();
foreach($armp as $I =>$maxp) foreach($maxp as $y =>$ax) foreach($ax as $x =>$v) {
	if (!isset($max_min[$y][$x])) $max_min[$y][$x]=array($v,$v);
	if ($v>$max_min[$y][$x][0]) $max_min[$y][$x][0]=$v;
	if ($v<$max_min[$y][$x][1]) $max_min[$y][$x][1]=$v;
}

$norm=array();
foreach($armp as $I =>$maxp) foreach($maxp as $y =>$ax) foreach($ax as $x =>$v) {
	$d=$max_min[$y][$x][0]-$max_min[$y][$x][1];
	if ($d==0) 
		$norm[$I][$y][$x]=0;
	else 
		$norm[$I][$y][$x]=round(100*($v-$max_min[$y][$x][1])/$d);
}
$W=0;
foreach($norm as $I =>$ar) {		
	ArT($ar);	
	$W+=$mw/2;
	if ($W>=40){
		echo "<br>";
		$W=0;
	}	
}

echo "$spr\nOK!";
?>
