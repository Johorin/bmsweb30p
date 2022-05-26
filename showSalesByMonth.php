<?php
//セッションの利用を開始
session_start();

//ログイン認証情報を取得する関数のインポート
require_once 'loginAuthentication.php';

//インポートした関数でログイン中のユーザー名と権限を取得
$authInfo = authenticate();

//初期画面から検索ボタンを押された場合の処理
if(isset($_POST['salesSearchButton'])) {
    require_once 'dbprocess.php';

    $searchYear = (int)$_POST['searchYear'];
    $searchMonth = (int)$_POST['searchMonth'];

    $selectSql = "SELECT A.isbn,B.title,B.price,A.quantity FROM orderinfo A inner join bookinfo B on A.isbn=B.isbn WHERE A.date LIKE '{$searchYear}-%' AND A.date LIKE '%-%{$searchMonth}-%'";
    $selectResult = executeQuery($selectSql);

    $salesRecords = array();

    while($salesRecord = mysqli_fetch_assoc($selectResult)) {
        $salesRecords[] = $salesRecord;
    }

    mysqli_free_result($selectResult);

    $total = 0;
}
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>売上状況画面</title>
	</head>
    <body>
	<header>
    	<h2 align="center">書籍管理システム</h2>
    	<hr style="border: 2px solid blue;">
    	<div class="float-left" style="position: absolute; top: 83px; left: 20px;">
    		<a href="./menu.php">[メニュー]</a>
    	</div>
    	<h3 align="center"><?=!isset($_POST['salesSearchButton']) ? '売上げ状況' : "{$searchYear}年{$searchMonth}月売上げ状況"?></h3>
    	<div class="loginInfo" style="position: absolute; top: 55px; right: 60px;">
    		<p>名前：<?=$authInfo['userName']?></p>
    		<p>権限：<?=$authInfo['authority']?></p>
    	</div>
    	<hr style="border: 1px solid black;">
    </header>
    <main>
    	<center>
        	<!-- フォーム部分 -->
        	<div class="forms" style="display: inline-flex;">
            	<form action="./showSalesByMonth.php" method="post">
            		　年<input type="number" name="searchYear" value="<?=$searchYear?>">
            		　月<input type="number" name="searchMonth" value="<?=$searchMonth?>">
            		　<input type="submit" name="salesSearchButton" value="検索">
            	</form>
        	</div>
        	<br><br>
        	<!-- テーブル部分 -->
        	<table>
        		<tr>
        			<th style="width: 20vw; background-color: grey;">ISBN</th>
        			<th style="width: 20vw; background-color: grey;">TITLE</th>
        			<th style="width: 20vw; background-color: grey;">価格</th>
        			<th style="width: 20vw; background-color: grey;">冊数</th>
        			<th style="width: 20vw; background-color: grey;">売上げ小計</th>
        		</tr>
        		<?php
        		if(isset($_POST['salesSearchButton'])) {
            		foreach($salesRecords as $record) {?>
                		<tr>
                			<td><?=$record['isbn']?></td>
                			<td><?=$record['title']?></td>
                			<td><?=$record['price']?>円</td>
                			<td><?=$record['quantity']?>冊</td>
                			<td><?=(int)$record['price'] * (int)$record['quantity']?>円</td>
                		</tr>
            		<?php
            		    $total += (int)$record['price'] * (int)$record['quantity'];
            		}
        		}?>
        	</table>
        	<?php
        	if(isset($_POST['salesSearchButton'])) {?>
        		<br><br>
        		<hr style="border: 2px solid grey;">
        		<table style="float: right;">
        			<th style="width: 10vw; background-color: grey;">合計</th>
        			<td style="width: 10vw;"><?=$total?>円</td>
        		</table>
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