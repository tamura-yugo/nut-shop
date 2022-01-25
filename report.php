<?php
  require_once '../connect.php';
?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
  <link rel="stylesheet" href="../report.css">
  <title>Document</title>
</head>

<body>
  <div class="container">
  <h2>売上レポート</h2>
  <a href="../admin/index.php">管理画面へ</a>
    <div class="row">

    <div class="col-sm-8">
      
    <form id="fm" action="" method="get">
      <section class="row mt-3">

        <div class="col-6 prd">
          <label>商品別</label>
          <select name="product" id="product">
            <option value="">選択してください</option>
<?php
  $sql = "SELECT id,name FROM `product` LIMIT 500";
  $stmt=$pdo->prepare($sql);
	$stmt->execute();

  $products = $stmt->fetchAll();
  $products[-1] = ['id'=>'-1','name'=>'すべて'];
  ksort($products); //参照渡しなので注意
  // var_dump($products);

  foreach ($products as $row) {
     
    $selected = !empty($_GET['product']) &&  $_GET['product']==$row['id'] ? 'selected' : '';
?>
            <option value="<?=$row['id']?>" <?=$selected?> ><?=$row['name']?></option>
<?php
  }
?>
          </select>
        </div>

        <div class="col-6 cst">
          <label>得意先別</label>
          <select name="customer" id="customer">
            <option value="">選択してください</option>
<?php
    $sql = "SELECT id,name FROM `customer` LIMIT 500";
    $stmt=$pdo->prepare($sql);
    $stmt->execute();
    $customers = $stmt->fetchAll();
    $customers[-1] = ['id'=>"-1",'name'=> "すべて" ];
    ksort($customers);

    foreach ($customers as $row) {
      $selected = !empty($_GET['customer']) &&  $_GET['customer']==$row['id'] ? 'selected' : '';
?>
            <option value="<?=$row['id']?>" <?=$selected?>><?=$row['name']?></option>
<?php
    }

?>
          </select>
        </div>
      </section>

      <section class="row mt-3">
        <div class="col-6">
          <label>開始月</label>
          <input type="text" name="year_month_start" id="year_month_start" autocomplete="off" value="<?=
          !empty($_GET['year_month_start']) ? $_GET['year_month_start']: '' ;?>" >
        </div>
        
        <div class="col-6">
          <label>開始月</label>
          <input type="text" name="year_month_end" id="year_month_end" autocomplete="off" value="<?=
          !empty($_GET['year_month_end']) ? $_GET['year_month_end']: '' ;?>" >
        </div>
      </section>

      <p class="mt-3"><a href="./report.php" class="btn btn-danger">クリア</a></p>
    </form>
  </div>


  <section class="container result mt-3">
    <!--帳票がここに出る-->
    <?php
    
    //送信したかどうかの判定
      if( !empty($_GET['product']) ){

        $pid = htmlspecialchars($_GET['product'],ENT_QUOTES);
        
      $where = "";
      if($pid > 0 ){
        // 特定の商品なら
        $where = "\n AND d.product_id = ?" ;
      }

      if( !empty($_GET['year_month_start'])){
        //開始日が来てれば
        $yms = htmlspecialchars($_GET['year_month_start'],ENT_QUOTES);
        $where .= "\n AND DATE_FORMAT(created,'%Y-%m') >= ?";
      } 

      if( !empty($_GET['year_month_end'])){
        //終了日が来てれば
        $yme = htmlspecialchars($_GET['year_month_end'],ENT_QUOTES);
        $where .= "\n AND DATE_FORMAT(created,'%Y-%m') <= ?";
      } 

        $sql = "SELECT d.product_id ,name,sum(count * price) as shoke 
        FROM `purchase_detail` as d
        LEFT JOIN product as p ON d.product_id = p.id
        LEFT JOIN purchase as u ON u.id = d.purchase_id
        WHERE 1
        $where
        GROUP BY d.product_id 
        LIMIT 15";
        $stmt=$pdo->prepare($sql);
        $i = 0;
        if($pid > 0 ){     $stmt->bindValue(++$i, $pid, PDO::PARAM_INT); }
        if(isset($yms) ){  $stmt->bindValue(++$i, $yms, PDO::PARAM_STR); }
        if(isset($yme) ){  $stmt->bindValue(++$i, $yme, PDO::PARAM_STR); }
        
    // var_dump($sql ,$i);
        $stmt->execute();

        echo '<table class="table">
            <tr><th>id</th><th>name</th><th>合計<th></tr>';  
        foreach ($stmt as $row) {
  ?>
          <tr>
            <td><?=$row['product_id']?></td>
            <td><?=$row['name']?></td>
            <td style="text-align:right"><?=number_format($row['shoke'])?></td>
          </tr>


  <?php
        }
        echo '</table>';  

      }elseif ( !empty($_GET['customer'])) {
        //得意先売上一覧
        $cid = htmlspecialchars($_GET['customer'],ENT_QUOTES);
        $where = "";
        if($cid > 0 ){
          // 特定の商品なら
          $where = "WHERE c.id = ?" ;
        }
        if( !empty($_GET['year_month_start'])){
          //開始日が来てれば
          $yms = htmlspecialchars($_GET['year_month_start'],ENT_QUOTES);
          $where .= "\n AND DATE_FORMAT(created,'%Y-%m') >= ?";
        } 
  
        if( !empty($_GET['year_month_end'])){
          //終了日が来てれば
          $yme = htmlspecialchars($_GET['year_month_end'],ENT_QUOTES);
          $where .= "\n AND DATE_FORMAT(created,'%Y-%m') <= ?";
        } 

        $sql = "SELECT c.id, c.name ,sum(count * price) as shoke 
        FROM `customer` as c
        LEFT JOIN purchase as p ON c.id = p.customer_id
        LEFT JOIN purchase_detail as d ON p.id = d.purchase_id
        LEFT JOIN product as r ON r.id = d.product_id
        $where
        GROUP BY c.id 
        LIMIT 15";

        $stmt=$pdo->prepare($sql);
        $i = 0;
        if($cid > 0 ){     $stmt->bindValue(++$i, $cid, PDO::PARAM_INT); }
        if(isset($yms) ){  $stmt->bindValue(++$i, $yms, PDO::PARAM_STR); }
        if(isset($yme) ){  $stmt->bindValue(++$i, $yme, PDO::PARAM_STR); }
        $stmt->execute();

        echo '<table class="table"><tr><th>id</th><th>name</th><th>合計<th></tr>';  
        foreach ($stmt as $row) {
  ?>
          <tr>
            <td><?=$row['id']?></td>
            <td><?=$row['name']?></td>
            <td style="text-align:right"><?=number_format($row['shoke'])?></td>
          </tr>


  <?php
      }
    }
    
    ?>
  </section>
  </div><!--container-->

  <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="../report.js"></script>
    <script src="../report2.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
    <script>
  $('#product').change(function(){
    if($(this).children('option:selected').val() != '' ){
      //選択してください以外を選んだ場合のみ customerを非選択状態にする
      $('#customer option').eq(0).attr('selected',true);
    }
    $("#fm").submit();
  });

  $('#customer').change(function(){
    if($(this).children('option:selected').val() != '' ){
      //選択してください以外を選んだ場合のみ productを非選択状態にする
      $('#product option').eq(0).attr('selected',true);
    }
    $("#fm").submit();
  });

  $('#year_month_start').change(function(){
    $("#fm").submit();
  });

  $('#year_month_end').change(function(){
    $("#fm").submit();
  });

  $('#year_month_start, #year_month_end').datepicker({
    format: 'yyyy-mm',
    language: 'ja',
    autoclose: true,
    minViewMode: 'months'
  });
 </script>
</body>

</html>