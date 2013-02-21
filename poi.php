<?php
header('Content-Type: text/html; charset=utf-8');

include "db.php";
if (isset($_GET['poi'])) {

$command=mysqli_real_escape_string($link, $_GET['poi']);

switch($command) {

case "show":
$sql= mysqli_query($link, "SELECT * from points");
while ($row = mysqli_fetch_array($sql, MYSQLI_ASSOC)) {
echo "Широта: ".$row['lat']."<br>Долгота: ".$row['lon']."<br>Имя: ".$row['name']."<br><hr>";
};
break;

case "name":
$sql= mysqli_query($link, "SELECT name from points");
while ($row = mysqli_fetch_array($sql, MYSQLI_ASSOC)) {
echo "Имя: ".$row['name']."<br>";
};
break;
};
};
?>