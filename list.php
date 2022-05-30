<?php
//セッションの利用を開始
session_start();

//ログイン認証情報を取得する関数のインポート
require_once 'loginAuthentication.php';

//インポートした関数でログイン中のユーザー名と権限を取得
$authInfo = authenticate();

/* 表示する書籍一覧を取得 */
require_once 'dbprocess.php';

$selectSql = "SELECT * FROM bookinfo ORDER BY isbn ASC";
$selectResult = executeQuery($selectSql);

$bookLists = array();

while($bookList = mysqli_fetch_assoc($selectResult)) {
    $bookLists[] = $bookList;
}
mysqli_free_result($selectResult);
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>書籍一覧画面</title>
	</head>
    <body>
    	<header>
        	<h2 align="center">書籍管理システム</h2>
        	<hr style="border: 2px solid blue;">
        	<div class="float-left" style="position: absolute; top: 83px; left: 20px;">
        		<a href="./menu.php" style="margin: 0 20px 0 0;">[メニュー]</a>
        		<a href="./insert.php">[書籍登録]</a>
        	</div>
        	<h3 align="center">書籍一覧</h3>
        	<div class="loginInfo" style="position: absolute; top: 55px; right: 60px;">
        		<p>名前：<?=$authInfo['userName']?></p>
        		<p>権限：<?=$authInfo['authority']?></p>
        	</div>
        	<hr style="border: 1px solid black;">
        </header>
        <main>
        	<br><br>
        	<?php
        	//管理者でログインしている時の画面表示
        	if($authInfo['authority'] == '管理者') {?>
            	<!-- フォーム部分 -->
            	<div class="forms" style="display: inline-flex;">
                	<form action="./search.php" method="post">
                		　ISBN<input type="text" name="isbn">
                		　TITLE<input type="text" name="title">
                		　価格<input type="text" name="price">
                		　<input type="submit" name="listButton" value="検索">
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
            			<th style="width: 25vw; background-color: lightblue;"></th>
            		</tr>
            		<?php
                	foreach($bookLists as $record) {?>
                		<tr>
                			<td><a href="./detail.php?isbn=<?=$record['isbn']?>&title=<?=$record['title']?>&price=<?=$record['price']?>"><?=$record['isbn']?></a></td>
                			<td><?=$record['title']?></td>
                			<td><?=$record['price']?>円</td>
                			<td>
                				<a href="./update.php?updateIsbn=<?=$record['isbn']?>" style="margin-right: 20px">変更</a>
                				<a href="./delete.php?deleteIsbn=<?=$record['isbn']?>" style="margin-right: 20px">削除</a>
                			</td>
                		</tr>
            		<?php
            		}?>
            	</table>
        	<?php
        	//一般ユーザでログインしている時の画面表示
        	} elseif($authInfo['authority'] == '一般ユーザ') {?>
            	<!-- フォーム部分 -->
            	<div class="forms" style="display: inline-flex;">
                	<form action="./search.php" method="post">
                		　ISBN<input type="text" name="isbn">
                		　TITLE<input type="text" name="title">
                		　価格<input type="text" name="price">
                		　<input type="submit" name="listButton" value="検索">
                	</form>
                	<form action="./list.php" method="get">
                		　<input type="submit" value="全件表示">
                	</form>
            	</div>
            	<br><br>
                <!-- テーブル部分 -->
            	<table>
            		<tr>
            			<th style="width: 20vw; background-color: lightblue;">ISBN</th>
            			<th style="width: 20vw; background-color: lightblue;">TITLE</th>
            			<th style="width: 20vw; background-color: lightblue;">価格</th>
            			<th style="width: 20vw; background-color: lightblue;">購入数</th>
            			<th style="width: 20vw; background-color: lightblue;"></th>
            		</tr>
            		<?php
            		foreach($bookLists as $record) {?>
                		<tr>
                			<td><a href="./detail.php?isbn=<?=$record['isbn']?>"><?=$record['isbn']?></a></td>
                			<td><?=$record['title']?></td>
                			<td><?=$record['price']?>円</td>
            				<form action="./insertIntoCart.php" method="post">
                    			<td><input type="number" name="quantity"></td>
                    			<td>
                    				<input type="hidden" name="insertIsbn" value="<?=$record['isbn']?>">
                    				<input type="submit" name="intoCartButton" value="カートに入れる">
                    			</td>
                    		</form>
                		</tr>
            		<?php
            		}?>
            	</table>
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