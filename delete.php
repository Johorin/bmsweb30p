<?php
/* ルーティング処理 */
if(isset($_POST['detailButton'])) {      //detail.phpからの遷移の場合に実行
    //POST送信で送られてきた削除する書籍データのisbnを変数に格納
    $isbn = $_POST['deleteIsbn'];
} elseif(isset($_GET['deleteIsbn'])) {  //list.phpからの遷移の場合に実行
    //GET送信で送られてきた削除する書籍データのisbnを変数に格納
    $isbn = $_GET['deleteIsbn'];
} else {                                  //detail.php以外からの遷移の場合に実行
    //list.phpにリダイレクト
    header('Location: ./list.php');
    exit;
}

//一連のDB処理操作をまとめた関数のインポート
require_once 'dbprocess.php';

//検索用クエリの設定
$selectSql = "SELECT * FROM bookinfo WHERE isbn = '{$isbn}'";
//検索用クエリの発行
$selectResult = executeQuery($selectSql);

if(!$selectResult) {    //削除対象のデータが存在しない時の処理
    header('Location: ./error.php?errNum=11');
    exit;
} else {               //削除対象のデータが見つかった時の処理
    //削除対象のレコードを取得
    $record = mysqli_fetch_array($selectResult);
    //検索結果セットの開放
    mysqli_free_result($selectResult);
    //削除用クエリの設定
    $deletetSql = "DELETE FROM bookinfo WHERE isbn = '{$isbn}'";
    //削除用クエリの発行
    executeQuery($deletetSql);
}
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>書籍削除画面</title>
	</head>
    <body>
	<header>
    	<h2 align="center">書籍販売システムWeb版 Ver.2.0</h2>
    	<hr style="border: 2px solid blue;">
    	<div class="float-left" style="position: absolute; top: 83px; left: 20px;">
    		<a href="./menu.php" style="margin-right: 20px;">[メニュー]</a>
    		<a href="./insert.php" style="margin-right: 20px;">[書籍登録]</a>
    		<a href="./list.php">[書籍一覧]</a>
    	</div>
    	<h3 align="center">書籍削除</h3>
    	<hr style="border: 1px solid black;">
    </header>
	<main>
	<br><br>
	<center>
		<br><br>
		<table>
			<tr>
				<td style="padding: 2px 20px 2px 5px; background-color: lightblue;">ISBN</td>
				<td><?=$record['isbn']?></td>
			</tr>
			<tr>
				<td style="padding: 2px 20px 2px 5px; background-color: lightblue;">TITLE</td>
				<td><?=$record['title']?></td>
			</tr>
			<tr>
				<td style="padding: 2px 20px 2px 5px; background-color: lightblue;">価格</td>
				<td><?=$record['price']?>円</td>
			</tr>
		</table>
    	<br><br>
    	<p>上記データを削除しました。</p>
    	<br><br>
    	<a href="./list.php">書籍一覧へ戻る</a>
	</center>
	</main>
    <footer>
    	<br><br><br>
    	<hr style="border: 1px solid blue;">
    	<p>Copyright (C) 20YY All Rights Reserved.</p>
    </footer>
    </body>
</html>