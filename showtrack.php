<?php
//ob_start();

header('Content-Type: text/html; charset=utf-8');
session_start();
include "db.php";

$OUT = "
<!DOCTYPE html>
<html>
<head>
    <title>Карта</title>
    <meta charset='utf-8' />
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
<link rel='stylesheet' href='http://cdn.leafletjs.com/leaflet-0.4/leaflet.css' />

 <!--[if lte IE 8]>
     <link rel='stylesheet' href='http://cdn.leafletjs.com/leaflet-0.4/leaflet.ie.css' />
 <![endif]-->
<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js'></script>
<script src='http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js'></script>
<script src='http://cdn.leafletjs.com/leaflet-0.4/leaflet.js'></script>
<script src='/bootstrap/js/bootstrap.js'></script>
<link href='/bootstrap/css/bootstrap.css' rel='stylesheet'>
<link href='/bootstrap/css/bootstrap-responsive.css' rel='stylesheet'>
</head>
<body>
<div class='row-fluid'>
<div class='span8 offset1'>
<ul class='nav nav-tabs'>
<li><a href='map.php'><i class='icon-map-marker'></i>Точки</a><li>
<li class='active'><a href='showtrack.php'><i class='icon-fullscreen'></i>Мониторинг</a></li>
<li><a href='poi.php?poi=show'><i class='icon-info-sign'></i>Информация</a><li>
</ul>
</div>
</div>
<div class='row-fluid'>
<div class='span5 offset1'>
";
if (!isset($_SESSION['user_id'])) {

    $OUT .= "Вы не авторизованны<br><br>
	    <form class='form-inline' method='post' action='auth.php'>
	    <input type='text' class='input-small' placeholder='Логин' name='login'>
	    <input type='password' class='input-small' placeholder='Пароль' name='password'>
	    <button type='submit' class='btn'>Войти</button>
	    </form>

	    <div class='accordion-group'>
	    <div class='accordion-heading'>
    	    <a class='accordion-toggle' data-toggle='collapse' data-parent='#accordion2' href='#collapseTwo'>
	    Регистрация
    	    </a>
	    </div>
	    <div id='collapseTwo' class='accordion-body collapse'>
	    <div class='accordion-inner'>
	    <form class='form-inline' method='post' action='auth.php'>
	    <input type='text' class='input-small' placeholder='Логин' name='rlogin'>
	    <input type='password' class='input-small' placeholder='Пароль' name='rpassword'>
	    <button type='submit' class='btn btn-primary'>Регистрация</button>
	    </form>
    	    </div>
	    </div>
	    </div>
	    </div>";
	     

} else {
    $name = $_SESSION['name'];
    $result=mysqli_query($link, "SELECT * FROM tracking WHERE name='$name' LIMIT 1");
    $alldevices=mysqli_query($link,"SELECT hash from tracking WHERE name='$name'");

if (mysqli_num_rows($result) == 0) {

    $OUT .="<span class='label label-info'>Нет ни одного устройства</span><br>
    
    Настройка:
    <div class='accordion' id='accordion2'>
	 <div class='accordion-group'>
	    <div class='accordion-heading'>
		 <a class='accordion-toggle' data-toggle='collapse' data-parent='#accordion2' href='#collapseOne'>
        Для телефонов Android с программой OSMAND
    		 </a>
	    </div>
	 <div id='collapseOne' class='accordion-body collapse'>
    		 <div class='accordion-inner'>
		    Скачайте из Google Play программу навигации OSMAND:<br>
		    <a href='https://market.android.com/details?id=net.osmand'>https://market.android.com/details?id=net.osmand</a><br> 
		    Откройте приложение и зайдите в 'настройки'.Далее зайдите в диспетчер плагинов.<br>
		    <img src='img/plugins.png' class='img-rounded' width='300' height='100'><br>
		    Включите плагины Мониторинг и Фоновый режим<br>
		    Зайдите в 'Настройки' - 'Мониторинг'. Поставьте галочки как показано на скриншоте.<br><br>
		    <img src='img/monitoring.png' class='img-rounded' width='300' height='100'><br>
		    Укажите WEB-адрес слежения, где после 'name=' Ваше имя при регистрации:<br>
		    <img src='img/webaddr.png' class='img-rounded' width='300' height='100'><br>
		    Всё! Ваше устройство настроенно и готово отправлять данные на сервер.
		    Если Вы хотите, что GPS отправлял данные в фоновом режиме, то настройте второй плагин 'Фоновый режим'.<br>
		    <img src='img/backgroundmon.png' class='img-rounded' width='300' height='100'><br>
	    </div>
	 </div>
    </div>
    
    <div class='accordion-group'>
	<div class='accordion-heading'>
    	    <a class='accordion-toggle' data-toggle='collapse' data-parent='#accordion2' href='#collapseTwo'>
    Для GPS трекеров
      </a>
    </div>
    <div id='collapseTwo' class='accordion-body collapse'>
      <div class='accordion-inner'>
        TODO
      </div>
    </div>
  </div>
</div>


<br><a href='auth.php?logout=1' class='btn btn-info'>Выход</a>
";
	
} else {
     		
    $OUT .= "
    <div id='myModal' class='modal hide fade' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
    <div class='modal-header'>
    <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>×</button>
    <h3 id='myModalLabel'>Добавление устройства</h3>
    </div>
    <div class='modal-body'>
    <p>Чтобы добавить новое устройство для отображения на сайте измените адрес live слежения, добавив к нему ключ hash. </p>
    <p>Допустим, Вам надо добавить устройство(имея уже одно по дефолту). Строка будет выглядеть так:
    <p>http://map.limpteam.ru/track.php?lat={0}&lon={1}&amptimestamp={2}&hdop={3}&altitude={4}&speed={5}&name=[ваше имя при регистрации]<b>&hash=[имя нового устройства]</b></p>
    </div>
    <div class='modal-footer'>
    <button class='btn' data-dismiss='modal' aria-hidden='true'>Закрыть</button>
    </div>
    </div>

    <div id='map' style='height: 600px; border-radius: 6px; box-shadow: 0 8px 16px -8px #222;'></div>
    </div>
    <div class='span3'>
    <script>
      var map = L.map('map').setView([55.76, 37.64], 8);
      map.locate({setView: true, maxZoom: 18});
      L.tileLayer('http://{s}.tile.cloudmade.com/6a47c48f55494a5f92c09fce0caf2051/997/256/{z}/{x}/{y}.png', {maxZoom: 18,}).addTo(map);
    ";
    if ($_GET['view'] == '1') {
    header('Refresh: 90; url='.$_SERVER["REQUEST_URI"]);
    $hash=mysqli_real_escape_string($link, $_GET['hash']);
    $showhash=mysqli_query($link, "SELECT * from tracking WHERE name='$name' AND hash='$hash'");
    while ($row = mysqli_fetch_array($showhash)) {
            $OUT .= "L.marker([".$row['lat'].",".$row['lon']."]).addTo(map).bindPopup('".$row['hash']." <br>Cкорость: ".$row['speed']." км/ч<br> Время: ".$row['timestamp']."');";
            $OUT .= "map.setView([".$row['lat'].",".$row['lon']."],18);</script>";
            $OUT .= "<label class='label label-info'>".$row['hash']."</label>";
	    $OUT .="<table class='table table-bordered'>
                    <tr><th>Последние координаты: </th></tr>
                    <tr><td>".$row['lat']." ".$row['lon']." </td></tr>
                    <tr><th>Скорость</th></tr>
		    <tr><td>".$row['speed']." км/ч</td><tr>
		    <tr><th>Время:</th></tr><tr><td>".$row['timestamp']."</td></tr>
                    <tr><th>Точность:</th></tr> ";
	    if ($row['hdop'] > 15) {
                $OUT .= "<tr><td><font color=red>Ниже среднего</font> (".$row['hdop'].")</td></tr>";
            } else  {
                $OUT .= "<tr><td><font color=green>Высокая </font> (".$row['hdop'].")</td></tr>";
	    }
	    $OUT .= "</table>Ваши устройства:<br>";
	    }
    while ($row = mysqli_fetch_array($alldevices)) {
	    $OUT.="<a href=showtrack.php?name=".$name."&hash=".$row['hash']."&delete=1><i class='icon-trash'></i></a><a href=showtrack.php?name=".$name."&hash=".$row['hash']."&clear=1><i class='icon-remove' title='Очистить трек, но оставить устройство'></i></a> <a href='showtrack.php?name=".$name."&hash=".$row['hash']."&view=1'><span class='label label-info'>".$row['hash']."</span></a><br>";
	    }

	    $OUT .= "<br><a href='javascript: history.go(-1)'><i class='icon-arrow-left'></i>Назад</a><br>
		    <br><a href='showtrack.php?name=".$name."&hash=".$hash."&showtrack=1'><i class='icon-eye-open'></i>Показать весь трек</a>
		    <br><a href='auth.php?logout=1' class='btn btn-info'>Выход</a>";
}  else {



// показать все точки
    if ($_GET['showallpoints'] == '1') {
	$hash=mysqli_real_escape_string($link, $_GET['hash']);
	$name=mysqli_real_escape_string($link,$_GET['name']);
	$showpoints=mysqli_query($link,"SELECT lat,lon,hash  FROM tracking WHERE name='$name'");
	    while ($row = mysqli_fetch_array($showpoints, MYSQLI_ASSOC)) {
		$OUT.= "L.marker([".$row['lat'].",".$row['lon']."]).addTo(map).bindPopup('".$row['hash']."');";
		}
    		$OUT.="</script>";
		$OUT.="<br><a href='showtrack.php?name=".$name."&showallpoints=1'><i class='icon-map-marker'></i>Показать все мои устройства на карте</a><br>
		     <a href='javascript: history.go(-1)'><i class='icon-arrow-left'></i>Назад</a><br><br>
                     <br><a href='auth.php?logout=1' class='btn btn-info'>Выход</a>";


} else {        


// показать весь трек маршрута
    if ($_GET['showtrack'] == '1')  {
	$hash=mysqli_real_escape_string($link, $_GET['hash']);
        $name=mysqli_real_escape_string($link,$_GET['name']);
	$showall=mysqli_query($link,"SELECT lat,lon,hash  FROM archive WHERE name='$name' AND hash='$hash' ORDER BY timestamp");

	$OUT .= "
	    var track = {
	    'type': 'FeatureCollection',
	    'features': [
	        {
                'type': 'Feature',
                'geometry': {
                'type': 'LineString',
                'coordinates': [
	";
        while ($row = mysqli_fetch_array($showall)) {
    	    $OUT .= "[".$row['lon'].", ".$row['lat']."], ";
	
	}
	$setview=mysqli_query($link,"SELECT lat,lon,timestamp FROM tracking WHERE name='$name' AND hash='$hash'");
	
	$OUT .= "]}}]};
    	    L.geoJson(track).addTo(map);";
	while ($row = mysqli_fetch_array($setview)) {
    
	$OUT .="map.setView([".$row['lat'].", ".$row['lon']."],14);";
	$OUT .="L.marker([".$row['lat'].",".$row['lon']."]).addTo(map);";
	$lastpoint=$row['timestamp'];
	}

        $OUT .="</script>
	Последняя активность была в:<br>".$lastpoint."<br>
	<a href='javascript: history.go(-1)'>Назад</a><br>
	<br><a href='showtrack.php?logout=1' class='btn btn-info'>Выход</a>
	</div>
	";
} else {

 if ($_GET['delete'] == '1')  {
    $hash=mysqli_real_escape_string($link, $_GET['hash']);
    $name=mysqli_real_escape_string($link,$_GET['name']);
    $delhash=mysqli_query($link, "DELETE from tracking WHERE name='$name' AND hash='$hash'");
    $delhasharch=mysqli_query($link,"DELETE from archive WHERE name='$name' AND hash='$hash'");
    if (($delhash) and ($delhasharch)) {
    header("Location:showtrack.php");
    } else {
    echo "ошибка";
    }


} else {

 if ($_GET['clear'] == '1')  {
    $hash=mysqli_real_escape_string($link, $_GET['hash']);
    $name=mysqli_real_escape_string($link,$_GET['name']);
    $clear=mysqli_query($link, "DELETE from archive WHERE name='$name' AND hash='$hash'");
    if ($clear) {
    header("Location:showtrack.php");
    } else {
    echo "ошибка";
    }


} else {

	header('Refresh: 90; url='.$_SERVER["REQUEST_URI"]); 
	while ($row = mysqli_fetch_array($result)) {
	    $hash = $row['hash'];
	    $OUT .= "L.marker([".$row['lat'].",".$row['lon']."]).addTo(map).bindPopup('".$row['hash']."<br> Cкорость: ".$row['speed']." км/ч<br> Время ".$row['timestamp']."');";
    	    $OUT .= "map.setView([".$row['lat'].",".$row['lon']."],18);</script>";
	    $OUT .= "<label class='label label-info'>".$row['hash']."</label>";
	    $OUT .="<table class='table table-bordered'>
		    <tr><th>Последние координаты: </th></tr>
		    <tr><td>".$row['lat']." ".$row['lon']." </td></tr>
		    <tr><th>Скорость</th></tr>
		    <tr><td>".$row['speed']." км/ч</td><tr>
		    <tr><th>Время:</th></tr><tr><td>".$row['timestamp']."</td></tr>
		    <tr><th>Точность:</th></tr> ";
	    if ($row['hdop'] > 15) {
		$OUT .= "<tr><td><font color=red>Ниже среднего</font> (".$row['hdop'].")</td></tr>"; 
	    } else  {
		$OUT .= "<tr><td><font color=green>Высокая </font> (".$row['hdop'].")</td></tr>";
	    }
	    $OUT .= "</table>
		    Ваши устройства:<br>";
    }
//все точки юзера из базы
	while ($row = mysqli_fetch_array($alldevices)) {
	    $OUT.="<a href=showtrack.php?name=".$name."&hash=".$row['hash']."&delete=1 title='удалить устройство'><i class='icon-trash'></i></a><a href=showtrack.php?name=".$name."&hash=".$row['hash']."&clear=1><i class='icon-remove' title='Очистить трек, но оставить устройство'></i></a> <a href='showtrack.php?name=".$name."&hash=".$row['hash']."&view=1'><span class='label label-info'>".$row['hash']."</span></a><br>";
	    }
	$OUT.="<a href='#myModal' role='button' data-toggle='modal'><i class='icon-plus'></i>Добавить устройство</a>";
	$OUT.="     <br><a href='showtrack.php?name=".$name."&showallpoints=1'><i class='icon-map-marker'></i>Показать все мои устройства на карте</a>
		    <br><a href='showtrack.php?name=".$name."&hash=".$hash."&showtrack=1'><i class='icon-eye-open'></i>Показать весь трек</a><br>
		    <br><a href='auth.php?logout=1' class='btn btn-info'>Выход</a>
	        ";
			}
		    }
		}	
	    }		
	    
	}
    }
}

$OUT .= "</div></div></body></html>";

echo $OUT;

//ob_end_flush();
?>