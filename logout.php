<?php
/* 
【機能】
	セッション情報を削除しログイン画面に遷移する
*/

//①セッションを開始する。
session_start();
//②セッションを削除する。
unset($_SESSION["id"]);
unset($_SESSION["name"]);
//③ログイン画面へ遷移する。
header("Location:login.php")
?>