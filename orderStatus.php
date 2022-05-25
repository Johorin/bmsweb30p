<?php
//セッションの利用を開始
session_start();

//セッションに登録されているユーザー情報を取得
$userInfo = $_SESSION['userInfo'];

if(!isset($userInfo)) { //セッションに登録されているユーザー情報が無い場合実行
    //ログイン画面に遷移
    header('Location: ./login.php');
    exit();
} else {
    //ユーザー情報
    switch ($userInfo['authority']) {
        case '1':
            $authority = '一般ユーザ';
            break;
        case '2':
            $authority  = '管理者';
    }
}

//orderinfoテーブルを検索し登録されている書籍情報を格納
require_once 'dbprocess.php';   //一連のDB操作処理をまとめた関数を読み込む
$selectSql = "SELECT user,title,date FROM orderinfo A inner join bookinfo B on A.isbn=B.isbn where user='{$userInfo['user']}' order by date";
$selectResult = executeQuery($selectSql);

//検索結果の取得に失敗した場合は独自エラー
if(!$selectResult) {
    die('購入状況書籍の取得に失敗しました。');
}

//購入済みの書籍情報レコードを配列$boughtBooksに逐次格納
$boughtBooks = array();
while($boughtBook = mysqli_fetch_assoc($selectResult)) {
    $boughtBooks[] =$boughtBook;
}

//検索結果セットの開放
mysqli_free_result($selectResult);
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>購入状況画面</title>
	</head>
    <body>
    <header>
    	<h2 align="center">書籍販売システムWeb版 Ver.2.0</h2>
    	<hr style="border: 2px solid blue;">
    	<div class="nav" style="position: absolute; top: 83px; left: 20px;">
    		<a href="./menu.php">[メニュー]</a>
    	</div>
    	<h3 align="center">購入状況</h3>
    	<div class="loginInfo" style="position: absolute; top: 55px; right: 60px;">
    		<p>名前：<?=$userInfo['user']?></p>
    		<p>権限：<?=$authority?></p>
    	</div>
    	<hr style="border: 1px solid black;">
    </header>
    <main>
    	<center>
    		<br><br>
    		<table>
    			<tr>
    				<th style="width: 25vw; background-color: lightblue;">ユーザー</th>
    				<th style="width: 25vw; background-color: lightblue;">TITLE</th>
    				<th style="width: 25vw; background-color: lightblue;">注文日</th>
    			</tr>
    			<?php
    			foreach($boughtBooks as $record) {?>
    			<tr>
    				<td><?=$record['user']?></td>
    				<td><?=$record['title']?></td>
    				<td><?=$record['date']?></td>
    			</tr>
    			<?php
    			}?>
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