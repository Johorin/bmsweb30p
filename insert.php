<?php
//DB操作一式を搭載した関数を定義したファイルをインポート
require_once 'dbprocess.php';

//POST送信した登録対象の書籍データがあれば実行
if(isset($_POST['insertButton'])) {
    /* エラー判定 */
    //ISBNが未入力の場合
    if($_POST['isbn'] === "") {
        header('Location: ./error.php?errNum=1');
        exit;
    }
    //タイトルが未入力の場合
    if($_POST['title'] === "") {
        header('Location: ./error.php?errNum=3');
        exit;
    }
    //価格が未入力の場合
    if($_POST['price'] === "") {
        header('Location: ./error.php?errNum=4');
        var_dump($_POST['price']);
        exit;
    }

    //入力したISBNが既に登録済みの場合
    $errCheckSql = "SELECT isbn FROM bookinfo WHERE isbn = {$_POST['isbn']}";
    $errCheckResult = executeQuery($errCheckSql);
    if($errCheckResult) {   //検索のクエリが発行できた場合実行
        $record = mysqli_fetch_array($errCheckResult);

        //入力されたisbn値で１件でも検索がヒットしたらエラー（isbnが重複）
        if($record != NULL) {
            //検索結果セットの開放
            mysqli_free_result($errCheckResult);
            header('Location: ./error.php?errNum=2');
            exit;
        }
    }
    //価格の値が不正(数値以外)の場合
    if(!is_numeric($_POST['price'])) {
        header('Location: ./error.php?errNum=5');
        exit;
    }

    /* 入力値チェックが通った後の処理 */
    //POST送信で送られてきたデータをそれぞれ変数に格納
    $isbn = $_POST['isbn'];
    $title = $_POST['title'];
    $price = $_POST['price'];

    //データの登録用SQL文
    $insertSql = "INSERT INTO bookinfo VALUES('{$isbn}','{$title}',{$price})";

    //データの登録
    executeQuery($insertSql);
}
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>書籍登録画面</title>
	</head>
    <body>
    <header>
    	<h2 align="center">書籍販売システムWeb版 Ver.2.0</h2>
    	<hr style="border: 2px solid blue;">
    	<div class="float-left" style="position: absolute; top: 83px; left: 20px;">
    		<a href="./menu.php" style="margin: 0 20px 0 0;">[メニュー]</a>
    		<a href="./list.php">[書籍一覧]</a>
    	</div>
    	<h3 align="center">書籍登録</h3>
    	<hr style="border: 1px solid black;">
    </header>
    <br><br>
    	<?php
    	//初期画面
    	if(!isset($_POST['isbn'])) {?>
    		<center>
    			<form action="./insert.php" method="post">
    				<br><br>
    				<table>
    					<tr>
    						<td style="padding: 2px 20px 2px 5px; background-color: lightblue;">ISBN</td>
    						<td><input type="text" name="isbn"></td>
    					</tr>
    					<tr>
    						<td style="padding: 2px 20px 2px 5px; background-color: lightblue;">TITLE</td>
    						<td><input type="text" name="title"></td>
    					</tr>
    					<tr>
    						<td style="padding: 2px 20px 2px 5px; background-color: lightblue;">価格</td>
    						<td><input type="text" name="price"></td>
    					</tr>
    				</table>
    				<br><br>
    				<input type="submit" name="insertButton" value="登録">
    			</form>
    		</center>
		<?php
		//書籍登録ボタンからの遷移
    	} else {?>
    		<center>
    			<br><br>
    			<table>
    				<tr>
    					<td style="padding: 2px 20px 2px 5px; background-color: lightblue;">ISBN</td>
    					<td><?=$isbn?></td>
    				</tr>
    				<tr>
    					<td style="padding: 2px 20px 2px 5px; background-color: lightblue;">TITLE</td>
    					<td><?=$title?></td>
    				</tr>
    				<tr>
    					<td style="padding: 2px 20px 2px 5px; background-color: lightblue;">価格</td>
    					<td><?=$price?></td>
    				</tr>
    			</table>
    			<br>
    			<p>上記データを登録しました。</p>
    			<br><br>
    			<a href="./list.php">書籍一覧へ戻る</a>
    			<a href="./insert.php">続けて登録する</a>
    		</center>
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