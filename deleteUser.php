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

//detailUser.phpもしくはlistUser.phpからの遷移でない場合はエラー画面へ遷移
if(!isset($_GET['deleteUserName'])) {
    header('Location: ./error.php?errNum=21');
    exit;
}

//一連のDB操作処理関数のインポート
require_once 'dbprocess.php';

//送られてきた削除対象のユーザーIDを取得
$deletedUserName = $_GET['deleteUserName'];

//削除する予定のユーザーがログイン中のユーザーだった場合は削除処理をキャンセルしてエラー画面に遷移
if($authInfo['user'] === $deletedUserName) {
    header('Location: ./error.php?errNum=22');
    exit;
}
//権限が'管理者'の場合は削除処理をキャンセルしてエラー画面に遷移
if($deletedUserName === '管理者') {
    header('Location: ./error.php?errNum=23');
    exit;
}

/* 送られてきたユーザーIDを元に削除するユーザー情報を取得 */
$selectSql = "SELECT * FROM userinfo WHERE user='{$deletedUserName}'";
$selectResult = executeQuery($selectSql);
$deletedUserData = mysqli_fetch_assoc($selectResult);
mysqli_free_result($selectResult);

/* ユーザー情報削除 */
$deleteSql = "DELETE FROM userinfo WHERE user='{$deletedUserName}'";
executeQuery($deleteSql);
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>ユーザー削除画面</title>
	</head>
    <body>
    	<header>
        	<h2 align="center">書籍管理システム</h2>
        	<hr style="border: 2px solid blue;">
        	<div class="float-left" style="position: absolute; top: 83px; left: 20px;">
        		<a href="./menu.php" style="margin: 0 20px 0 0;">[メニュー]</a>
        		<a href="./listUser.php">[ユーザー一覧]</a>
        	</div>
        	<h3 align="center">ユーザー削除</h3>
        	<div class="loginInfo" style="position: absolute; top: 55px; right: 60px;">
        		<p>名前：<?=$authInfo['userName']?></p>
        		<p>権限：<?=$authInfo['authority']?></p>
        	</div>
        	<hr style="border: 1px solid black;">
        </header>
        <main>
        	<center>
        		<br>
        		<br>
        		<table>
        			<tr>
            			<th style="width: 180px; background-color: grey; text-align: left; padding-left: 20px;">ユーザー</th>
            			<td style="width: 200px;"><?=$deletedUserData['user']?></td>
        			</tr>
        			<tr>
            			<th style="width: 180px; background-color: grey; text-align: left; padding-left: 20px;">パスワード</th>
            			<td style="width: 200px;"><?=$deletedUserData['password']?></td>
        			</tr>
        			<tr>
            			<th style="width: 180px; background-color: grey; text-align: left; padding-left: 20px;">Eメール</th>
            			<td style="width: 200px; white-space: nowrap;"><?=$deletedUserData['email']?></td>
        			</tr>
        			<tr>
            			<th style="width: 180px; background-color: grey; text-align: left; padding-left: 20px;">権限</th>
            			<td style="width: 200px;"><?=($deletedUserData['authority'] === '1') ? '一般ユーザ' : '管理者'?></td>
        			</tr>
        		</table>
        		<br>
        		<p>上記データを削除しました。</p>
        		<br>
        		<a href="./listUser.php">ユーザー一覧へ戻る</a>
        	</center>
        </main>
        <footer>
        	<br><br><br>
        	<hr style="border: 1px solid blue;">
        	<p>Copyright (C) 20YY All Rights Reserved.</p>
        </footer>
    </body>
</html>