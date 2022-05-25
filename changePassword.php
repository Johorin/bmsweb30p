<?php
//セッションの開始
session_start();

//ログイン認証情報を取得する関数のインポート
require_once 'loginAuthentication.php';

//インポートした関数でログイン中のユーザー名と権限を取得
$authInfo = authenticate();

//「変更」ボタンが押された状態の処理
if(isset($_POST['changePassButton'])) {
    //POSTデータを変数に格納
    $oldPass = $_POST['oldPass'];
    $newPass = $_POST['newPass'];
    $newPassAgain = $_POST['newPassAgain'];

    /* バリデーションエラー設定 */
    //旧パスワードが正しくないとエラー
    if($oldPass != $_SESSION['userInfo']['password']) {
        $errMsg1 = '旧パスワードが正しくありません。';
    }
    //新パスワードと確認用パスワードが等しくないとエラー
    if($newPass != $newPassAgain) {
        $errMsg2 = '新パスワードと一致しません。';
    }

    //バリデーションエラーが存在しない場合にパスワード更新
    if(!isset($errMsg1) && !isset($errMsg2)) {
        require_once 'dbprocess.php';

        $updateSql = "UPDATE userinfo SET password='{$newPass}' WHERE user='{$authInfo['userName']}'";
        executeQuery($updateSql);

        //セッションに登録されているパスワードも更新
        $_SESSION['userInfo']['password'] = $newPass;

        //パスワードの更新後はメニュー画面に遷移
        header('Location: ./menu.php?updatedPass=1');
        exit;
    }
}
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>パスワード変更画面</title>
	</head>
    <body>
        <header>
        	<h2 align="center">書籍管理システム</h2>
        	<hr style="border: 2px solid blue;">
    		<a href="./menu.php" style="margin: 0 20px 0 0;">[メニュー]</a>
        	<h3 align="center">パスワード変更</h3>
        	<div class="loginInfo" style="position: absolute; top: 55px; right: 60px;">
        		<p>名前：<?=$authInfo['userName']?></p>
        		<p>権限：<?=$authInfo['authority']?></p>
        	</div>
        	<hr style="border: 1px solid black;">
        </header>
        <main>
        	<br><br>
        	<center>
        		<form action="./changePassword.php" method="post">
                	<table>
                		<tr>
                			<th style="background-color: grey">ユーザー</th>
                			<td><?=$authInfo['userName']?></td>
                		</tr>
                		<tr>
                			<th style="background-color: grey">旧パスワード</th>
                			<td>
                				<input type="text" name="oldPass">
                				<p style="color: red;"><?=isset($errMsg1) ? $errMsg1 : ''?></p>
                			</td>
                		</tr>
                		<tr>
                			<th style="background-color: grey">新パスワード</th>
                			<td><input type="text" name="newPass"></td>
                		</tr>
                		<tr>
                			<th style="background-color: grey">新パスワード（確認用）</th>
                			<td>
                				<input type="text" name="newPassAgain">
                				<p style="color: red;"><?=isset($errMsg2) ? $errMsg2 : ''?></p>
                			</td>
                		</tr>
                	</table>
                	<br>
                	<input type="submit" name="changePassButton" value="変更">
        		</form>
        	</center>
        </main>
        <footer>
        	<br><br><br>
        	<hr style="border: 1px solid blue;">
        	<p>Copyright (C) 20YY All Rights Reserved.</p>
        </footer>
    </body>
</html>