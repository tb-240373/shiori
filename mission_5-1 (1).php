<!DOCTYPE html>
<html lang = "ja">
    <head>
        <meta charset = "UTF-8">
        <title>mission_5-1</title>
    </head>
    <body>
        
<?php

//データベースへ接続(4-1)

$dsn = 'mysql:dbname=tb240373db;host=localhost';
$user = 'tb-240373';
$password = '9XXb5xXefg';
//PHPからデータベースへ簡単にアクセスできる魔法の言葉
//メリット:データベースの種類やバージョンの違いを気にせず接続できる
//デメリット:データベースの機能をフルに扱う事ができない
$pdo = new PDO($dsn,$user,$password,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

//データベース内にテーブルを作成(4-2)
//テーブル名:tbtest_123
$sql = "CREATE TABLE IF NOT EXISTS tbtest_123"
 ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "name char(32),"
    . "comment TEXT,"
    . "date char(32),"
    . "pass TEXT"
    .");";
    $stmt = $pdo -> query($sql);
    
//入力フォームの受け取り
    $name = $_POST['name'];
    $comment = $_POST['comment'];
    $pass = $_POST['pass'];
    $delete = $_POST['delete'];
    $delpass = $_POST['delpass'];
    $edit = $_POST['edit'];
    $editpass = $_POST['editpass'];
    $date = date("Y/m/d H:i:s");

//登録
      if(!empty($pass)){
      $sql = $pdo -> prepare("INSERT INTO tbtest_123 (name, comment, date, pass) VALUES (:name, :comment, :date, :pass)");
      $sql -> bindParam(':name', $name, PDO::PARAM_STR);
      $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
      $sql -> bindParam(':date', $date, PDO::PARAM_STR);
      $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
      $sql -> execute();
      }


//削除機能
if(!empty($delete)){
  $sql = 'SELECT * FROM tbtest_123';
  $stmt = $pdo->prepare($sql);//値部分にパラメータを付けて実行待ち
  //bindParam:与えられた変数を文字列としてパラメータに入れる
  //PDO::PARAM_INT:変数の値を数値として扱う
  $stmt->bindParam(';id',$delete,PDO::PARAM_INT);
  $stmt->execute();//準備したprepareに入っているSQL文を実行
  $results = $stmt->fetchAll();//配列の形式を指定するモード
  foreach($results as $row) {
      //パスワードが一致したら
     if($row['id'] == $delete && $delpass == $row['pass']){
         //WHERE:DELETE対象を限定する
        $sql = 'DELETE FROM tbtest_123 WHERE id=:id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $delete, PDO::PARAM_INT);
        $stmt->execute();
     }
  }
}

//編集機能
  //編集フォームに表示
  $sql = 'SELECT * FROM tbtest_123';
  $stmt = $pdo->prepare($sql);//値部分にパラメータを付けて実行待ち
  //bindParam:与えられた変数を文字列としてパラメータに入れる
  //PDO::PARAM_INT:変数の値を数値として扱う
  $stmt->bindParam(';id',$edit,PDO::PARAM_INT);
  $stmt->execute();//準備したprepareに入っているSQL文を実行
  $results = $stmt->fetchAll();//配列の形式を指定するモード
  foreach($results as $row) {
        if (!empty($edit) && $row['id'] == $edit && $row['pass'] == $editpass) { //投稿番号と編集フォームに入力した番号が同じなら
                $editnum = $row['id']; //value属性を用いて既存の投稿をフォームに表示
                $editname = $row['name']; 
                $editcomment = $row['comment'];
                $editpass = $row['pass'];
        }
    }
    
    //編集実行（UPDATE文）
    if (!empty($name) && !empty($comment) && !empty($editNo)) {
        $id = $editNo; //変更する投稿番号
        $sql = 'UPDATE boards SET name=:name,comment=:comment,date=:date,pass=:pass WHERE id=:id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
        $stmt->bindParam(':pass',$pass, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }

    

 //表示
      $sql = 'SELECT * FROM tbtest_123';
      $stmt = $pdo->query($sql);
      $results = $stmt->fetchAll();
      foreach($results as $row){
          echo $row['id'].',';
          echo $row['name'].',';
          echo $row['comment'].',';
          echo $row['date'].'<br>';
          echo "<hr>";
      }
      
 
?>

<form method = "post" action = "">
        <input type = "text" name = "name" placeholder = "名前" value= "<?php if(!empty($editname)) {echo $editname;} ?>"><br>
        <input type = "text" name = "comment" placeholder = "コメント"  value= "<?php if(!empty($editcomment)) {echo $editcomment;} ?>"><br>
        <input type = "text" name = "pass" placeholder = "パスワード"  value= "<?php if(!empty($editpass)) {echo $editpass;} ?>">
        <input type = "submit" value = "送信"><br>
        <input type = "text" name = "delete" placeholder = "削除対象番号"><br>
        <input type = "text" name = "delpass" placeholder = "パスワード">
        <input type = "submit" value = "削除"><br>
        <input type = "text" name = "edit" placeholder = "編集対象番号"><br>
        <input type = "text" name = "editpass" placeholder = "パスワード">
        <input type = "submit" value = "編集"><br>
        <input type ="hidden" name="editNo" placeholder="編集する番号表示" value="<?php if(!empty($editnum)) {echo $editnum;} ?>">

    </form>
    </body>
</html>











