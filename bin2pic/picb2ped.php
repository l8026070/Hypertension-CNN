<pre>
<?php

ini_set('memory_limit',-1);
set_time_limit(-1);
error_reporting(E_ALL);
ini_set('display_errors', 1);

$ag=array("x","U","G","k","C","y","s","b","A","w","r","d","m","h","v","0");

foreach($ag as $k => $g)
	$p2g[str_pad(decbin($k),4,0,STR_PAD_LEFT)]=$g;

$I=0;
$str='';
if ($handle = opendir(realpath("./pgb/"))) {
	while (false !== ($file = readdir($handle))){ 
		$astr=array();
		$afn=explode(".",$file);
		$ext=$afn[count($afn)-1];
		if ($ext!='png') continue;
		$f="./pgb/$file";
		$img = imagecreatefrompng($f);
		$width = imagesx($img);
		$height = imagesy($img);
		for($y = 0; $y < $height; $y++) for($x = 0; $x < $width; $x++) {
			$ac=array();
			$rgb = imagecolorat($img,$x,$y);
			$cols = imagecolorsforindex($img, $rgb);
			foreach($cols as $c) $ac[]=$c;
			foreach($ac as $i =>$c) if ($i<3){
				$rs=str_pad(decbin($c),8,0,STR_PAD_LEFT);
				$ac[2*$i+10]=substr($rs,4);
				$ac[2*$i+11]=substr($rs,0,4);
				$ac[2*$i+20]=$p2g[$ac[2*$i+10]];
				$ac[2*$i+21]=$p2g[$ac[2*$i+11]];
			}
			$astr[]=$ac[20];
			$astr[]=$ac[21];
		}
		$str.=str_replace(array("_",".png"),array(" ",""),$file).implode(" ",$astr)."\r\n";
		$I++;
		//if ($I>0) break;
	}
	closedir($handle);
}
file_put_contents("./pgb.ped", $str);
echo "OK!";
?>
