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

//一連のDB操作処理をまとめた関数を読み込む
require_once 'dbprocess.php';

//書籍データがデータベースに存在するか確認
$selectSql = 'SELECT * FROM bookinfo order by isbn';
$selectResult = executeQuery($selectSql);

//検索結果がヒットすればエラー（エラー番号18）
if($selectResult && !is_null(mysqli_fetch_assoc($selectResult))) {
    header('Location: ./error.php?errNum=18');
    exit();
}

//読み込みファイルが所定の位置になければエラー（エラー番号19）
$file_path = "./file/initial_data.csv";
if(!file_exists($file_path)) {
    header('Location: ./error.php?errNum=19');
    exit();
}

//初期データファイルを読み込む
$fp = fopen($file_path, 'r');
$iniBookList = array();

//読み込んだcsvファイルを2次元配列$iniBookListとして整えつつINSERT
$bookNum = 0;
while($iniBook = fgetcsv($fp)) {
    $iniBookList[$bookNum]['isbn'] = $iniBook[0];
    $iniBookList[$bookNum]['title'] = $iniBook[1];
    $iniBookList[$bookNum]['price'] = $iniBook[2];

    //bookinfoテーブルにcsvファイルから読み込んだデータを登録
    $insertSql = "INSERT INTO bookinfo VALUES('{$iniBookList[$bookNum]['isbn']}','{$iniBookList[$bookNum]['title']}','{$iniBookList[$bookNum]['price']}')";

    //insertに失敗した場合は独自エラー
    if(!executeQuery($insertSql)) {
        die('初期データの登録に失敗しました。');
    }

    //行番号をインクリメント
    $bookNum++;
}
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>初期データ登録画面</title>
	</head>
    <body>
    <header>
    	<h2 align="center">書籍販売システムWeb版 Ver.2.0</h2>
    	<hr style="border: 2px solid blue;">
    	<div class="nav" style="position: absolute; top: 83px; left: 20px;">
    		<a href="./menu.php">[メニュー]</a>
    	</div>
    	<h3 align="center">初期データ登録</h3>
    	<div class="loginInfo" style="position: absolute; top: 55px; right: 60px;">
    		<p>名前：<?=$userInfo['user']?></p>
    		<p>権限：<?=$authority?></p>
    	</div>
    	<hr style="border: 1px solid black;">
    </header>
    <main>
    	<center>
    		<br><br>
    		<h3>初期データとして以下のデータを登録しました。</h3>
    		<br>
    		<table>
    			<tr>
    				<th style="width: 25vw; background-color: lightblue;">ISBN</th>
    				<th style="width: 25vw; background-color: lightblue;">TITLE</th>
    				<th style="width: 25vw; background-color: lightblue;">価格</th>
    			</tr>
    			<?php
    			foreach($iniBookList as $record) {?>
    			<tr>
    				<td><?=$record['isbn']?></td>
    				<td><?=$record['title']?></td>
    				<td><?=$record['price']?></td>
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