<?php
header('Content-Type: text/html; charset=utf-8');
include "db.php";

if (isset($_GET['name'])) {
$name=mysqli_real_escape_string($link,$_GET['name']);
$lat=mysqli_real_escape_string($link, $_GET['lat']);
$lon=mysqli_real_escape_string($link, $_GET['lon']);
$speed=mysqli_real_escape_string($link, $_GET['speed']);
$speed=intval($speed)*3.6;
$hdop=mysqli_real_escape_string($link, $_GET['hdop']);
$hash=mysqli_real_escape_string($link, $_GET['hash']);
if (!isset($_GET['hash']) or ($_GET['hash'] == '')) {
$hash=$name;
}
$sql=mysqli_query($link,"INSERT INTO `tracking` (`name`, `lat`, `lon`, `hdop`, `timestamp`, `speed`, `hash`) VALUES ('$name','$lat','$lon', '$hdop', now(),'$speed','$hash') ON DUPLICATE KEY update 
`lat`='$lat',
`lon`='$lon',
`hdop`='$hdop',
`timestamp`=now(),
`speed`='$speed'");
$sqlarchive=mysqli_query($link, "INSERT INTO archive (name, lat, lon, hdop, timestamp, speed, hash) VALUES ('$name','$lat','$lon','$hdop', now(),'$speed', '$hash')");
if (($sql) and ($sqlarchive)) {
echo "выполнено";
}
else {
echo "не выполнено";
echo mysql_error();
}
}
?>