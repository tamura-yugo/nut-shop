<?php 
	if (session_status() == PHP_SESSION_NONE) {
		// セッションは有効で、開始していないとき
		session_start();
	}


require '../header.php';
require 'menu.php';

if (!empty($_POST['token']) && !empty($_POST['login']) && !empty($_POST['password'])) {
	if (hash_equals($_SESSION['token'], $_POST['token'])) {
		//トークンが一致してたらやること
		
		require '../connect.php'; 
		$sql = "select * from customer where login=?";
		$sth=$pdo->prepare( $sql );
		$sth->execute([$_REQUEST['login']]);
		$stmt = $sth->fetch();




		if (password_verify( $_POST['password'], $stmt['password'] )) {
			//この関数でハッシュ化したパスワードと照合し正しければtrueが返される
			$_SESSION['customer']=[
				'name'=>$stmt['name']
				,'address'=>$stmt['address']
				,'login'=>$stmt['login']
				,'password'=>$stmt['password']
				,'email'=>$stmt['email']
				,'id'=>$stmt['id']
			];
			echo 'いらっしゃいませ、', $stmt['name'] , 'さん。';
		} else {
			echo 'ログイン名またはパスワードが違います。';
		}




	}else{
		echo "不正なcsrfリクエストが送信されました｡" ;
	}
}else{
	echo "必須項目がありません";
}


?>
<?php require '../footer.php'; ?>
