<?php session_start(); ?>
<?php require '../header.php'; ?>
<?php require 'menu.php'; ?>
<?php
if (isset($_SESSION['customer'])) {
	unset($_SESSION['customer']);
	echo '<html><header>
        <meta http-equiv="refresh" content="3;URL=../admin/product.php">
        </header>
				ログアウトしました。';
} else {
	echo '<html><header>
        <meta http-equiv="refresh" content="3;URL=../admin/product.php">
        </header>
	      すでにログアウトしています。';
}
?>
<?php require '../footer.php'; ?>
