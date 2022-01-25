<?php if ( empty($_SESSION['customer']['name'])) { ?>
  <a href="product.php">商品</a>
  <a href="customer-input.php">会員登録</a>
  <a href="login-input3.php">ログイン</a>
 
<?php  }else { ?>
  <a href="favorite-show.php">お気に入り</a>
  <a href="history.php">購入履歴</a>
  <a href="cart-show.php">カート</a>
  <a href="purchase-input.php">購入</a>
  <a href="customer-input.php">お客様情報更新</a>
  <a href="logout-input.php">ログアウト</a>
  <div style="float:right">ようこそ<?=$_SESSION['customer']['name']?>さん</div>
<?php  }?>
<hr>