<?php

	session_start();
	$db['host']="localhost";
	$db['dbname']="zaiko2021_yse";
	$db['username']= "zaiko2021_yse";
	$db['pass']= "2021zaiko";
	$saleDate=array(197,198,199,200,201,202);
	$price=array(400,500,600,700,800,900,1000,2000);
	$stock= array(10,20,30,40,50);

if ($_SESSION['login']==False){

	$_SESSION['error2'] ="ログインしてください";
	header("Location: login.php");//④ログイン画面へ遷移する。
}

 $hostname="localhost";
  $pdo = new PDO("mysql:host=$hostname;dbname=zaiko2021_yse;charset=utf8;","zaiko2021", "2021zaiko" );
	$dtb_mae=$dtb="SELECT * FROM books ";
	$and ="";

	// -----------------検索機能でデータベースに接続する文を作成 ーーーーーーーーーーーーーーーーーーーーーーーーーーーーー
if(!@$_POST['search']){
	// echo "chua bam search"; //debug
}else{
	// echo "da bam nut search<br>";
	if(@$_POST['nendai'] || @$_POST['endai']||@$_POST['zaiko']||@$_POST['key']){ $dtb = $dtb." where ";}

	if(@$_POST['key']){  $dtb = $dtb."(title like '%".$_POST['key']."%' OR author like '%".$_POST['key']."%') "	;$and=" AND " 	;}

	if(@$_POST['nendai']){  $dtb = $dtb.$and."salesDate like '".$_POST['nendai']."%'"	;$and=" AND " 	;}

	if(@$_POST['endai']){if($_POST['endai']>=1000){$dtb=$dtb.$and."price>= ".$_POST['endai']." AND price<".($_POST['endai']+1000);}else{ $dtb=$dtb.$and."price>= ".$_POST['endai']." AND price<".($_POST['endai']+100)	;$and=" AND " 	;}}

	if(@$_POST['zaiko']){if($_POST['zaiko']>50){$dtb=$dtb.$and."stock> ".$_POST['zaiko']	;}else{$dtb=$dtb.$and."stock< ".$_POST['zaiko']	;}}
// echo $dtb;   //debug 用
$dtb_mae=$dtb;
}

// ーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーソート機能でデータベースに接続する文を作成ーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーー
$count['id']=$count['book_name']=$count['author']=$count['date']=$count['price']=$count['stock']=0;
$ten="";
$sankaku['date']=$sankaku['price']=$sankaku['stock']=$sankaku['id']=$sankaku['book_name']=$sankaku['author']="▼";

if(!@$_POST['sort']){
	// echo "chua bam sort va dtb la:".$dtb;　//debug
}else{  //sort dc bam
	$dtb_mae=$dtb=$_POST['dtb'];
	$dtb=$dtb." ORDER BY ";
	$ten=" , ";

if($_POST['sort']=="id"){
		// echo "date button selected<br>";
		$count['id']=$_POST['count_id'];
		$count['id']++;
		echo "da bam sort id<br>";
		if ($count['id']%2==0) {
		 	$dtb=$dtb."id ASC";
		}else{
	 		$dtb=$dtb."id DESC";
	 		$sankaku['id']="▲";
	 	}
	}else if($_POST['sort']=="book_name"){
		// echo "date button selected<br>";
		$count['book_name']=$_POST['count_book_name'];
		$count['book_name']++;
		if ($count['book_name']%2==0) {
		 	$dtb=$dtb."title ASC";
		}else{
	 		$dtb=$dtb."title DESC";
	 		$sankaku['book_name']="▲";
	 	}
	}else if($_POST['sort']=="author"){
		// echo "date button selected<br>";
		$count['author']=$_POST['count_author'];
		$count['author']++;
		if ($count['author']%2==0) {
		 	$dtb=$dtb."author ASC";
		}else{
	 		$dtb=$dtb."author DESC";
	 		$sankaku['author']="▲";
	 	}
	}else if($_POST['sort']=="date"){
		// echo "date button selected<br>";
		$count['date']=$_POST['count_date'];
		$count['date']++;
		if ($count['date']%2==0) {
		 	$dtb=$dtb."salesDate ASC";
		}else{
	 		$dtb=$dtb."salesDate DESC";
	 		$sankaku['date']="▲";
	 	}
	}else if($_POST['sort']=="price"){
		$count['price']=$_POST['count_price'];
		$count['price']++;
		if ($count['price']%2==0) {
		 	$dtb=$dtb."price ASC";
		}else{
	 		$dtb=$dtb."price DESC";
	 		$sankaku['price']="▲";
	 	}
	}else if($_POST['sort']=="stock"){
		$count['stock']=$_POST['count_stock'];
		$count['stock']++;
		if ($count['stock']%2==0) {
		 	$dtb=$dtb."stock ASC";
		}else{
	 		$dtb=$dtb."stock DESC";
	 		$sankaku['stock']="▲";
		}
	}
	echo "da bam sort va dtb =".$dtb; //debug 用
}

	$st = $pdo->query($dtb);

    
?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<title>書籍一覧</title>
	<link rel="stylesheet" href="css/ichiran.css" type="text/css" />
		<!-- <link rel="stylesheet" href="css/ninsyou.css" type="text/css" /> -->

</head>
<body>
	<div id="header">
		<h1>書籍一覧</h1>
	</div>
	<form action="zaiko_ichiran.php" method="post" id="myform" name="myform">
					<!-- 左メニュー -->

			
		<div id="pagebody">

			<div id="menu"><form>
			<nav>
			<ul>
				<li>
					<table style ="width:800px;">
					<thead>
						<tr>
							<th id="" >キーワード</th>
							<th id="saleDate">発売年代</th>
							<th id="price"　>金額</th>
							<th id="stock"　>在庫</th>
						
						</tr>

					</thead>
					<tbody>
						<tr>
							<td id="" ><input type="text" name="key"></td>
							<td id="" ><select name="nendai"><?php echo "<option></option>"; foreach($saleDate as $date) {echo "<option value=".$date.">".$date."0年代</option>"; }?></select></td>
							<td id="" ><select name="endai"><?php echo "<option></option>"; foreach($price as $price) {echo "<option value=".$price.">".$price."円代</option>"; }?></select></td>
							<td id="" ><select name="zaiko"><?php echo "<option></option>"; foreach($stock as $stock) {echo "<option value=".$stock.">".$stock."冊未満</option>"; }echo "<option value=".($stock+1).">".$stock."冊以上</option>";　?></select></td>
						</tr>
					</tbody>
				</table>
				</li>
				<li><div id="right"><button type="submit" id="kensaku" formmethod="POST" name="search" value="7" >検索</button></div> </li>
			</ul>
		</nav>
	</form>

	</div>

				<div id="error">
				<?php
					//⑨SESSIONの「success」の中身を表示する。
				echo @$_SESSION["success"];
				?>
			</div>
	
			<!-- 中央表示 -->
			<div id="center" >
<!-- style="float:right;" -->
				 <!-- 書籍一覧の表示 -->
				 <div id="left">
				<p id="ninsyou_ippan">
					<?php
						echo @$_SESSION["account_name"];
						echo @$_SESSION["NAME"];
					?><br>
					<button type="button" id="logout" onclick="location.href='logout.php'">ログアウト</button>
				</p>
				<button type="submit" id="btn1" formmethod="POST" name="decision" value="3" formaction="nyuka.php">入荷</button>

				<button type="submit" id="btn1" formmethod="POST" name="decision" value="4" formaction="syukka.php">出荷</button>

				<button type="submit" id="btn1" formmethod="POST" name="decision" value="5" formaction="new_product.php">新商品追加</button>
				<button type="submit" id="btn1" formmethod="POST" name="decision" value="6" formaction="delete_product.php">商品削除</button>
			</div>
				<table>
					<thead>
						<tr>
							<th id="check"></th>
							<th id="id">ID<button type="submit" formmethod="POST" name ="sort" value="id"><?php echo $sankaku["id"]; ?></button></th>
							<th id="book_name">書籍名<button type="submit" formmethod="POST" name ="sort" value="book_name"><?php echo $sankaku["book_name"]; ?></button></th>
							<th id="author">著者名<button type="submit" formmethod="POST" name ="sort" value="author"><?php echo $sankaku["author"]; ?></button></th>
							<th width ="100px" id="salesDate">発売日<button type="submit" formmethod="POST" name ="sort" value="date"><?php echo $sankaku["date"]; ?></button></th>
							<th width ="80px" id="itemPrice">金額<button type="submit" formmethod="POST" name ="sort" value="price"><?php echo $sankaku["price"]; ?></button></th>
							<th width ="120px" id="stock">在庫数<button type="submit" formmethod="POST" name ="sort" value="stock"><?php echo $sankaku["stock"]; ?></button></th>
							<input type="hidden" name="dtb" value="<?php echo $dtb_mae;?>">
							<input type="hidden" name="count_id" value="<?php echo $count['id'];?>">
							<input type="hidden" name="count_book_name" value="<?php echo $count['book_name'];?>">
							<input type="hidden" name="count_author" value="<?php echo $count['author'];?>">
							<input type="hidden" name="count_date" value="<?php echo $count['date'];?>">
							<input type="hidden" name="count_price" value="<?php echo $count['price'];?>">
							<input type="hidden" name="count_stock" value="<?php echo $count['stock'];?>">
						</tr>
					</thead>
					<tbody>
						<?php
						//⑩SQLの実行結果の変数から1レコードのデータを取り出す。レコードがない場合はループを終了する。
						while($extract=$st->fetch()){
							if($extract['deleflag']==1){continue;}
							echo "<tr id='book'>";
							echo "<td id='check'><input type='checkbox' name='books[]'value=".$extract['id']."></td>";
							echo "<td id='id'>".$extract['id']."</td>";
							echo "<td id='title'>".$extract['title']."</td>";
							echo "<td id='author'>".$extract['author']."</td>";
							echo "<td id='date'>".$extract['salesDate']."</td>";
							echo "<td id='price'>".$extract['price']."</td>";
							echo "<td id='stock'>".$extract['stock']."</td>";

							echo "</tr>";
						}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</form>
	<div id="footer">
		<footer>株式会社アクロイト</footer>
	</div>
</body>
</html>