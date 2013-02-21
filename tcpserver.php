<?php
    header('Content-Type: text/plain;');
    include "db.php";
    error_reporting(E_ALL ^ E_WARNING);//включаем отображение ошибок
    set_time_limit(0);//чтоб скрипт выполнялся всегда, а не положенные 30 секунд по дефолту
    ob_implicit_flush();//выводить строки с помощью echo нужно сразу при их выводе, а не после полной загрузки страницы, как это делается по-умолчанию.
    $address='83.229.149.147';
    $port = 50001;//порт нашего сервера
    $sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);//создаём дескриптор сокетаsocket_set_option($sock, SOL_SOCKET, SO_REUSEADDR, 1);//задаём опции порта
    socket_bind($sock, $address, $port);//привязываес этот дескриптор к нашему адресу и порту
    socket_set_option($sock, SOL_SOCKET, SO_KEEPALIVE, 1);
    socket_set_nonblock($sock);
    socket_listen($sock,1000);//начинаем прослушивание сокета
    $clients = array($sock);// Создаем список всех клиентов, которые будут связаны с нами . Добавляем сокет в этот список
    $tarr=array();
    while (true) {
	$read = $clients;// создаём копии
	if(count($tarr)>0)foreach($tarr as $ind => $tim){
	    if ((time()-$tim)>10){ //если пользователь неактивен в течении 10 секунд - отсоединяем его.
		socket_close($read[$ind+1]);
		$key = array_search($read[$ind+1], $clients);//удаляем дескриптор клиента из массива клиентов
		unset($clients[$key]);
		unset($iparr[$key-1]);
		unset($tarr[$ind]);
		echo "Disconnect client.";
		continue;//Продолжаем читать сообщения клиентов
	    }
	}
	if (socket_select($read, $write = NULL, $except = NULL, 1) < 1) //получаем список всех клиентов, если нет клиентов с данными - продолжаем
	    continue;
	if (in_array($sock, $read)) { //смотрим, есть ли клиенты, пытающиеся соединиться
	    $clients[] = $newsock = socket_accept($sock);//соединяемся с клиентом и добавляем дескриптор соединения в массив
	    socket_write($newsock, "<OK>");//отсылаем клиенту сообщение, что всё он соединился
	    socket_getpeername($newsock, $ip);//запрашиваем ип адрес клиента
	    echo "New connection from ip: {$ip}";//пишем в консоль что у нас новое соединение
	    $key = array_search($sock, $read);//удаляем запись из массива, в котором хранятся соединяющиеся клиенты
	    $iparr[]=$ip;
	    $tarr[]=time();
	    unset($read[$key]);
	    $read[]=$newsock;
	    continue;//Продолжаем читать сообщения клиентов
	}
	foreach ($read as $index => $read_sock) {//начинаем смотреть по всем клиентам, которые соединены с нами
	    $data = socket_read($read_sock, 1024);//читаем что пишет клиент
	    if ($data === false) {//проверяем, не отсоединился ли клиент
		$key = array_search($read_sock, $clients);//удаляем дескриптор клиента из массива клиентов
		unset($clients[$key]);
		unset($iparr[$key-1]);
		echo "Disconnect client.";
		unset($tarr[$key-1]);
		continue;//Продолжаем читать сообщения клиентов
	    }
	    $data = trim($data);
	    if (!empty($data)) {

		Save2DB($data);
//если клиент чтото прислал - отслыаем обратно. В этом месте можно писать свои обработчики команд
		echo $iparr[$index-1]."[$index] - $data";
		$tarr[$index-1]=time();
		switch($data){
		    case "<KILLMY>"://отключить клиента
			socket_close($read_sock);
			$key = array_search($read_sock, $clients);//удаляем дескриптор клиента из массива клиентов
			unset($clients[$key]);
			unset($iparr[$key-1]);
			echo "Disconnect client.";
			unset($tarr[$key-1]);
		    break;
		    case "<PING>"://проверить есть ли связь
			socket_write($read_sock, "<PONG>");
		    break;
		}
		if ($data==="<KILL>"){//вырубить сервер
		    socket_close($sock);
		break(2);
		}
	    }
	}
    }
function Save2DB($name) {
global $link;
$result=mysqli_query($link, "REPLACE INTO archive (name) VALUES ('$name')");
if (!$result) {
echo "не добавили";
}
else {
echo "добавили";
};
};

    socket_close($sock);
?>