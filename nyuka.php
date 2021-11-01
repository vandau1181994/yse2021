<?php
/* 
【機能】
書籍の出荷数を指定する。確定ボタンを押すことで確認画面へ出荷個数を引き継いで遷移す
る。
【エラー一覧（エラー表示：発生条件）】
このフィールドを入力して下さい(吹き出し)：出荷個数が未入力
出荷する個数が在庫数を超えています：出荷したい個数が在庫数を超えている
数値以外が入力されています：入力された値に数字以外の文字が含まれている。
*/
/*
 * ①session_status()の結果が「PHP_SESSION_NONE」と一致するか判定する。
 * 一致した場合はif文の中に入る。
 */
if (session_status() == PHP_SESSION_NONE) {
	//②セッションを開始する
	session_start();
	$_SESSION["success"] = "";
}

//③SESSIONの「login」フラグがfalseか判定する。「login」フラグがfalseの場合はif文の中に入る。
if ($_SESSION["login"] == False) {
	//④SESSIONの「error2」に「ログインしてください」と設定する。
	$_SESSION['error2'] = "ログインしてください";
	header("Location: login.php"); //④ログイン画面へ遷移する。
	//⑤ログイン画面へ遷移する。
}

//⑥データベースへ接続し、接続情報を変数に保存する

//⑦データベースで使用する文字コードを「UTF8」にする

$pdo = new PDO("mysql:host=localhost;dbname=zaiko2021_yse;charset=utf8;", "zaiko2021_yse", "2021zaiko");
$st = $pdo->query("SELECT * FROM books ");
//⑧POSTの「books」の値が空か判定する。空の場合はif文の中に入る。
if (!@($_POST["books"])) {
	//⑨SESSIONの「success」に「出荷する商品が選択されていません」と設定する。
	//⑩在庫一覧画面へ遷移する。
	$_SESSION["success"] = "入荷する商品が選択されていません";
	header("Location: zaiko_ichiran.php");
	exit;
}

function getId($id, $con)
{
	/* 
	 * ⑪書籍を取得するSQLを作成する実行する。
	 * その際にWHERE句でメソッドの引数の$idに一致する書籍のみ取得する。
	 * SQLの実行結果を変数に保存する。
	 */
	// $id=$_POST["id"]);
	$pdo = new PDO("mysql:host=localhost;dbname=zaiko2021_yse;charset=utf8;", "zaiko2021_yse", "2021zaiko");
	$st = $con->query("SELECT * FROM books where id =$id");

	while ($row = $st->fetch()) {
		//⑫実行した結果から1レコード取得し、returnで値を返す。
		return $row;
	}
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
				if (@$_SESSION["error"]) {
					//⑭SESSIONの「error」の中身を表示する。
					echo $_SESSION["error"];
				}
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
					/*
				 * ⑮POSTの「books」から一つずつ値を取り出し、変数に保存する。
				 */
					$ids = $_POST["books"];

					foreach (/* ⑮の処理を書く */$ids as $id) {
						// ⑯「getId」関数を呼び出し、変数に戻り値を入れる。その際引数に⑮の処理で取得した値と⑥のDBの接続情報を渡す。
						$selectedBook = getId($id, $pdo);
					?>
						<input type="hidden" value="<?php echo	/* ⑰ ⑯の戻り値からidを取り出し、設定する */ $selectedBook["id"]; ?>" name="books[]">
						<tr>
							<td><?php echo	/* ⑱ ⑯の戻り値からidを取り出し、表示する */ $selectedBook["id"]; ?></td>
							<td><?php echo	/* ⑲ ⑯の戻り値からtitleを取り出し、表示する */ $selectedBook["title"]; ?></td>
							<td><?php echo	/* ⑳ ⑯の戻り値からauthorを取り出し、表示する */ $selectedBook["author"]; ?></td>
							<td><?php echo	/* ㉑ ⑯の戻り値からsalesDateを取り出し、表示する */ $selectedBook["salesDate"];; ?></td>
							<td><?php echo	/* ㉒ ⑯の戻り値からpriceを取り出し、表示する */ $selectedBook["price"]; ?></td>
							<td><?php echo	/* ㉓ ⑯の戻り値からstockを取り出し、表示する */ $selectedBook["stock"]; ?></td>
							<td><input type='text' name='stock[]' size='5' maxlength='11' required></td>
						</tr>
					<?php
					}
					?>
				</table>
				<button type="submit" id="kakutei" formmethod="POST" name="decision" value="1">確定</button>
			</div>
		</div>
	</form>
	<!-- フッター 　-->
	<div id="footer">
		<footer>株式会社アクロイト</footer>
	</div>
</body>

</html>