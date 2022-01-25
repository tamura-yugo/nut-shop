<?php 
if (session_status() == PHP_SESSION_NONE) {
  // セッションは有効で、開始していないとき
  session_start();
}
$_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
$token = $_SESSION['token'];

require '../header.php';
 require 'menu.php'; 



  
?>
<form action="login-output3.php" method="post">
<input type="hidden" name="token" value="<?=$token?>">
ログイン名<input type="text" name="login"><br>
パスワード<input type="password" name="password" id="pswd"><br>
<input type="submit" value="ログイン">
</form>
<?php require '../footer.php'; ?>


<script
  src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
  integrity="sha256-4+XzXVhsDmqanXGHaHvgh1gMQKX40OUvDEBTu8JcmNs="
  crossorigin="anonymous"></script>
<script>

$("#pswd").keypress(function (e) {
  var pushedkey = e.originalEvent.key;
  var reg = new RegExp(/^[0-9a-zA-Z]*$/);
  var res = reg.test(pushedkey);
  
	if(!res){
    alert(pushedkey + "はパスワードに含まれていません\n最初から入れ直してください" );
    setTimeout(function(){
      $("#pswd").val('');
    },300);
  }
});

</script>