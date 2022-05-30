<?php
//セッションの利用を開始
session_start();

//ログイン認証情報を取得する関数のインポート
require_once 'loginAuthentication.php';

//インポートした関数でログイン中のユーザー名と権限を取得
$authInfo = authenticate();

//権限が一般ユーザーからのアクセスの際にはエラー画面へリダイレクト
if($authInfo === '一般ユーザ') {
    header('Location: ./error.php?errNum=20');
    exit;
}

/* 送られてきたユーザーIDを元に表示するデータを取得 */
require_once 'dbprocess.php';

$userName = $_GET['detailUserName'];
$selectSql = "SELECT * FROM userinfo WHERE user='{$userName}'";
$selectResult = executeQuery($selectSql);
$userData = mysqli_fetch_assoc($selectResult);
mysqli_free_result($selectResult);
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>ユーザー詳細画面</title>
	</head>
    <body>
    	<header>
        	<h2 align="center">書籍管理システム</h2>
        	<hr style="border: 2px solid blue;">
        	<div class="float-left" style="position: absolute; top: 83px; left: 20px;">
        		<a href="./menu.php" style="margin: 0 20px 0 0;">[メニュー]</a>
        		<a href="./listUser.php">[ユーザー一覧]</a>
        	</div>
        	<h3 align="center">ユーザー詳細</h3>
        	<div class="loginInfo" style="position: absolute; top: 55px; right: 60px;">
        		<p>名前：<?=$authInfo['userName']?></p>
        		<p>権限：<?=$authInfo['authority']?></p>
        	</div>
        	<hr style="border: 1px solid black;">
        </header>
        <main>
        	<center>
        		<br>
        		<div class="actionButtons" style="display: inline-flex;">
            		<form action="./updateUser.php" method="get">
            			<input type="hidden" name="updateUserName" value="<?=$userName?>">
            			<input type="submit" name="updateUserButton" value="変更" style="margin-right: 30px;">
            		</form>
            		<form action="./deleteUser.php" method="get">
            			<input type="hidden" name="deleteUserName" value="<?=$userName?>">
            			<input type="submit" name="deleteUserButton" value="削除">
            		</form>
        		</div>
        		<table>
        			<tr>
            			<th style="width: 180px; background-color: grey; text-align: left; padding-left: 20px;">ユーザー</th>
            			<td style="width: 200px;"><?=$userData['user']?></td>
        			</tr>
        			<tr>
            			<th style="width: 180px; background-color: grey; text-align: left; padding-left: 20px;">パスワード</th>
            			<td style="width: 200px;"><?=$userData['password']?></td>
        			</tr>
        			<tr>
            			<th style="width: 180px; background-color: grey; text-align: left; padding-left: 20px;">Eメール</th>
            			<td style="width: 200px; white-space: nowrap;"><?=$userData['email']?></td>
        			</tr>
        			<tr>
            			<th style="width: 180px; background-color: grey; text-align: left; padding-left: 20px;">権限</th>
            			<td style="width: 200px;"><?=($userData['authority'] === '1') ? '一般ユーザ' : '管理者'?></td>
        			</tr>
        		</table>
        	</center>
        </main>
        <footer>
        	<br><br><br>
        	<hr style="border: 1px solid blue;">
        	<p>Copyright (C) 20YY All Rights Reserved.</p>
        </footer>
    </body>
</html>