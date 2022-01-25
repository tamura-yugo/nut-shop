<?php
session_start();
class PurchaseHistory{ //←名前なんでもいい

  protected $pdo;
  public function __construct(){
    require '../header.php';
    require '../connect.php';
    $this->pdo = $pdo;
  }

  public function table_show(){



      $sql = "SELECT p.id,c.name,c.email,
      GROUP_CONCAT(s.name) as pname ,
      GROUP_CONCAT(d.count) as num ,shipping, p.created
      from purchase as p
      left join customer as c on c.id = p.customer_id
      left join purchase_detail as d on purchase_id = p.id
      left join product as s on s.id = product_id
      group by p.id
      limit 0,15" ;

      $purchase=$this->pdo->prepare( $sql );
      $purchase->execute();

      echo isset($_SESSION['alert']) ? $_SESSION['alert'] : '';

      echo'<table border="1"><tr>
        <th>注文番号</th> <th>個人情報</th> <th>注文詳細</th> <th>出荷</th> <th>注文日</th> </tr>';

      foreach ($purchase as $key=>$row) { 
        
        echo "<td>$row[id]</td>
              <td>$row[name]
              <br>$row[email]</td>
         ";

        //文字列がカンマで区切られてる場合
        $pnames = explode(',' , $row['pname']);
        $nums = explode(',' ,$row['num']);
        $td = '';
          foreach ($pnames as $key => $value) {
            $td .=  "$value x $nums[$key] <br>";  
          }
        
        $chk = array_fill(0,2,"");
        if( $row['shipping'] == 1 ){ 
          $chk[0] = "checked"; //未出荷
        }else{
          $chk[1] = "checked";
        }

        echo "<td> $td </td>
              <td> 
                <form  action='purchase_shipped.php' method='post'>
                  <label><input type='radio' name='shipped' value='1' $chk[0] >未荷済</label>
                  <br><label><input type='radio' name='shipped' value='2' $chk[1]>出荷済み</label>
                  <input type='hidden' name='purchase_id' value='$row[id]'>
                  <br> <input type='submit' value='変更'>
                </form>
              </td>
              <td> $row[created] </td>
        </tr>"; 

      }
    }
}

$ph = new PurchaseHistory();
$ph->table_show();