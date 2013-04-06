<?php
header('Content-Type: text/html; charset=utf-8');

include "db.php"; 

if (!empty($_POST['name']) &&  isset($_POST['lat']) && isset($_POST['lon'])) {
$lat=mysqli_real_escape_string($link, $_POST['lat']);
$lon=mysqli_real_escape_string($link, $_POST['lon']);
$name=mysqli_real_escape_string($link, $_POST['name']);
$latlon=mysqli_query($link, "INSERT INTO points (name, lat, lon) VALUES ('$name', '$lat','$lon');");
if ($latlon) {
echo "Добавили новую точку ".$name." <br> <a href='map.php'><center>Обновить</a>";
}
else
echo "Ошибка! Точка уже существует! Используйте другое название.";
};
?>