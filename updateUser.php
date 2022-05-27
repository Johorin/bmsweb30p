<?php
//セッションの利用を開始
session_start();

//ログイン認証情報を取得する関数のインポート
require_once 'loginAuthentication.php';

//インポートした関数でログイン中のユーザー名と権限を取得
$authInfo = authenticate();

//権限が一般ユーザーからのアクセスの際にはメニュー画面へリダイレクト
if($authInfo === '一般ユーザ') {
    header('Location: ./menu.php');
    exit;
}

require_once 'dbprocess.php';

//detailUser.phpもしくはlistUser.phpからの遷移の場合の処理
if(isset($_GET['updateUserName'])) {
    /* 送られてきたユーザーIDを元に元のデータを再形成 */
    $userName = $_GET['updateUserName'];
    $selectSql = "SELECT * FROM userinfo WHERE user='{$userName}'";
    $selectResult = executeQuery($selectSql);
    $userData = mysqli_fetch_assoc($selectResult);
    mysqli_free_result($selectResult);
}
//自分自身からの遷移の場合の処理（データ更新処理）
elseif(isset($_POST['updateUserButton'])) {
    /* 送られてきたPOSTデータを元に変更する前のユーザー情報を取得 */
    $beforeUserName = $_POST['updateUserName'];
    $selectSql = "SELECT * FROM userinfo WHERE user='{$beforeUserName}'";
    $selectResult = executeQuery($selectSql);
    $beforeUserData = mysqli_fetch_assoc($selectResult);
    mysqli_free_result($selectResult);

    /* ユーザー情報変更処理 */
    $updateUserName = $_POST['updateUserName'];
    $newPassword = $_POST['newPassword'];
    $newPasswordAgain = $_POST['newPasswordAgain'];
    $newEmail = $_POST['newEmail'];
    $newAuthority = $_POST['newAuthority'];

    //入力されたパスワードと確認用パスワードが一致しない場合メニュー画面へ遷移
    if($newPassword != $newPasswordAgain) {
        header('Location: ./menu.php');
        exit;
    }

    $updateSql = "UPDATE userinfo SET password='{$newPassword}',email='{$newEmail}',authority='{$newAuthority}' WHERE user='{$updateUserName}'";
    executeQuery($updateSql);

    /* 変更後のユーザー情報を取得 */
    $updatedUserName = $_POST['updateUserName'];
    $selectSql = "SELECT * FROM userinfo WHERE user='{$updatedUserName}'";
    $selectResult = executeQuery($selectSql);
    $updatedUserData = mysqli_fetch_assoc($selectResult);
    mysqli_free_result($selectResult);

    //パスワードを文字数分'*'で置換処理
    $secretPass = '';

    for($tmp = 0; $tmp < strlen($updatedUserData['password']); $tmp++) {
        $secretPass .= '*';
    }
}
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>ユーザー変更画面</title>
	</head>
    <body>
    	<header>
        	<h2 align="center">書籍管理システム</h2>
        	<hr style="border: 2px solid blue;">
        	<div class="float-left" style="position: absolute; top: 83px; left: 20px;">
        		<a href="./menu.php" style="margin: 0 20px 0 0;">[メニュー]</a>
        		<a href="./insertUser.php" style="margin: 0 20px 0 0;">[ユーザー登録]</a>
        		<a href="./listUser.php">[ユーザー一覧]</a>
        	</div>
        	<h3 align="center">ユーザー変更</h3>
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
        		if(!isset($_POST['updateUserButton'])) {?>
        		<form action="./updateUser.php" method="post">
        			<table>
        				<tr>
        					<th style="width: 180px;"></th>
        					<td>&lt;&lt;変更前情報&gt;&gt;</td>
        					<td>&lt;&lt;変更後情報&gt;&gt;</td>
        				</tr>
        				<tr>
        					<th style="width: 180px; background-color: grey; text-align: left; padding-left: 20px;">ユーザー</th>
        					<td style="width: 200px;"><?=$userData['user']?></td>
        					<td style="width: 200px;"><?=$userData['user']?></td>
        				</tr>
        				<tr>
        					<th style="width: 180px; background-color: grey; text-align: left; padding-left: 20px;">パスワード</th>
        					<td style="width: 200px;"><?=$userData['password']?></td>
        					<td style="width: 200px;"><input type="password" name="newPassword" style="width: 100%" required></td>
        				</tr>
        				<tr>
        					<th style="width: 180px; background-color: grey; text-align: left; padding-left: 20px;">パスワード（確認用）</th>
        					<td style="width: 200px;"></td>
        					<td style="width: 200px;"><input type="password" name="newPasswordAgain" style="width: 100%" required></td>
        				</tr>
        				<tr>
        					<th style="width: 180px; background-color: grey; text-align: left; padding-left: 20px;">Eメール</th>
        					<td style="width: 200px;"><?=$userData['email']?></td>
        					<td style="width: 200px;"><input type="text" name="newEmail" style="width: 100%" required></td>
        				</tr>
        				<tr>
        					<th style="width: 180px; background-color: grey; text-align: left; padding-left: 20px;">権限</th>
        					<td style="width: 200px;"><?=($userData['authority'] === '1') ? '一般ユーザ' : '管理者'?></td>
        					<td style="width: 200px;">
        						<select name="newAutority" style="width: 100%">
        							<option value="1" selected>一般ユーザ</option>
        							<option value="2">管理者</option>
        						</select>
        					</td>
        				</tr>
        			</table>
        			<br>
        			<input type="hidden" name="updateUserName" value="<?=$userName?>">
        			<input type="submit" name="updateUserButton" value="変更完了">
        		</form>
        		<?php
        		} else {?>
        			<table>
        				<tr>
        					<th style="width: 180px;"></th>
        					<td>&lt;&lt;変更前情報&gt;&gt;</td>
        					<td>&lt;&lt;変更後情報&gt;&gt;</td>
        				</tr>
        				<tr>
        					<th style="width: 180px; background-color: grey; text-align: left; padding-left: 20px;">ユーザー</th>
        					<td style="width: 200px;"><?=$beforeUserData['user']?></td>
        					<td style="width: 200px;"><?=$beforeUserData['user']?></td>
        				</tr>
        				<tr>
        					<th style="width: 180px; background-color: grey; text-align: left; padding-left: 20px;">パスワード</th>
        					<td style="width: 200px;"><?=$beforeUserData['password']?></td>
        					<td style="width: 200px;"><?=$secretPass?></td>
        				</tr>
        				<tr>
        					<th style="width: 180px; background-color: grey; text-align: left; padding-left: 20px;">Eメール</th>
        					<td style="width: 200px;"><?=$beforeUserData['email']?></td>
        					<td style="width: 200px;"><?=$updatedUserData['email']?></td>
        				</tr>
        				<tr>
        					<th style="width: 180px; background-color: grey; text-align: left; padding-left: 20px;">権限</th>
        					<td style="width: 200px;"><?=($beforeUserData['authority'] === '1') ? '一般ユーザ' : '管理者'?></td>
        					<td style="width: 200px;"><?=($updatedUserData['authority'] === '1') ? '一般ユーザ' : '管理者'?></td>
        				</tr>
        			</table>
        			<p>上記内容でデータを更新しました。</p>
        			<br>
        			<a href="./listUser.php">ユーザー一覧へ戻る</a>
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