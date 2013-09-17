
<?php
header('Content-Type: text/html; charset=utf-8');
session_start();

include "db.php";

if (isset($_POST['rlogin']) && isset($_POST['rpassword']))  {

if (($_POST['rlogin'] == '') or ($_POST['rpassword']==''))
{
echo("Пустое поле.");
exit;

} else { 

if ((!preg_match("/^[a-zA-Z0-9]+$/",$_POST['rlogin'])) or (!preg_match("/^[a-zA-Z0-9]+$/",$_POST['rpassword']))) 
{
echo("Нельзя использовать такие символы");
exit;
}
else {
$login = mysqli_real_escape_string($link, $_POST['rlogin']);
$password = md5($_POST['rpassword']);
$sql=mysqli_query($link, "INSERT INTO auth (login, pass) VALUES ('$login', '$password')");

if ($sql) {
header('Refresh: 4;URL=showtrack.php');
echo "Добавили. Через 4 секунды Вы вернетесь на страницу авторизации.";
}
else {
echo "Не добавили.Такой логин уже существует.<br>
Через 4 секунды Вы вернетесь на страницу авторизации.";
header('Refresh: 4;URL=showtrack.php');
}
}
}
}
if (isset($_POST['login']) && isset($_POST['password']))
{
    $login = mysqli_real_escape_string($link, $_POST['login']);
    $password = md5($_POST['password']);

    $query = "SELECT login FROM auth WHERE login='$login' AND pass='$password' LIMIT 1";
    $sql = mysqli_query($link, $query) or die(mysql_error());

    if (mysqli_num_rows($sql) == 1) {

        $row = mysqli_fetch_assoc($sql);
	$_SESSION['name'] = $login;
        $_SESSION['user_id'] = $row['login'];
	header('Location: showtrack.php');
	exit;
    }
    else {
        echo "Такой логин с паролем не найдены в базе данных. Через 4 секунды Вы вернетесь на страницу авторизации.";
	header('Refresh: 4;URL=showtrack.php');
    }
}

if ($_GET['logout'] == '1') {
session_destroy();
header('location: map.php');
exit;
}
?>
