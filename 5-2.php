<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission5-2</title>
</head>

<?php 
    $dsn = 'データベース名';
    $user = 'ユーザー名';
    $password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
?>

<body>
    <!-- 3-1 1.formタグ、inputタグの「名前(name)」「コメント(comment)」送信(submit)ボタンを作成 -->
    <form action="" method="post">
        <!-- 3-4 6.value属性で取得したものをフォームに初期表示 -->
        <input type="text" name="name" placeholder="名前"><br>
        <input type="text" name="comment" placeholder="コメント"><br>
        <!-- 3-5 1.パスワードの入力欄を作成 -->
        <input type="text" name="pass" placeholder="パスワード">
        <input type="submit" name="submit">
    </form>
    
    <!-- 3-3 1.削除用のフォームを用意 -->
    <form action="" method="post">
        <p>削除はこちらから</p>
        <input type="text" name="par" placeholder="削除対象番号"><br>
        <!-- 3-5 3.パスワード欄を追加 -->
        <input type="text" name="dpass" placeholder="パスワード">
        <button type="submit" name="delete">削除</button>
    </form><br>
    
    <!-- 3-4 1.編集用のフォームを用意 -->
    <form action="" method="post">
        <p>編集はこちらから</p>
        <input type="text" name="editno" placeholder="編集対象番号"><br>
        <!-- 5-1 編集用のフォームを用意 -->
        <input type="text" name="ename" placeholder="名前">
        <input type="text" name="ecomment" placeholder="コメント">
        <!-- 3-5 3.パスワード欄を追加 -->
        <input type="text" name="epass" placeholder="パスワード">
        <button type="submit" name="edit">編集</button>
    </form><br>
    
    <?php
        //3-1 2.POST送信の各値の変数を用意
        $comment = $_POST["comment"];
        $name = $_POST["name"];
        
        //3-1 2.投稿日時の変数を用意
        $date = date("Y年m月d日 H時i分s秒");
        
        //3-3 2.削除番号をPOST
        $par = $_POST["par"];
        
        //3-4 2.編集番号をPOST
        $editno = $_POST["editno"];
        $ename = $_POST["ename"];
        $ecomment = $_POST["ecomment"];
        
        //3-5 4.①パスワードをPOST
        $pass = $_POST["pass"];
        $dpass = $_POST["dpass"];
        $epass = $_POST["epass"]; 
        
        //3-3 2.削除ボタンが押された時
        if(isset($_POST["delete"])) {
            
            $sql = 'SELECT * FROM tb5 where id = :id';
	        $stmt = $pdo -> prepare($sql);
        	$stmt -> bindParam(':id', $par, PDO::PARAM_INT);
        	$stmt -> execute();
	        $results = $stmt -> fetchAll();
	        $record = $results[0];
	        
            //3-5 4.③投稿されたパスワードと入力されたものが一致した時のみ処理
            if($dpass == $record["pass"]) {

                echo "削除を受け付けました<br><br>";
                
                    $id = $par;
                	$sql = 'delete from tb5 where id=:id';
                	$stmt = $pdo -> prepare($sql);
                	$stmt -> bindParam(':id', $id, PDO::PARAM_INT);
                	$stmt -> execute();
	
                
            } else {
                
                echo "パスワードが正しくありません<br>";
                
            }    
                
        //3-4 2.編集ボタンが押された時    
        } elseif(isset($_POST["edit"])) {
            
            echo "編集を受け付けました<br><br>";
            
            $sql = 'SELECT * FROM tb5 where id = :id';
	        $stmt = $pdo -> prepare($sql);
        	$stmt -> bindParam(':id', $editno, PDO::PARAM_INT);
        	$stmt -> execute();
	        $results = $stmt -> fetchAll();
	        $record = $results[0];
	        
            //3-5 4.③投稿されたパスワードと入力されたものが一致した時のみ処理
            if($epass == $record["pass"]) {

                $id = $editno; //変更する投稿番号
            	$name = $ename;
            	$comment = $ecomment; //変更したい名前、変更したいコメントは自分で決めること
            	//WHEREで指定した部分の情報を更新
            	$sql = 'UPDATE tb5 SET name=:name,comment=:comment WHERE id=:id';
            	$stmt = $pdo->prepare($sql);
            	$stmt->bindParam(':name', $name, PDO::PARAM_STR);
            	$stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
            	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
            	$stmt->execute();
                
            } else {
                echo "パスワードが正しくありません<br>";
            }   
            
        //3-3 2.送信ボタンが押された時    
        } elseif(isset($_POST["submit"])) {
            
            if(empty($pass)) {
                echo "パスワードを入力してください<br>";        
            
            //3-1 4.追記保存
            } elseif(empty($comment) && empty($name)) {
                echo "名前とコメントを入力してください<br>";
               
            } elseif(empty($name)) {
                echo "名前を入力してください<br>";
                
            } elseif(empty($comment)) {
                echo "コメントを入力してください<br>";
                

            } else {
                $sql = $pdo -> prepare("INSERT INTO tb5 (name, comment, pass) VALUES (:name, :comment, :pass)");
                //:nameを$nameに代入、変数は文字列
            	$sql -> bindParam(':name', $name, PDO::PARAM_STR);
                //:commentを$commentに代入、変数は文字列
            	$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
            	$sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
            
            	$name = $_POST["name"];
            	$comment = $_POST["comment"];
            	$pass = $_POST["pass"];
            	//適用
            	$sql -> execute();

                echo "コメントを受け付けました<br>";

            }
            
        }
        
    ?>
    

    <?php
        
        $sql = 'SELECT * FROM tb5';
	    $stmt = $pdo -> query($sql);
	    $results = $stmt -> fetchAll();
	    foreach ($results as $row){
		//$rowの中にはテーブルのカラム名が入る
    		echo $row['id'].',';
    		echo $row['name'].',';
    		echo $row['comment'].'<br>';
    	    echo "<hr>";
	    }

    ?>
    
    
</body>
</html>