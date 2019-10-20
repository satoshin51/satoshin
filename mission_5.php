<html>
	<head>
		<title>	ミッション５(完成形)	</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	</head>
	<body>
		<?php
			//phpのプログラムを書くところ↓
			//データベース接続
			$dsn='mysql:dbname=***;host=localhost';
			$user='***';
			$password='***';
			$pdo=new PDO($dsn,$user,$password,array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_WARNING));
			//テーブル作成(テーブル名:mission5)
			$sql="CREATE TABLE IF NOT EXISTS mission5"
			."("
			."id INT AUTO_INCREMENT PRIMARY KEY,"
			."name char(32),"
			."comment TEXT,"
			."date TEXT,"
			."pass TEXT"
			.");";
			$stmt=$pdo->query($sql);

			//削除機能

			if(isset($_POST["send_2"])){ //削除ボタンを押したら
				if(!empty($_POST["delete"]) && !empty($_POST["password_2"])){
					//各行ごと配列に取り出す
					$id=$_POST["delete"];
					$sql='SELECT * FROM mission5';
					$stmt=$pdo->query($sql);
					$results=$stmt->fetchAll();
					//削除対象の行を探索
					foreach($results as $word){
						if($word['id']==$id){//削除番号が一致したら
							if ($word['pass']==$_POST["password_2"]){//パスワード確認
								$sql='delete from mission5 where id=:id';
								$stmt=$pdo->prepare($sql);
								$stmt->bindParam(':id', $id, PDO::PARAM_INT);
								$stmt->execute();

							}else if($word['pass']!=$_POST["password_2"]){
								echo "パスワードが違います";
							}
						}
					}
				}else if(empty($_POST["delete"])){
					echo "番号を入力してください";
				}else if(empty($_POST["password_2"])){
					echo "パスワードを入力してください";
				}
			}

			//編集機能

 			if(isset($_POST["send_3"])){//編集ボタンが押されたら
 				if(!empty($_POST["hensyu"]) && !empty($_POST["password_3"])){
					//各行ごと配列に格納
 					$id=$_POST["hensyu"];//変更する番号
 					$sql='SELECT * FROM mission5';
					$stmt=$pdo->query($sql);
					$results=$stmt->fetchAll();
					//編集対象の行を探索
					foreach($results as $word_1){
						if($word_1['id']==$id){
							if ($word_1['pass']==$_POST["password_3"]){//パスワード確認
								foreach($results as $word_2){
									if($word_2['id']==$id){//編集番号が一致したら
										$get_num=$word_2['id'];//編集したい番号を取り出す
										$get_name=$word_2['name'];//編集したい名前を取り出す
										$get_comment=$word_2['comment'];//編集したいコメントを取り出す
									}
								}
								$stmt->execute();
							}else if($word_1['pass']!=$_POST["password_3"]){
								echo "パスワードが違います";
							}
						}
					}
				}else if(empty($_POST["hensyu"])){
					echo "番号を入力してください";
				}else if(empty($_POST["password_3"])){
					echo "パスワードを入力してください";
				}
			}

			//投稿機能
			if(isset($_POST["send_1"])){ //投稿ボタンを押したら
				if(!empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["password_1"])){
					if(empty($_POST["hensyu_num"])){//新規投稿モード
						$sql=$pdo->prepare("INSERT INTO mission5 (name,comment,date,pass) VALUES (:name, :comment, :date, :pass)");
						$sql->bindParam(':name', $name, PDO::PARAM_STR);
						$sql->bindParam(':comment', $comment, PDO::PARAM_STR);
						$sql->bindParam(':date', $date, PDO::PARAM_STR);
						$sql->bindParam(':pass', $pass, PDO::PARAM_STR);

						$name=$_POST["name"];//名前
						$comment=$_POST["comment"];//コメント
						$date=date("Y/m/d G:i:s");//投稿時間
						$pass=$_POST["password_1"];//パスワード

						$sql->execute();
					}else if(!empty($_POST["hensyu_num"])){//編集モード

						$id=$_POST["hensyu_num"];
						$name=$_POST["name"];
						$comment=$_POST["comment"];
						$date=date("Y/m/d G:i:s");//投稿時間
						$pass=$_POST["password_1"];//パスワード
						$sql='update mission5 set name=:name,comment=:comment, date=:date, pass=:pass where id=:id';
						$stmt=$pdo->prepare($sql);
						$stmt->bindParam(':name', $name, PDO::PARAM_STR);
						$stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
						$stmt->bindParam(':date', $date, PDO::PARAM_STR);
						$stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
						$stmt->bindParam(':id', $id, PDO::PARAM_INT);
						$stmt->execute();
					}
				}else if(empty($_POST["name"])){
					echo "名前を入力してください";
				}else if(empty($_POST["comment"])){
				  echo "コメントを入力してください";
				}else if(empty($_POST["password_1"])){
				echo "パスワードを入力してください";
				}
			}

		?>

		<form action="<?php print($_SERVER['PHP_SELF']) ?>" method="post">
			<input type="hidden" name="hensyu_num"
							value="<?php
										if(isset($_POST['send_3']) && !empty($get_num)){
											print $get_num;
										}
										?>"
							>
			<br>
			<input type="text" name="name"
						value="<?php
									if(isset($_POST['send_3']) && !empty($get_name)){
										print $get_name;
                     				}
                     				?>"
            			placeholder="名前"><br>
			<input type="text" name="comment"
							value="<?php
										if(isset($_POST['send_3']) && !empty($get_comment)){
                                                print $get_comment;
                                    	}
                                     	?>"
							placeholder="コメント"><br>
			<input type="text" name="password_1" placeholder="パスワード">
			<input type="submit" name="send_1" value="送信">
			<br><br>
			<input type="text" name="delete" placeholder="削除番号"><br>
			<input type="text" name="password_2" placeholder="パスワード">
			<input type="submit" name="send_2" value="削除">
			<br><br>
			<input type="text" name="hensyu" placeholder="編集番号"><br>
			<input type="text" name="password_3" placeholder="パスワード">
			<input type="submit" name="send_3" value="編集">

		</form>

		<?php
			//ブラウザ表示
			if(isset($_POST["send_1"])||isset($_POST["send_2"])||isset($_POST["send_3"])){
				//データベース接続
				$dsn='mysql:dbname=***;host=localhost';
				$user='***';
				$password='***';
				$pdo=new PDO($dsn,$user,$password,array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_WARNING));

				$sql='SELECT * FROM mission5';
				$stmt=$pdo->query($sql);
				$results=$stmt->fetchAll();
				foreach ($results as $row){
					//$rowの中にはテーブルのカラム名が入る
					echo $row['id'].',';
					echo $row['name'].',';
					echo $row['comment'].',';
					echo $row['date'].'<br>';
					echo "<hr>";
				}
			}
		?>

	</body>
</html>
