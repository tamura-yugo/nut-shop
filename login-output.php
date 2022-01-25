<?php session_start(); ?>
<?php require '../header.php'; ?>
<?php require 'menu.php'; ?>
<?php
unset($_SESSION['customer']);
$login=[$_REQUEST['login']];
$password=[$_REQUEST['password']];
$pdo=new PDO('mysql:host=localhost;dbname=ty_shop;charset=utf8', 
	'tamura_yugo', 'Asdf3333-');
$sql=$pdo->prepare('select * from customer where login=? and password=?');
$sql->bindValue('', $login and $password, PDO::PARAM_INT);
$sql->execute([$_REQUEST['login'], $_REQUEST['password']]);
foreach ($sql as $row) {
	$_SESSION['customer']=[
		'id'=>$row['id'], 'name'=>$row['name'], 
		'address'=>$row['address'], 'login'=>$row['login'], 
		'password'=>$row['password']];
}
if (isset($_SESSION['customer'])) {
	echo 'いらっしゃいませ、', $_SESSION['customer']['name'], 'さん。';
} else {
	echo 'ログイン名またはパスワードが違います。';
}
?>
<?php require '../footer.php'; ?>
