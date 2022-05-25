<?php
//念のためint型に変換しておく
$errNum = (int)$_GET['errNum'];

//エラー判定
switch($errNum) {
    case 1:
        $errMsg = "ISBNが未入力の為、書籍登録処理は行えませんでした。";
        $linkedTo = 'list';
        break;
    case 2:
        $errMsg = "入力ISBNは既に登録済みの為、書籍登録処理は行えませんでした。";
        $linkedTo = 'list';
        break;
    case 3:
        $errMsg = "タイトルが未入力の為、書籍登録処理は行えませんでした。";
        $linkedTo = 'list';
        break;
    case 4:
        $errMsg = "価格が未入力の為、書籍登録処理は行えませんでした。";
        $linkedTo = 'list';
        break;
    case 5:
        $errMsg = "価格の値が不正の為、書籍登録処理は行えませんでした。";
        $linkedTo = 'list';
        break;
    case 6:
        $errMsg = "詳細対象の書籍が存在しない為、詳細情報処理は行えません。";
        $linkedTo = 'list';
        break;
    case 7:
        $errMsg = "タイトルが未入力の為、書籍更新処理は行えませんでした。";
        $linkedTo = 'list';
        break;
    case 8:
        $errMsg = "価格が未入力の為、書籍更新処理は行えませんでした。";
        $linkedTo = 'list';
        break;
    case 9:
        $errMsg = "価格の値が不正の為、書籍更新処理は行えませんでした。";
        $linkedTo = 'list';
        break;
    case 10:
        $errMsg = "更新対象の書籍は存在しない為、更新処理は行えませんでした。";
        $linkedTo = 'list';
        break;
    case 11:
        $errMsg = "削除対象の書籍は存在しない為、削除処理は行えませんでした。";
        $linkedTo = 'list';
        break;
    case 12:
        $errMsg = '入力されたユーザー名とパスワードが間違っています。';
        $linkedTo = 'login';
        break;
    case 13:
        $errMsg = 'セッション切れの為、カートに追加処理は行えませんでした。';
        $linkedTo = 'logout';
        break;
    case 14:
        $errMsg = '該当書籍がDBに存在しない為、カートに追加処理は行えませんでした。';
        $linkedTo = 'menu';
        break;
    case 15:
        $errMsg = 'セッション切れの為、カート状況確認処理は行えませんでした。';
        $linkedTo = 'logout';
        break;
    case 16:
        $errMsg = 'セッション切れの為、購入処理は行えませんでした。';
        $linkedTo = 'logout';
        break;
    case 17:
        $errMsg = 'カートに書籍が入っていません。購入処理は行えませんでした。';
        $linkedTo = 'menu';
        break;
    case 18:
        $errMsg = '既にDBに書籍データが存在するため、初期登録は行えませんでした。';
        $linkedTo = 'menu';
        break;
    case 19:
        $errMsg = '初期データファイルが無い為、登録は行えません。';
        $linkedTo = 'menu';
}
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>Error!</title>
	</head>
    <body>
    <header>
	    <h2 align="center">書籍販売システムWeb版 Ver.2.0</h2>
    	<hr style="border: 2px solid blue;">
    </header>
	<center>
    	<br><br>
    	<h2>●●エラー●●</h2>
		<p><?=$errMsg?></p>
		<br><br>
		<?php
		if($linkedTo == 'list') {?>
				<a href="./list.php">[一覧表示へ戻る]</a>
		<?php
		} elseif($linkedTo == 'menu') {?>
			<a href="./menu.php">[メニューへ戻る]</a>
		<?php
		} elseif($linkedTo == 'logout') {?>
			<a href="./logout.php">[ログアウトする]</a>
		<?php
		} elseif($linkedTo == 'login') {?>
			<a href="./login.php">[ログイン画面へ戻る]</a>
		<?php
		}?>
	</center>
    <footer>
    	<br><br><br>
    	<hr style="border: 1px solid blue;">
    	<p>Copyright (C) 20YY All Rights Reserved.</p>
    </footer>
    </body>
</html>