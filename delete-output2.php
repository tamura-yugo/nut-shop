<?php  session_start();
	require '../connect.php'; 

// $sql = "select onsale from product
// 				where id = ?";
// $stmt=$pdo->prepare($sql);
// $stmt->execute([$_REQUEST['id']]);
// $onsale = $stmt->fetch();
// $onsale = $onsale['onsale'];
// //三項演算子で
// $onsale = $onsale===1 ? 0 : 1;


$sql = 'update product set `onsale`=? where id=?';
$stmt=$pdo->prepare($sql);
// var_dump( $onsale ); exit;
$stmt->bindValue(1, $_REQUEST['onsale'], PDO::PARAM_INT);
$stmt->bindValue(2, $_REQUEST['id'], PDO::PARAM_INT);

if ($stmt->execute()) {
	$_SESSION['respons'] = '変更に成功しました。';
} else {
	$_SESSION['respons'] = '変更に失敗しました。';

}
 
header('Location:./delete-input.php');