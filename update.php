<?php
/* ルーティング処理 */
if(isset($_GET['updateIsbn']) || isset($_POST['updateButton'])) {    //list.phpやdetail.phpからの遷移もしくは自分自身からの遷移の場合実行
    //一連のDB処理操作をまとめた関数のインポート
    require_once 'dbprocess.php';

    //遷移元によって設定する変数を変更
    if(isset($_GET['updateIsbn'])) {     //list.phpもしくはdetail.phpからの遷移の場合に実行
        //GET送信で送られてきた更新対象の書籍のisbnを変数に格納
        $isbn = $_GET['updateIsbn'];
    } elseif(isset($_POST['updateButton'])) {   //自分自身からの遷移の場合に実行
        //POSTで受け取った更新対象の各データをそれぞれ変数に格納
        $isbn = $_POST['isbn'];
        $newTitle = $_POST['newTitle'];
        $newPrice = (int)$_POST['newPrice'];

        //POSTで受け取った更新前の各データをそれぞれ変数に格納
        $oldTitle = $_POST['oldTitle'];
        $oldPrice = $_POST['oldPrice'];

        /* エラー判定１ */
        if($newTitle == "") {
            header('Location: ./error.php?errNum=7');
            exit;
        }
        if($newPrice == "") {
            header('Location: ./error.php?errNum=8');
            exit;
        }
        if(!is_numeric($newPrice)) {
            header('Location: ./error.php?errNum=9');
            exit;
        }
    }

    /* エラー判定２ */
    //検索用のクエリの設定
    $selectSql = "SELECT * FROM bookinfo WHERE isbn = '{$isbn}'";
    //検索用クエリの発行
    $selectResult = executeQuery($selectSql);

    if(!$selectResult) {    //更新対象のデータが存在しなかった場合の処理
        header('Location: ./error.php?errNum=10');
        exit;
    } else {               //更新対象のデータが見つかった場合の処理
        //更新対象のレコードを配列として取得
        $record = mysqli_fetch_array($selectResult);
        //検索結果セットの開放
        mysqli_free_result($selectResult);

        //自分自身からの遷移の場合に実行
        if(isset($_POST['updateButton'])) {
            //更新用クエリの設定
            $updateSql = "UPDATE bookinfo SET title = '{$newTitle}', price = {$newPrice} WHERE isbn = '{$isbn}'";
            //更新用クエリの発行
            executeQuery($updateSql);
        }
    }
} else {                             //上記以外からの遷移の場合に実行
    //list.phpにリダイレクト
    header('Location: ./list.php');
    exit;
}
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>書籍更新画面</title>
	</head>
    <body>
	<header>
    	<h2 align="center">書籍販売システムWeb版 Ver.2.0</h2>
    	<hr style="border: 2px solid blue;">
    	<div class="nav" style="position: absolute; top: 83px; left: 20px;">
    		<a href="./menu.php" style="margin-right: 20px;">[メニュー]</a>
    		<a href="./insert.php" style="margin-right: 20px;">[書籍登録]</a>
    		<a href="./list.php">[書籍一覧]</a>
    	</div>
    	<h3 align="center">書籍変更</h3>
    	<hr style="border: 1px solid black;">
    </header>
    <main>
    	<br><br>
    	<?php
    	//初期画面の表示
    	if(!isset($_POST['updateButton'])) {?>
    	<center>
        	<form action="./update.php" method="post">
            	<table>
            		<tr>
            			<th style="background-color: lightblue;"></th>
            			<th style="background-color: lightblue;">&lt;&lt;変更前情報&gt;&gt;</th>
            			<th style="background-color: lightblue;">&lt;&lt;変更後情報&gt;&gt;</th>
            		</tr>
            		<tr>
            			<th style="background-color: lightblue;">ISBN</th>
            			<td style="background-color: aqua;"><?=$record['isbn']?></td>
            			<td><?=$record['isbn']?></td>
            		</tr>
            		<tr>
            			<th style="background-color: lightblue;">TITLE</th>
            			<td style="background-color: aqua;"><?=$record['title']?></td>
            			<td><input type="text" name="newTitle"></td>
            		</tr>
            		<tr>
            			<th style="background-color: lightblue;">価格</th>
            			<td style="background-color: aqua;"><?=$record['price']?>円</td>
            			<td><input type="text" name="newPrice">円</td>
            		</tr>
            	</table>
            	<br><br><br><br>
            	<input type="hidden" name="isbn" value="<?=$record['isbn']?>">
            	<input type="hidden" name="oldTitle" value="<?=$record['title']?>">
            	<input type="hidden" name="oldPrice" value="<?=$record['price']?>">
            	<input type="submit" name="updateButton" value="変更完了">
        	</form>
    	</center>
    	<?php
    	//自分自身からの遷移の場合の表示
    	} else {?>
    	<center>
        	<table>
        		<tr>
        			<th style="background-color: lightblue;"></th>
        			<th style="background-color: lightblue;">&lt;&lt;変更前情報&gt;&gt;</th>
        			<th style="background-color: lightblue;">&lt;&lt;変更後情報&gt;&gt;</th>
        		</tr>
        		<tr>
        			<th style="background-color: lightblue;">ISBN</th>
        			<td style="background-color: aqua;"><?=$isbn?></td>
        			<td><?=$isbn?></td>
        		</tr>
        		<tr>
        			<th style="background-color: lightblue;">TITLE</th>
        			<td style="background-color: aqua;"><?=$oldTitle?></td>
        			<td><?=$newTitle?></td>
        		</tr>
        		<tr>
        			<th style="background-color: lightblue;">価格</th>
        			<td style="background-color: aqua;"><?=$oldPrice?>円</td>
        			<td><?=$newPrice?>円</td>
        		</tr>
        	</table>
        	<br><br>
        	<p>上記内容でデータを更新しました。</p>
        	<br><br>
        	<a href="./list.php">書籍一覧へ戻る</a>
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