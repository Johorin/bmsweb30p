<?php
//セッションの利用を開始
session_start();

//セッション情報の破棄
unset($_SESSION['userInfo']);
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>ログアウト画面</title>
	</head>
    <body>
    <header>
    	<h2 align="center">書籍販売システムWeb版 Ver.2.0</h2>
    	<hr style="border: 2px solid blue;">
    	<h3 align="center">ログアウト画面</h3>
    	<hr style="border: 1px solid black;">
    </header>
    <main>
    	<br><br>
    	<h4>ログアウトしました。</h4>
    	<br>
    	<a href="./login.php">【ログイン画面へ戻る】</a>
	</main>
    <footer>
    	<br><br><br>
    	<hr style="border: 1px solid blue;">
    	<p>Copyright (C) 20YY All Rights Reserved.</p>
    </footer>
    </body>
</html>