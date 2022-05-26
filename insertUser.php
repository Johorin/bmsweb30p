<?php
//セッションの利用を開始
session_start();

//ログイン認証情報を取得する関数のインポート
require_once 'loginAuthentication.php';

//インポートした関数でログイン中のユーザー名と権限を取得
$authInfo = authenticate();

if(isset($_POST['insertUserButton'])) {
    $insertUserName = $_POST['insertUserName'];
    $insertPassword = $_POST['insertPassword'];
    $insertPasswordAgain = $_POST['insertPasswordAgain'];
    $insertEmail = $_POST['insertEmail'];
    $insertAuthority = $_POST['insertAuthority'];

    if($insertPassword === $insertPasswordAgain) {
        require_once 'dbprocess.php';

        $insertSql = "INSERT INTO userinfo VALUES('{$insertUserName}','{$insertPassword}','{$insertEmail}','{$insertAuthority}')";
        executeQuery($insertSql);
    } else {
        $passMismatach = 1;
        header('Location: ./insertUser.php?passMismatch=1');
        exit;
    }
}
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>ユーザー登録画面</title>
	</head>
    <body>
    	<header>
        	<h2 align="center">書籍管理システム</h2>
        	<hr style="border: 2px solid blue;">
        	<div class="float-left" style="position: absolute; top: 83px; left: 20px;">
        		<a href="./menu.php" style="margin: 0 20px 0 0;">[メニュー]</a>
        		<a href="./listUser.php">[ユーザー一覧]</a>
        	</div>
        	<h3 align="center">ユーザー登録</h3>
        	<div class="loginInfo" style="position: absolute; top: 55px; right: 60px;">
        		<p>名前：<?=$authInfo['userName']?></p>
        		<p>権限：<?=$authInfo['authority']?></p>
        	</div>
        	<hr style="border: 1px solid black;">
        </header>
        <main>
        	<center>
        		<br><br>
        		<?php
        		//初期画面の表示
        		if(!isset($_POST['insertUserButton'])) {?>
        			<p style="color: red;"><?=isset($_GET['passMismatch']) ? '確認用パスワードが一致しませんでした。<br>もう一度入力して下さい。' : ''?></p>
        			<form action="./insertUser.php" method="post">
        				<table>
        					<tr>
        						<th style="width: 20vw; background-color: grey;">ユーザー</th>
        						<td style="width: 20vw;"><input type="text" name="insertUserName" style="width: 100%;" value="<?=$insertUserName?>" required></td>
        					</tr>
        					<tr>
        						<th style="width: 20vw; background-color: grey;">パスワード</th>
        						<td style="width: 20vw;"><input type="text" name="insertPassword" style="width: 100%;" value="<?=$insertPassword?>" required></td>
        					</tr>
        					<tr>
        						<th style="width: 20vw; background-color: grey;">パスワード（確認用）/th>
        						<td style="width: 20vw;"><input type="text" name="insertPasswordAgain" style="width: 100%;" required></td>
        					</tr>
        					<tr>
        						<th style="width: 20vw; background-color: grey;">Eメール</th>
        						<td style="width: 20vw;"><input type="text" name="insertEmail" style="width: 100%;" value="<?=$insertEmail?>" required></td>
        					</tr>
        					<tr>
        						<th style="width: 20vw; background-color: grey;">権限</th>
        						<td style="width: 20vw;">
        							<select name="insertAuthority" style="width: 100%" required>
        								<!-- <option value="0" selected></option> -->
        								<option value="1" selected>一般ユーザー</option>
        								<option value="2">管理者</option>
        							</select>
        						</td>
        					</tr>
        				</table>
        				<br><br>
        				<input type="submit" name="insertUserButton" value="登録">
        			</form>
        		<?php
        		//登録ボタン押下後画面の表示
        		} else {?>
    				<table>
    					<tr>
    						<th style="width: 20vw; background-color: grey;">ユーザー</th>
    						<td style="width: 20vw;"><?=$insertUserName?></td>
    					</tr>
    					<tr>
    						<th style="width: 20vw; background-color: grey;">パスワード</th>
    						<td style="width: 20vw;"><?=$insertPassword?></td>
    					</tr>
    					<tr>
    						<th style="width: 20vw; background-color: grey;">Eメール</th>
    						<td style="width: 20vw;"><?=$insertEmail?></td>
    					</tr>
    					<tr>
    						<th style="width: 20vw; background-color: grey;">権限</th>
    						<td style="width: 20vw;"><?=($insertAuthority === '2') ? '管理者' : '一般ユーザ'?></td>
    					</tr>
    				</table>
    				<p>上記ユーザを登録しました。</p>
    				<br><br>
    				<a href="./listUser.php">ユーザー一覧へ戻る</a>　　
    				<a href="./insertUser.php">続けて登録する</a>
        		<?php
        		}?>
        	</center>
        </main>
        <footer>
        	<br><br><br>
        	<hr style="border: 1px solid blue;">
        	<p>Copyright (C) 20YY All Rights Reserved.</p>
        </footer>
    </body>
</html>