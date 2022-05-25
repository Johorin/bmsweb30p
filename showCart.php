<?php
//セッションの利用を開始
session_start();

//セッション情報が切れていないかチェック
if(!isset($_SESSION['userInfo'])) {
    //切れてたらエラー番号15とともにエラーページへ遷移
    header('Location: ./error.php?errNum=15');
    exit();
}

//セッションに登録されているユーザー情報を取得
$userInfo = $_SESSION['userInfo'];

if(!isset($userInfo)) { //セッションに登録されているユーザー情報が無い場合実行
    //ログイン画面に遷移
    header('Location: ./login.php');
    exit();
} else {
    //ユーザー情報
    switch ((int)$userInfo['authority']) {
        case '1':
            $authority = '一般ユーザ';
            break;
        case '2':
            $authority  = '管理者';
    }
}

//削除リンクから戻ってきている場合の処理
if(isset($_GET['deleteIsbn'])) {
    //指定の削除番号のデータを削除
    unset($_SESSION['cartInfo'][$_GET['deleteIsbn']]);
}

//カートの中の書籍の価格を合計
$total = 0;
if(isset($_SESSION['cartInfo'])) {
    foreach($_SESSION['cartInfo'] as $bookData) {
        $total += $bookData['price'];
    }
}
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>カート内容</title>
	</head>
    <body>
    <header>
    	<h2 align="center">書籍販売システムWeb版 Ver.2.0</h2>
    	<hr style="border: 2px solid blue;">
    	<div class="nav" style="position: absolute; top: 83px; left: 20px;">
    		<a href="./menu.php" style="margin: 0 20px 0 0;">[メニュー]</a>
    		<a href="./list.php">[書籍一覧]</a>
    	</div>
    	<h3 align="center">カート内容</h3>
    	<div class="loginInfo" style="position: absolute; top: 55px; right: 60px;">
    		<p>名前：<?=$userInfo['user']?></p>
    		<p>権限：<?=$authority?></p>
    	</div>
    	<hr style="border: 1px solid black;">
    </header>
    <main>
    	<center>
        	<br><br><br>
        	<!-- テーブル部分 -->
        	<table>
        		<tr>
        			<th style="width: 25vw; background-color: lightblue;">ISBN</th>
        			<th style="width: 25vw; background-color: lightblue;">TITLE</th>
        			<th style="width: 25vw; background-color: lightblue;">価格</th>
        			<th style="width: 25vw; background-color: lightblue;">削除</th>
        		</tr>
        		<?php
        		if(isset($_SESSION['cartInfo'])) {
            		foreach($_SESSION['cartInfo'] as $bookData) {?>
            		<tr>
            			<td><a href="./detail.php?isbn=<?=$bookData['isbn']?>"><?=$bookData['isbn']?></a></td>
            			<td><?=$bookData['title']?></td>
            			<td><?=$bookData['price']?>円</td>
            			<td><a href="./showCart.php?deleteIsbn=<?=$bookData['isbn']?>">削除</a></td>
            		</tr>
            		<?php
            		}
        		}?>
        	</table>
        	<br><br>
        	<hr style="border: 2px solid grey;">
        	<table>
        		<tr>
        			<th style="width: 10vw; background-color: lightblue;">合計</th>
        			<td><?=$total?>円</td>
        		</tr>
        	</table>
        	<br><br><br>
        	<form action="./buyConfirm.php" method="post">
        		<input type="submit" name="buyButton" value="購入">
        	</form>
    	</center>
    </main>
    <footer>
    	<br><br><br>
    	<hr style="border: 1px solid blue;">
    	<p>Copyright (C) 20YY All Rights Reserved.</p>
    </footer>
    </body>
</html>