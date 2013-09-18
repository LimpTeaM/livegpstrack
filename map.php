<?php
//error_reporting(E_ALL);
//ini_set("display_errors", 1);
session_start();
header('Content-Type: text/html; charset=utf-8');
include "db.php";


if (!$link) {
    echo "нет коннекта";
    exit;
} else {
    $result=mysqli_query($link, "SELECT * from points");

};

$OUT="
<!DOCTYPE html>
<html>
<head>
    <title>Карта</title>
    <meta charset='utf-8' />

    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
<link rel='stylesheet' href='http://cdn.leafletjs.com/leaflet-0.6.4/leaflet.css' />
 <!--[if lte IE 8]>
     <link rel='stylesheet' href='http://cdn.leafletjs.com/leaflet-0.6.4/leaflet.ie.css' />
 <![endif]-->
<script src='http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js'></script>
<script src='http://cdn.leafletjs.com/leaflet-0.6.4/leaflet.js'></script>
<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js'></script>
<script src='geocoder.js'></script>
<link href='/bootstrap/css/bootstrap.css' rel='stylesheet'>
<link href='/bootstrap/css/bootstrap-responsive.css' rel='stylesheet'>
</head>
<body>
<div class='row-fluid'>
<div class='span8 offset1'>
<ul class='nav nav-tabs'>
<li class='active'><a href='map.php'><i class='icon-map-marker'></i>Точки</a><li>
<li><a href='showtrack.php'><i class='icon-fullscreen'></i>Мониторинг</a></li>
<li><a href='poi.php?poi=show'><i class='icon-info-sign'></i>Информация</a><li>
</ul>
</div>
</div>
<div class='row-fluid'>
    <div class='span5 offset1'><div id='map' style='height: 700px; border-radius: 6px; box-shadow: 0 8px 16px -8px #222;'></div></div>
    <div class='span3'>
    <span class='label label-info'>Введите имя точки и нажмите на карту</span>
    <label>Имя точки:</label> <input type='text' id='name'><br>
    <span class='label label-info'>Ваше местоположение:</span>
    <div id='location'></div>
</div>
</div>
<script>
      var map = L.map('map').setView([55.76, 37.64], 9);
      map.locate({setView: true, enableHighAccuracy: true, maxZoom: 18 });
    //  L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {maxZoom: 18,}).addTo(map);
        L.tileLayer('http://{s}.tile.cloudmade.com/6a47c48f55494a5f92c09fce0caf2051/997/256/{z}/{x}/{y}.png', {maxZoom: 18,}).addTo(map);
	map.on('locationfound', onLocationFound);

";

while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
 $OUT.= "L.marker([".$row['lat'].",".$row['lon']."]).addTo(map).bindPopup('".$row['name']."');";
} 

$OUT.="
var popup = L.popup();
map.on('click', function(e) {
var name = document.getElementById('name').value;
if (name == '') {
popup.setLatLng(e.latlng).setContent('Поле пустое! Введите что-нибудь.').openOn(map);
exit;
}
else {
$.ajax({
  type: 'POST',
  url: 'add.php',
  data: { name: name, lat: e.latlng.lat, lon: e.latlng.lng}
}).done(function( msg ) {
decodeURIComponent(msg);
popup.setLatLng(e.latlng).setContent(msg).openOn(map);
});
}});
</script>
<br>
<br>
<div class='row'>
<div class='span12'><center><span class='label label-info'>limpteam 2013</span></center></div>
</div>
</body>
</html> ";
echo $OUT;
?>