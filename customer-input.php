<?php session_start(); ?> 
<?php require '../header.php'; ?>
<?php 
  $name=$address=$login=$password=$email='';
  if (isset($_SESSION['customer'])) {
    $name=$_SESSION['customer']['name'];
    $address=$_SESSION['customer']['address'];
    $email=$_SESSION['customer']['email'];
    $login=$_SESSION['customer']['login'];
  } 
?> 
<form class="customer" action="customer-output.php" method="post">
  <table>
    <tr>
      <td>お名前<em>＊</em></td>
      <td><input type="text" name="name" value="<?=$name?>"  required></td>
    </tr>
    <tr>
      <td>ご住所<em>＊</em></td>
      <td><input type="text" name="address" value="<?=$address?>"  required></td>
    </tr>
    <tr>
      <td>メール<em>＊</em></td>
      <td><input type="email" name="email" value="<?=isset($email)?$email:''?>" required></td>
    </tr>
    <tr>
      <td>ログイン名<em>＊</em></td>
      <td><input type="text" name="login" value="<?=$login?>" required>
        <span id="alert"></span>
      </td>
    </tr>
    <tr>
      <td>パスワード<em>＊</em></td>
      <td><input type="password" name="password" value="" required></td>
    </tr>
    <tr>
      <td>パスワード確認<em>＊</em></td>
      <td><input type="password" name="password_c" value="" required></td>
    </tr>
    
  </table>
  <input type="submit" value="確定">
</form>


<script src="https://code.jquery.com/jquery-3.5.1.js" ></script>
<script>
  $('[name="login"]').on("change", function () {
    var login = $('[name="login"]').val(); 
    $.ajax({
      url: 'ajaxtest.php',
      data:{"login": login},
      type: "POST",
      dataType: "text",
    }).done(function (data) {
      if(data == 1 ){
        $('#alert').html(`<span style="color:red">残念!すでに使用されているアカウント名です</span>`);
        $('[name="login"]').val('');
      } else{
        $('#alert').html('');
      }
    }).fail(function(XMLHttpRequest, status, e){
      console.log(e);
    });

  });

</script>


<?php require '../footer.php'; ?>