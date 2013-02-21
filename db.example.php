<?php

global $link;

$link = mysqli_connect("127.0.0.1", "track", "321", "track");

if (!$link) {
    die("Ошибка подключения ". mysqli_connect_error());
}
mysqli_query($link, "SET CHARACTER SET utf8");
mysqli_query($link, "SET NAMES utf8");

?>