<?php
//GET送信されたデータが存在しない場合には実行させない
if(!isset($_GET['isbn'])) {
    exit;
}

require_once 'dbprocess.php';

$isbn = $_GET['isbn'];

$selectSql = "SELECT * FROM bookinfo WHERE isbn = '{$isbn}'";

$selectResult = executeQuery($selectSql);

/* エラー判定 */
//画面遷移時に詳細表示対象データが存在しない場合
if(!$selectResult) {
    header('Location: ./error.php?errNum=6');
    exit;
}

$records = mysqli_fetch_array($selectResult);
mysqli_free_result($selectResult);
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>書籍詳細画面</title>
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
    	<h3 align="center">書籍詳細情報</h3>
    	<hr style="border: 1px solid black;">
    </header>
	<main>
	<br><br>
	<center>
		<div class="forms" style="display: inline-flex;">
			<form action="./update.php" method="get">
				<input type="hidden" name="updateIsbn" value="<?=$records['isbn']?>">
				<input type="submit" value="変更" style="margin-right: 60px">
			</form>
			<form action="./delete.php" method="post">
				<input type="hidden" name="deleteIsbn" value="<?=$records['isbn']?>">
				<input type="submit" name="detailButton" value="削除">
			</form>
		</div>
		<br><br>
		<table>
			<tr>
				<td style="padding: 2px 20px 2px 5px; background-color: lightblue;">ISBN</td>
				<td><?=$records['isbn']?></td>
			</tr>
			<tr>
				<td style="padding: 2px 20px 2px 5px; background-color: lightblue;">TITLE</td>
				<td><?=$records['title']?></td>
			</tr>
			<tr>
				<td style="padding: 2px 20px 2px 5px; background-color: lightblue;">価格</td>
				<td><?=$records['price']?>円</td>
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