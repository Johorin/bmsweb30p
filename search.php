<?php
/* ルーティング処理 */
if(isset($_POST['listButton']) || isset($_POST['searchButton'])) {   //list.phpもしくは自分自身からの遷移の場合に実行
    //一連のDB処理操作をまとめた関数のインポート
    require_once 'dbprocess.php';

    //POST送信で送られてきた各データを変数に格納
    $isbn = $_POST['isbn'];
    $title = $_POST['title'];
    $price = $_POST['price'];

    //検索用クエリの設定
    $selectSql = "SELECT * FROM bookinfo ";
    $selectSql .= "WHERE isbn LIKE '%{$isbn}%' ";
    $selectSql .= "AND title LIKE '%{$title}%' ";
    $selectSql .= "AND price LIKE '%{$price}%' ";
    $selectSql .= "ORDER BY isbn ASC";

    //検索用クエリの発行
    $selectResult = executeQuery($selectSql);

    //検索結果があった場合にのみ実行
    if($selectResult) {
        //検索にヒットしたレコードをある限り取得して配列$hitRecordsに格納
        $hitRecords = array();
        while($record = mysqli_fetch_array($selectResult)) {
            $hitRecords[] = $record;
        }
    } else {    //検索に失敗したときは独自エラー
        die('書籍の検索に失敗しました。');
    }

    //検索結果セットの開放
    mysqli_free_result($selectResult);
} else {    //list.php以外からの遷移の場合に実行
    //menu.phpにリダイレクト
    header('Location: ./menu.php');
    exit;
}
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>書籍一覧画面</title>
	</head>
    <body>
	<header>
    	<h2 align="center">書籍販売システムWeb版 Ver.2.0</h2>
    	<hr style="border: 2px solid blue;">
    	<div class="float-left" style="position: absolute; top: 83px; left: 20px;">
    		<a href="./menu.php" style="margin: 0 20px 0 0;">[メニュー]</a>
    		<a href="./insert.php">[書籍登録]</a>
    	</div>
    	<h3 align="center">書籍一覧</h3>
    	<hr style="border: 1px solid black;">
    </header>
    <main>
    	<br><br>
    	<!-- フォーム部分 -->
    	<div class="forms" style="display: inline-flex;">
        	<form action="./search.php" method="post">
        		　ISBN<input type="text" name="isbn" value="<?=$isbn?>">
        		　TITLE<input type="text" name="title" value="<?=$title?>">
        		　価格<input type="text" name="price" value="<?=$price?>">
        		　<input type="submit" name="searchButton" value="検索">
        	</form>
        	<form action="./list.php" method="get">
        		　<input type="submit" value="全件表示">
        	</form>
    	</div>
    	<br><br>
    	<!-- テーブル部分 -->
    	<table>
    		<tr>
    			<th style="width: 25vw; background-color: lightblue;">ISBN</th>
    			<th style="width: 25vw; background-color: lightblue;">TITLE</th>
    			<th style="width: 25vw; background-color: lightblue;">価格</th>
    			<th style="width: 25vw; background-color: lightblue;">変更/削除</th>
    		</tr>
    		<?php
    		//検索結果が１件でもヒットした場合の表示
    		if(count($hitRecords) != 0) {
        		foreach($hitRecords as $record) {?>
        		<tr>
        			<td><a href="./detail.php?isbn=<?=$record['isbn']?>"><?=$record['isbn']?></a></td>
        			<td><?=$record['title']?></td>
        			<td><?=$record['price']?>円</td>
        			<td>
        				<a href="./update.php?updateIsbn=<?=$record['isbn']?>" style="margin-right: 20px">変更</a>
        				<a href="./delete.php?deleteIsbn=<?=$record['isbn']?>">削除</a>
        			</td>
        		</tr>
        		<?php
        		//foreach文の終わり
        		}
    		//if文の終わり
    		}?>
    	</table>
		<?php
		//検索の結果何もヒットしなかった場合の表示
		if(count($hitRecords) == 0) {?>
		<p>検索に一致する書籍はありませんでした。</p>
		<?php
		}?>
    </main>
    <footer>
    	<br><br><br>
    	<hr style="border: 1px solid blue;">
    	<p>Copyright (C) 20YY All Rights Reserved.</p>
    </footer>
    </body>
</html>