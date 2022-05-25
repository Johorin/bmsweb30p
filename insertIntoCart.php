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

//セッション情報が切れていないかチェック
if(!isset($_SESSION['userInfo'])) {
    //切れてたらエラー番号13とともにエラーページへ遷移
    header('Location: ./error.php?errNum=13');
    exit();
}

//遷移元からのISBN番号（GETパラメータ）を取得
$isbn = $_GET['insertIsbn'];

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

    //カートに追加した書籍情報をそれぞれ変数に格納
    $addIsbn = $addBookInfo['isbn'];
    $addTitle = $addBookInfo['title'];
    $addPrice = $addBookInfo['price'];
}
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>カート追加</title>
	</head>
    <body>
    <header>
    	<h2 align="center">書籍販売システムWeb版 Ver.2.0</h2>
    	<hr style="border: 2px solid blue;">
    	<div class="nav" style="position: absolute; top: 83px; left: 20px;">
    		<a href="./menu.php" style="margin: 0 20px 0 0;">[メニュー]</a>
    		<a href="./list.php">[書籍一覧]</a>
    	</div>
    	<h3 align="center">カート追加</h3>
    	<div class="loginInfo" style="position: absolute; top: 55px; right: 60px;">
    		<p>名前：<?=$userInfo['user']?></p>
    		<p>権限：<?=$authority?></p>
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
        			<td style="background-color: lightblue;">ISBN</td>
        			<td><?=$addIsbn?></td>
        		</tr>
        		<tr>
        			<td style="background-color: lightblue;">TITLE</td>
        			<td><?=$addTitle?></td>
        		</tr>
        		<tr>
        			<td style="background-color: lightblue;">価格</td>
        			<td><?=$addPrice?></td>
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