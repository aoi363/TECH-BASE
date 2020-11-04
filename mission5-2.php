<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>mission5-1</title>
</head>
<body>

<?php
///データベースへの接続
    $dsn = 'データベース名';
    $user = 'ユーザー名';
    $password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

//CREATE文：データベース内にテーブルを作成
    $sql = "CREATE TABLE IF NOT EXISTS mission5"
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"
	. "name char(32),"
	. "comment TEXT,"
	. "datetime timestamp"
	.");";
	$stmt = $pdo->query($sql);

    
//名前とコメント
    if(!empty($_POST["name"])&&(!empty($_POST["comment"]))&&(!empty($_POST["pass1"]))){//名前、コメント、パスワードが空の時は動作しない
        $pass=$_POST["pass1"];//
        
        if($pass=="pass"){//パスワードが一致した場合
             //投稿フォームのデータを受け取る
                $name = $_POST["name"];
                $comment = $_POST["comment"];
                $datetime = date("Y/m/d H:i:s");//日付データを取得 
            
        //編集
            if(!empty($_POST["editnum"])){//編集番号が表示されているとき
                                        

            //UPDATE文：入力されているデータレコードの内容を編集
                $id = $_POST["editnum"]; //変更する投稿番号
	            $sql = 'UPDATE mission5 SET name=:name,comment=:comment,datetime=:datetime WHERE id=:id';
            	$stmt = $pdo->prepare($sql);
	            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
	            $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
	            $stmt->bindParam(':datetime', $datetime, PDO::PARAM_STR);
	            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
	            $stmt->execute();
	            
            }else{
                
        //新規投稿
             //INSERT文：データを入力（データレコードの挿入）
                $sql = $pdo -> prepare("INSERT INTO mission5 (name, comment, datetime) VALUES (:name, :comment, :datetime)");
	            $sql -> bindParam(':name', $name, PDO::PARAM_STR);
         	    $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
        	    $sql -> bindParam(':datetime', $datetime, PDO::PARAM_STR);
	            $sql -> execute();
            
            }
        }
    }



//削除機能（指定された番号以外を書き直す）
    if(!empty($_POST["delete"])&&(!empty($_POST["pass2"]))){//フォームが空の時は動作しない
        $pass=$_POST["pass2"];
        
        if($pass=="pass"){//パスワードが一致した場合
        
        //DELETE文：入力したデータレコードを削除
            $id = $_POST["delete"];
	        $sql = 'delete from mission5 where id=:id';
	        $stmt = $pdo->prepare($sql);
	        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
	        $stmt->execute();
        
        }
    }
    

//編集機能(編集フォームから投稿フォームへ飛ばす）
    if(!empty($_POST["edit"])&&(!empty($_POST["pass3"]))){//ファイルが空の時は動作しない
        $pass=$_POST["pass3"];
        
        if($pass=="pass"){//パスワードが一致した場合
            $edit=$_POST["edit"];
            
        }
    }


?>

    [　入力フォーム　]<br>
    <form action = "" method = "post">
    <!--名前-->
    <input type= "text" name="name" placeholder ="名前" 
        value="<?php if(isset($edit)){
            $id = $edit;
            $sql = 'SELECT * FROM mission5 WHERE id=:id ';
            $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
            $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
            $stmt->execute();                             // ←SQLを実行する。
            $results = $stmt->fetchAll(); 
            	foreach ($results as $row){
            		//$rowの中にはテーブルのカラム名が入る
            		echo $row['name'];
            	}       
        }?>"><br>
    
    <!--コメント-->
    <input type= "text" name = "comment" placeholder ="コメント" 
        value="<?php if(isset($edit)){
            $id = $edit;
            $sql = 'SELECT * FROM mission5 WHERE id=:id ';
            $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
            $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
            $stmt->execute();                             // ←SQLを実行する。
            $results = $stmt->fetchAll(); 
            	foreach ($results as $row){
            		//$rowの中にはテーブルのカラム名が入る
            		echo $row['comment'];
            	}
        }?>">
        
    <!--編集用のテキストボックス（非表示）-->
        <input type= "hidden" name="editnum" 
        value="<?php if(isset($edit)){echo $edit;}?>"><br>
        <input type= "password" name="pass1" placeholder="パスワード">
        <input type= "submit" name = "submit">
    </form><br>
    
    [　削除フォーム　]<br>
    <form action = "" method ="post">
        <input type= "text" name="delete"><br>
        <input type= "password" name= "pass2" placeholder="パスワード">
        <input type= "submit" value="削除">
    </form><br>

    [　編集番号指定用フォーム　]<br>
    <form action = "" method="post">
        <input type= "text" name="edit"><br>
        <input type= "password" name= "pass3" placeholder="パスワード">
        <input type= "submit" value="編集"><br><br><br>
    </form>
    
<?php
//テキストファイルの内容をブラウザに表示させる
echo"---------------------------------------<br>";
echo" 【　投稿一覧　】<br><br><br>";


//SELECT文：入力したデータレコードを抽出し、表示する
$sql = 'SELECT * FROM mission5';
$stmt = $pdo->query($sql);
$results = $stmt->fetchAll(); 
	foreach ($results as $row){
		//$rowの中にはテーブルのカラム名が入る
		echo $row['id'].',';
		echo $row['name'].',';
		echo $row['comment'].',';
		echo $row['datetime'].'<br>';
	    echo "<hr>";
	}
?>

</body>
</html>