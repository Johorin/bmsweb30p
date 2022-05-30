<?php
//セッションの開始
session_start();

//ログイン認証情報を取得する関数のインポート
require_once 'loginAuthentication.php';

//インポートした関数でログイン中のユーザー名と権限を取得
$authInfo = authenticate();
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>メニュー画面</title>
	</head>
    <body>
        <header>
        	<h2 align="center">書籍管理システム</h2>
        	<hr style="border: 2px solid blue;">
        	<h3 align="center">MENU</h3>
        	<div class="loginInfo" style="position: absolute; top: 55px; right: 60px;">
        		<p>名前：<?=$authInfo['userName']?></p>
        		<p>権限：<?=$authInfo['authority']?></p>
        	</div>
        	<hr style="border: 1px solid black;">
        </header>
        <main>
        	<br>
        	<p><?=isset($_GET['updatedPass']) ? 'パスワードを更新しました。' : ''?></p>
        	<br>
        	<?php
        	if($authInfo['authority'] == '管理者') {?>
        		<center>
        			<br><br>
        			<p><a href="./list.php">【書籍一覧】</a></p>
        			<p><a href="./insert.php">【書籍登録】</a></p>
        			<p><a href="./insertIniData.php">【初期データ登録（データがない場合のみ）】</a></p>
        			<p><a href="./orderStatus.php">【購入状況確認】</a></p>
        			<p><a href="./showSalesByMonth.php">【売上げ状況】</a></p>
        			<p><a href="./insertUser.php">【ユーザー登録】</a></p>
        			<p><a href="./listUser.php">【ユーザー一覧】</a></p>
        			<p><a href="./changePassword.php">【パスワード変更】</a></p>
        			<p><a href="./logout.php">【ログアウト】</a></p>
        		</center>
    		<?php
            } elseif($authInfo['authority'] == '一般ユーザ') {?>
        		<center>
        			<br><br>
        			<p><a href="./list.php">【書籍一覧】</a></p>
        			<p><a href="./showCart.php">【カート状況確認】</a></p>
        			<p><a href="./orderHistory.php">【購入履歴】</a></p>
        			<br>
        			<p><a href="./changePassword.php">【パスワード変更】</a></p>
        			<p><a href="./logout.php">【ログアウト】</a></p>
        		</center>
    		<?php
            }?>
        </main>
        <footer>
        	<br><br><br>
        	<hr style="border: 1px solid blue;">
        	<p>Copyright (C) 20YY All Rights Reserved.</p>
        </footer>
    </body>
</html>