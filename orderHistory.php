<?php
//セッションの利用を開始
session_start();

//ログイン認証情報を取得する関数のインポート
require_once 'loginAuthentication.php';

//インポートした関数でログイン中のユーザー名と権限を取得
$authInfo = authenticate();

//権限が管理者からのアクセスの際にはエラー画面へリダイレクト
if($authInfo['authority'] === '管理者') {
    header('Location: ./error.php?errNum=20');
    exit;
}

require_once 'dbprocess.php';

$selectSql = "SELECT A.title,B.quantity,B.date FROM bookinfo A inner join orderinfo B on A.isbn=B.isbn WHERE B.user='{$authInfo['userName']}'";
$selectResult = executeQuery($selectSql);
$orderLists = array();

$key = 0;
while($orderList = mysqli_fetch_assoc($selectResult)) {
    $orderLists[$key]['title'] = $orderList['title'];
    $orderLists[$key]['quantity'] = $orderList['quantity'];
    $orderLists[$key]['date'] = str_replace('-', '／', $orderList['date']);

    $key++;
}
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>購入履歴画面</title>
	</head>
    <body>
    	<header>
        	<h2 align="center">書籍管理システム</h2>
        	<hr style="border: 2px solid blue;">
        	<div class="float-left" style="position: absolute; top: 83px; left: 20px;">
        		<a href="./menu.php" style="margin: 0 20px 0 0;">[メニュー]</a>
        	</div>
        	<h3 align="center">購入履歴</h3>
        	<div class="loginInfo" style="position: absolute; top: 55px; right: 60px;">
        		<p>名前：<?=$authInfo['userName']?></p>
        		<p>権限：<?=$authInfo['authority']?></p>
        	</div>
        	<hr style="border: 1px solid black;">
        </header>
        <main>
        	<center>
        		<br><br><br>
        		<table>
        			<tr>
        				<th style="background-color: grey; width: 20vw;">Title</th>
        				<th style="background-color: grey; width: 20vw;">数量</th>
        				<th style="background-color: grey; width: 20vw;">注文日</th>
        			</tr>
        			<?php
        			if(!empty($orderLists)) {
        			    foreach($orderLists as $order) {?>
        				<tr style="text-align: center;">
        					<td><?=$order['title']?></td>
        					<td><?=$order['quantity']?>冊</td>
        					<td><?=$order['date']?></td>
        				</tr>
        				<?php
        			    }
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