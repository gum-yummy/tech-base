<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_3-5</title>
</head>
<body>
    
    好きな食べ物を教えてください！<br>
    <?php

    $dsn = 'mysql:dbname=***;host=localhost';
    $user = '***';
    $password = '***';
    $pdo = new PDO($dsn, $user, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING]);

    $sql = "CREATE TABLE IF NOT EXISTS table5_1"
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "name char(32),"
    . "comment TEXT,"
    . "post_date TEXT,"
    . "pass TEXT"
    .");";
    $stmt = $pdo->query($sql);
    
    ?>
    
    <?php
        if(isset($_POST["edit_sub"])&&isset($_POST["edit"])&&!empty($_POST["pass3"])){
            $edit = $_POST["edit"];
            $pass = $_POST["pass3"];

            $id = $edit; 
            $sql = 'SELECT * FROM table5_1 WHERE id = :id AND pass=:pass';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
            $stmt->execute();
            $results = $stmt->fetch();
                        
            if(!$results){
                $name_v = "お名前";
                $comment_v = "コメント";
                $edit_v = "";
                $pass_v = "";
            }else{
                $name_v = $results["name"];
                $comment_v = $results["comment"];
                $edit_v = $edit;
                $pass_v = $results["pass"];
                unset($result);


            }                     
            
                                    
            
        }else{
            $name_v = "お名前";
            $comment_v = "コメント";
            $edit_v = "";
            $pass_v = "";
        }
    ?>
    <form action="" method="post" >
        お名前：<input type="text" name="name" value="<?=$name_v?>"><br>
        コメント：<input type="text" name="comment" value="<?=$comment_v?>"><br>
        パスワード：<input type="text" name="pass1" value="<?=$pass_v?>"><br>
        <input type="submit" name="submit"><br><br>
        削除番号：<input type="text" name="delete" value="削除番号"><br>
        パスワード：<input type="text" name="pass2" value=""><br>
        <input type="submit" name="del_sub"><br><br>
        編集番号：<input type="text" name="edit" value="編集番号"><br>
        パスワード：<input type="text" name="pass3" value=""><br>
        <input type="submit" name="edit_sub"><br>
        <input type="hidden" name="edit_num" value="<?=$edit_v?>"><br>
    </form>
    <?php
        
        
        if(isset($_POST["submit"])&&isset($_POST["name"])&&isset($_POST["comment"])){
            $name = $_POST["name"];
            $comment = $_POST["comment"];
            $post_date = date ( "Y/m/d H:i:s" );
            $edit = $_POST["edit_num"];
            $pass = $_POST["pass1"];
            
            if(!empty($edit)){
                $id = $edit; 
                $sql = 'UPDATE table5_1 SET name=:name,comment=:comment,post_date=:post_date,pass=:pass WHERE id=:id';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                $stmt->bindParam(':post_date', $post_date, PDO::PARAM_STR);
                $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
                
            }else{
            
                $sql = $pdo -> prepare("INSERT INTO table5_1 (name, comment, post_date, pass) VALUES (:name, :comment, :post_date, :pass)");
                $sql -> bindParam(':name', $name, PDO::PARAM_STR);
                $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
                $sql -> bindParam(':post_date', $post_date, PDO::PARAM_STR);
                $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
                $sql -> execute();
                

            }
            
            $sql = 'SELECT * FROM table5_1';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach ($results as $row){
                echo $row['id'].' ';
                echo $row['name'].' ';
                echo $row['comment'].' ';
                echo $row['post_date']."<br>";
            echo "<hr>";
            }
        }
        
        
        if(isset($_POST["del_sub"])&&isset($_POST["delete"])&&!empty($_POST["pass2"])){
            $delete = $_POST["delete"];
            $pass = $_POST["pass2"];          

                $id = $delete;
                $sql = 'delete from table5_1 where id=:id AND pass=:pass';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
                $stmt->execute();

                $sql = 'SELECT * FROM table5_1';
                $stmt = $pdo->query($sql);
                $results = $stmt->fetchAll();
                foreach ($results as $row){
                    echo $row['id'].' ';
                    echo $row['name'].' ';
                    echo $row['comment'].'';
                    echo $row['post_date']."<br>";
                echo "<hr>";
                }
        }
        
        
        
    ?>
    
</body>
</html>
