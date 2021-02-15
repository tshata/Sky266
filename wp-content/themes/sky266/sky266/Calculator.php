 <?php
function calculateCubic($length,$width,$height){


	$total=$length*$width*$height;
	return $CBM;

}
 if(isset($_GET['length']) && isset($_GET['width']) && isset($_GET['height'])){

$length=$_GET['length'];
$width=$_GET['width'];
$height=$_GET['height'];
calculateCubic($_GET['length'],$_GET['width'],$_GET['height']);

}
?>