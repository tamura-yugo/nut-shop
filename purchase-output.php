<?php session_start(); ?>
<?php require '../header.php'; ?>
<?php require 'menu.php'; ?>
<?php

date_default_timezone_set('Asia/Tokyo');
// var_dump($_SESSION['product'] ); exit;
	require '../connect.php';

	$purchase_id=1;
foreach ($pdo->query('select max(id) from purchase') as $row) {
	$purchase_id=$row['max(id)']+1;
}

try {
	$pdo->beginTransaction();

		$sql = "insert into purchase (id,customer_id,shipping) values(?,?,1)";
		$sth = $pdo->prepare($sql);
		$sth->bindValue(1, $purchase_id, PDO::PARAM_INT);
		$sth->bindValue(2,  $_SESSION['customer']['id'], PDO::PARAM_INT);
		$sth->execute();

		foreach ($_SESSION['product'] as $product_id=>$product) {
			//	purchase_id, 	product_id,	count
			$sql = 'insert into purchase_detail values(?,?,?)';
			$sth=$pdo->prepare($sql);
			$sth->bindValue(1, $purchase_id, PDO::PARAM_INT);
			$sth->bindValue(2, $product_id, PDO::PARAM_INT);
			$sth->bindValue(3, $product['count'] , PDO::PARAM_INT);
			$sth->execute();
		}

		$pdo->commit();
		echo '購入手続きが完了しました。ありがとうございます。';
		unset($_SESSION['product']);
		
} catch (Exception $e) {
	$pdo->rollBack();
  die("購入手続き中にエラーが発生しました。申し訳ございません。" . $e->getMessage());

}


?>
<?php require '../footer.php'; ?>