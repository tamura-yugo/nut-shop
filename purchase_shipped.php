<?php
session_start();
require '../connect.php'; 

if( isset($_POST['shipped'] ,$_POST['purchase_id'])){
  // issetは複数でもOK &&と同じ意味になる
  foreach ($_POST as $key => $value) {
    // ループしてサニタイジング
    $post[$key] = htmlspecialchars( $value ,ENT_QUOTES);
  }

  $sql = "update purchase set shipping = ? where id =?";
  $stmt=$pdo->prepare($sql);
  $stmt->bindValue(1, $post['shipped'], PDO::PARAM_INT); 
  $stmt->bindValue(2, $post['purchase_id'], PDO::PARAM_INT); 
  $success = $stmt->execute();

  if($success){
    $_SESSION['alert']=  "発注ID $post[purchase_id] を更新しました";
  }else{
    $_SESSION['alert']=  "発注ID $post[purchase_id] を更新できませんでした";
  }
}
//元のページにリダイレクト
header('Location: ./purchase-history.php');
