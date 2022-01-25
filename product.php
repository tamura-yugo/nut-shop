<?php require '../header.php'; ?>
<?php require 'menu.php'; ?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
<form action="product.php" method="post">
商品検索
<input type="text" name="keyword">
<input type="submit" value="検索">
</form>
<hr>
<style>
.yoko{width:250px;
      height:187.5px;}
.bottom{margin-bottom:20px;}

</style>
<?php

$pdo=new PDO('mysql:host=localhost;dbname=ty_shop;charset=utf8', 
	'tamura_yugo', 'Asdf3333-');
if (isset($_REQUEST['keyword'])) {
	$sql=$pdo->prepare('select * from product where name like ?');
	$sql->execute(['%'.$_REQUEST['keyword'].'%']);
} else {
	$sql=$pdo->prepare('select * from product');
	$sql->execute([]);		
}

echo '<div class="container">';
echo '<div class="row">';


foreach ($sql as $row) {
	$id=$row['id'];
  echo '<div class="col-xs-3 col-ms-3 col-md-6 col-lg-4 ">';
  echo '<a href="detail.php?id=', $id, '"><img src="image/', $row['id'], '.jpg" class="yoko"></a>';
	
	
	echo '<section class="bottom">';
	echo '<a href="detail.php?id=', $id, '">', $row['name'], '</a>' , $row['price'], '</section>';
  echo '</div>';
  }
  
  echo '</div>';
  echo '</div>';


?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>

<?php require '../footer.php'; ?>
