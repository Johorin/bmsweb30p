<?php
//セッションの利用を開始
session_start();

//showCart.phpの購入ボタンからの遷移以外はメニュ―画面へリダイレクト
if(!isset($_POST['buyButton'])) {
    header('Location: ./menu.php');
    exit();
}

//セッション情報が切れていないかチェック
if(!isset($_SESSION['userInfo'])) {
    //切れてたらエラー番号16とともにエラーページへ遷移
    header('Location: ./error.php?errNum=16');
    exit();
}

//カートの中身があるかチェック
if(!isset($_SESSION['cartInfo'][0])) {
    //1つも無ければエラー番号17とともにエラーページへ遷移
    header('Location: ./error.php?errNum=17');
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
    switch ($userInfo['authority']) {
        case '1':
            $authority = '一般ユーザ';
            break;
        case '2':
            $authority  = '管理者';
    }
}

/* カートの中の書籍情報をorderinfoテーブルに登録しつつ価格の合計を計算 */
$total = 0; //価格の合計を格納する変数
$cartInfo = $_SESSION['cartInfo'];  //cartInfoパラメータのセッションの内容を変数に用意
require_once 'dbprocess.php';   //一連のDB操作処理をまとめた関数を読み込む

//購入情報をDBのorderinfoテーブルに登録
foreach($cartInfo as $boughtBook) {
    $user = $userInfo['user'];
    $isbn = $boughtBook['isbn'];
    $quantity = 1;
    $date = date('Y-m-d');

    $insertSql = "insert into orderinfo(user,isbn,quantity,date) values('{$user}','{$isbn}','{$quantity}','{$date}')";
    executeQuery($insertSql);

    //価格を合計
    $total += $boughtBook['price'];
}

/* 自動メール送信処理 */
//送信準備
mb_language("japanese");
mb_internal_encoding("UTF-8");
$to= 'hhoyuak2145@gmail.com'; //ご自分のアドレスを入れてください
$sbj="ご注文の受付完了";

//本文の設定
$body = <<<EOF
{$userInfo['user']}様

本のご購入ありがとうございます。
以下内容でご注文を受け付けましたので、ご連絡致します。
\n
EOF;
foreach($cartInfo as $bookData) {
    $body .= "{$bookData['isbn']} {$bookData['title']} {$bookData['price']}円\n";
}
$body .= "合計 {$total}円\n\n";
$body .= 'またのご利用よろしくお願いします。';

$hdr = "Content-Type: text/plain;charset=ISO-2022-JP";

//送信
mb_send_mail($to, $sbj, $body, $hdr);

/* カートの中身削除 */
unset($_SESSION['cartInfo']);
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>購入品確認</title>
	</head>
    <body>
    <header>
    	<h2 align="center">書籍販売システムWeb版 Ver.2.0</h2>
    	<hr style="border: 2px solid blue;">
    	<div class="nav" style="position: absolute; top: 83px; left: 20px;">
    		<a href="./menu.php" style="margin: 0 20px 0 0;">[メニュー]</a>
    		<a href="./list.php">[書籍一覧]</a>
    	</div>
    	<h3 align="center">購入品確認</h3>
    	<div class="loginInfo" style="position: absolute; top: 55px; right: 60px;">
    		<p>名前：<?=$userInfo['user']?></p>
    		<p>権限：<?=$authority?></p>
    	</div>
    	<hr style="border: 1px solid black;">
    </header>
    <main>
    	<center>
    		<h4>下記の商品を購入しました。</h4>
    		<h4>ご利用ありがとうございました。</h4>
    		<br>
    		<table>
    			<tr>
    				<th style="width: 25vw; background-color: lightblue;">ISBN</th>
    				<th style="width: 25vw; background-color: lightblue;">TITLE</th>
    				<th style="width: 25vw; background-color: lightblue;">価格</th>
    			</tr>
    			<?php
            		foreach($cartInfo as $bookData) {?>
            		<tr>
            			<td><?=$bookData['isbn']?></td>
            			<td><?=$bookData['title']?></td>
            			<td><?=$bookData['price']?>円</td>
            		</tr>
        		<?php
        		}?>
    		</table>
    		<br><br>
    		<hr>
        	<table>
        		<tr>
        			<th style="width: 10vw; background-color: lightblue;">合計</th>
        			<td><?=$total?>円</td>
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