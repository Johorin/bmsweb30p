<?php
//セッションの利用を開始
session_start();

//ログイン認証情報を取得する関数のインポート
require_once 'loginAuthentication.php';

//インポートした関数でログイン中のユーザー名と権限を取得
$authInfo = authenticate();

//一連のDB操作処理をまとめた関数を読み込む
require_once 'dbprocess.php';

//セッション情報が切れていないかチェック
if(!isset($_SESSION['userInfo'])) {
    //切れてたらエラー番号13とともにエラーページへ遷移
    header('Location: ./error.php?errNum=13');
    exit();
}

//遷移元からのISBN番号（GETパラメータ）を取得
$isbn = $_POST['insertIsbn'];

//取得したISBNの書籍情報を検索するクエリ文を設定&発行
$selectSql = "select * from bookinfo where isbn={$isbn}";
$selectResult = executeQuery($selectSql);

if(!$selectResult) { //書籍情報が取得できなかった場合
    //メモリ開放
    mysqli_free_result($selectResult);

    //エラー番号14とともにエラーページへ遷移
    header('Location: ./error.php?errNum=14');
    exit();
} else {    //書籍情報が取得できた場合
    //書籍情報を連想配列として取得
    $addBookInfo = mysqli_fetch_assoc($selectResult);

    //メモリ開放
    mysqli_free_result($selectResult);

    //その書籍情報をセッションに格納
    $_SESSION['cartInfo'][] = $addBookInfo;
    $_SESSION['cartInfo']['quantity'] = $_POST['quantity'];

    //カートに追加した書籍情報をそれぞれ変数に格納
    $addIsbn = $addBookInfo['isbn'];
    $addTitle = $addBookInfo['title'];
    $addPrice = $addBookInfo['price'];
    $quantity = $_POST['quantity'];
}
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>カート追加</title>
	</head>
    <body>
    <header>
    	<h2 align="center">書籍管理システム</h2>
    	<hr style="border: 2px solid blue;">
    	<div class="nav" style="position: absolute; top: 83px; left: 20px;">
    		<a href="./menu.php" style="margin: 0 20px 0 0;">[メニュー]</a>
    		<a href="./list.php">[書籍一覧]</a>
    	</div>
    	<h3 align="center">カート追加</h3>
    	<div class="loginInfo" style="position: absolute; top: 55px; right: 60px;">
    		<p>名前：<?=$authInfo['userName']?></p>
    		<p>権限：<?=$authInfo['authority']?>></p>
    	</div>
    	<hr style="border: 1px solid black;">
    </header>
    <main>
        <center>
        	<br><br>
        	<h4>下記の書籍をカートに追加しました。</h4>
        	<br>
        	<table>
        		<tr>
        			<td style="background-color: grey;">ISBN</td>
        			<td><?=$addIsbn?></td>
        		</tr>
        		<tr>
        			<td style="background-color: grey;">TITLE</td>
        			<td><?=$addTitle?></td>
        		</tr>
        		<tr>
        			<td style="background-color: grey;">価格</td>
        			<td><?=$addPrice?></td>
        		</tr>
        		<tr>
        			<td style="background-color: grey;">購入数</td>
        			<td><?=$quantity?>冊</td>
        		</tr>
        	</table>
        	<br>
        	<form action="./showCart.php" method="post">
        		<input type="submit" name="confirmCart" value="カート確認">
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