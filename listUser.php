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

$selectSql = 'SELECT user,email,authority FROM userinfo';
$selectResult = executeQuery($selectSql);

$userLists = array();

while($userList = mysqli_fetch_assoc($selectResult)) {
    $userLists[] = $userList;
}

mysqli_free_result($selectResult);
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>ユーザー一覧画面</title>
	</head>
    <body>
    	<header>
        	<h2 align="center">書籍管理システム</h2>
        	<hr style="border: 2px solid blue;">
        	<div class="float-left" style="position: absolute; top: 83px; left: 20px;">
        		<a href="./menu.php" style="margin: 0 20px 0 0;">[メニュー]</a>
        		<a href="./insertUser.php">[ユーザー登録]</a>
        	</div>
        	<h3 align="center">ユーザー一覧</h3>
        	<div class="loginInfo" style="position: absolute; top: 55px; right: 60px;">
        		<p>名前：<?=$authInfo['userName']?></p>
        		<p>権限：<?=$authInfo['authority']?></p>
        	</div>
        	<hr style="border: 1px solid black;">
        </header>
        <main>
        	<br><br>
        	<!-- フォーム部分 -->
        	<div class="forms" style="display: inline-flex;">
            	<form action="./searchUser.php" method="post">
            		　ユーザー<input type="text" name="searchUserName">
            		　　　<input type="submit" name="searchUserButton" value="検索">
            	</form>
            	<form action="./listUser.php" method="post">
            		　<input type="submit" name="showAll" value="全件表示">
            	</form>
        	</div>
        	<br><br>
        	<!-- テーブル部分 -->
        	<table>
        		<tr>
        			<th style="width: 25vw; background-color: grey;">ユーザー</th>
        			<th style="width: 25vw; background-color: grey;">Eメール</th>
        			<th style="width: 25vw; background-color: grey;">権限</th>
        			<th style="width: 25vw; background-color: grey;"></th>
        		</tr>
        		<?php
        		foreach($userLists as $record) {?>
            		<tr>
            			<td><a href="./detailUser.php?detailUserName=<?=$record['user']?>"><?=$record['user']?></a></td>
            			<td><?=$record['email']?></td>
            			<td><?=($record['authority'] === '1') ? '一般ユーザー' : '管理者'?></td>
            			<td>
            				<a href="./updateUser.php?updateUserName=<?=$record['user']?>" style="margin-right: 20px">変更</a>
            				<a href="./deleteUser.php?deleteUserName=<?=$record['user']?>" style="margin-right: 20px">削除</a>
            			</td>
            		</tr>
        		<?php
        		}?>
        	</table>
        </main>
        <footer>
        	<br><br><br>
        	<hr style="border: 1px solid blue;">
        	<p>Copyright (C) 20YY All Rights Reserved.</p>
        </footer>
    </body>
</html>