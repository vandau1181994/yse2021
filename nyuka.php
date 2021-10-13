<?php
/* 
【機能】
書籍の入荷数を指定する。確定ボタンを押すことで確認画面へ入荷個数を引き継いで遷移す
る。なお、在庫数は各書籍100冊を最大在庫数とする。

【エラー一覧（エラー表示：発生条件）】
このフィールドを入力して下さい(吹き出し)：入荷個数が未入力
最大在庫数を超える数は入力できません：現在の在庫数と入荷の個数を足した値が最大在庫数を超えている
数値以外が入力されています：入力された値に数字以外の文字が含まれている
*/

/*
 * ①session_status()の結果が「PHP_SESSION_NONE」と一致するか判定する。
 * 一致した場合はif文の中に入る。
 */
// if (/* ①.の処理を行う */) {
	if (session_status() == PHP_SESSION_NONE) {
		//②セッションを開始する
		session_start();
	}


//③SESSIONの「login」フラグがfalseか判定する。「login」フラグがfalseの場合はif文の中に入る。
// if (/* ③の処理を書く */){
// 	//④SESSIONの「error2」に「ログインしてください」と設定する。
// 	//⑤ログイン画面へ遷移する。
// }

//⑥データベースへ接続し、接続情報を変数に保存する
$dbname = "zaiko2021_yse";
$host = "localhost";
$charset = "UTF8";
$user =  "zaiko2021_yse";
$password = "2021zaiko";
$option = [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION];

//⑦データベースで使用する文字コードを「UTF8」にする
$dsn = "mysql:dbname={$dbname};host={$host};charset={$charset}";
try
{
	$pdo = new PDO($dsn,$user,$password,$option);
	// echo "SUCCESS";
}catch(PDOException $e)
{
	die($e->getMessage());
}



//⑧POSTの「books」の値が空か判定する。空の場合はif文の中に入る。

	//⑨SESSIONの「success」に「入荷する商品が選択されていません」と設定する。

	//⑩在庫一覧画面へ遷移する。
	

//var_dump($_POST);
function getId($id,$con){
	/* 
	 * ⑪書籍を取得するSQLを作成する実行する。
	 * その際にWHERE句でメソッドの引数の$idに一致する書籍のみ取得する。
	 * SQLの実行結果を変数に保存する。
	 */
	$id = htmlspecialchars($id);
	$sql = "SELECT * FROM books WHERE id = {$id} ";
	$statement = $con->query($sql);
	//⑫実行した結果から1レコード取得し、returnで値を返す。
	$items =  $statement->fetch(PDO::FETCH_ASSOC);
	return $items;
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>入荷</title>
	<link rel="stylesheet" href="css/ichiran.css" type="text/css" />
</head>
<body>
	<!-- ヘッダ -->
	<div id="header">
		<h1>入荷</h1>
	</div>

	<!-- メニュー -->
	<div id="menu">
		<nav>
			<ul>
				<li><a href="zaiko_ichiran.php?page=1">書籍一覧</a></li>
			</ul>
		</nav>
	</div>

	<form action="nyuka_kakunin.php" method="post">
		<div id="pagebody">
			<!-- エラーメッセージ -->
			<div id="error">
			<?php
			/*
			 * ⑬SESSIONの「error」にメッセージが設定されているかを判定する。
			 * 設定されていた場合はif文の中に入る。
			 */ 
			// if(/* ⑬の処理を書く */){
			// 	//⑭SESSIONの「error」の中身を表示する。
			// }
			?>
			</div>
			<div id="center">
				<table>
					<thead>
						<tr>
							<th id="id">ID</th>
							<th id="book_name">書籍名</th>
							<th id="author">著者名</th>
							<th id="salesDate">発売日</th>
							<th id="itemPrice">金額(円)</th>
							<th id="stock">在庫数</th>
							<th id="in">入荷数</th>
						</tr>
					</thead>
					<?php 
					// /*
					//  * ⑮POSTの「books」から一つずつ値を取り出し、変数に保存する。
					//  */
					$ids = $_POST["books"];
    				 foreach($ids as $id):
    				// ⑯「getId」関数を呼び出し、変数に戻り値を入れる。その際引数に⑮の処理で取得した値と⑥のDBの接続情報を渡す。	
					$selectedBook = getId($id,$pdo);
					
					?>
					<input type="hidden" value=" <?php echo	/* ⑰ ⑯の戻り値からidを取り出し、設定する */$selectedBook["id"];?>" name="books[]">
					<tr>
						<td><?php echo	/* ⑱ ⑯の戻り値からidを取り出し、表示する */ $selectedBook["id"];?></td>
						<td><?php echo	/* ⑲ ⑯の戻り値からtitleを取り出し、表示する */$selectedBook["title"];?></td>
						<td><?php echo	/* ⑳ ⑯の戻り値からauthorを取り出し、表示する */$selectedBook["author"];?></td>
						<td><?php echo	/* ㉑ ⑯の戻り値からsalesDateを取り出し、表示する */$selectedBook["salesDate"];?></td>
						<td><?php echo	/* ㉒ ⑯の戻り値からpriceを取り出し、表示する */$selectedBook["price"];?></td>
						<td><?php echo	/* ㉓ ⑯の戻り値からstockを取り出し、表示する */$selectedBook["stock"];?></td>
						<td><input type='text' name='stock[]' size='5' maxlength='11' required></td>
					</tr>
					<?php endforeach ?>
				</table>
				<button type="submit" id="kakutei" formmethod="POST" name="decision" value="1">確定</button>
			</div>
		</div>
	</form>
	<!-- フッター -->
	<div id="footer">
		<footer>株式会社アクロイト</footer>
	</div>
</body>
</html>