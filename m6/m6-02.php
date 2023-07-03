<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_6-2</title>
</head>
<body>
    

    <?php

        include 'dbConfig.php';
    
    ?>
    
    <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $comment = $_POST["comment"] ?? "";
            $edit = $_POST["edit_num"] ?? "";
        }
        $name = $_GET["user_name"];
        $post_date = date ( "Y/m/d H:i:s" );
        $pass = $_GET["user_pass"];

        $targetDir = "image/";
        $fileName = isset($_FILES["file"]["name"]) ? basename($_FILES["file"]["name"]) : "";
        $uniqueFileName = time() . '_' . uniqid() . '_' . $fileName;
        $targetFilePath = $targetDir . $uniqueFileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
        
        if(isset($_POST["edit_sub"])&&isset($_POST["edit"])){
            $edit = $_POST["edit"];


            $id = $edit; 
            $sql = 'SELECT * FROM table6_2 WHERE id = :id AND pass=:pass';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
            $stmt->execute();
            $results = $stmt->fetch();
                        
            if(!$results){
                $comment_v = "コメント";
                $edit_v = "";
                $pass_v = "";
            }else{
                $comment_v = $results["comment"];
                $edi = "編集番号:".$edit;
                $edit_v = $edit;
                unset($result);


            }                     
            
                                    
            
        }else{
            $comment_v = "";
            $edi = "コメント：";
            $edit_v = "";
        
        }
    ?>

    <style>
        #textarea {
            text-align: left;
            resize: none;
            width: 90%;
        }

        .max-size-image {
            max-width: 400px;
            max-height: 400px;
        }

        .form-container {
            display: flex;
        }
        
        .form-container .left-column {
            width: 40%;
            position: fixed;
            height: 100vh;
            overflow-y: scroll;
            background-color: #fffafa; 
        }
        
        .form-container .right-column {
            width: 60%;
            margin-left: 40%;
            overflow-y: scroll;
            padding-left: 20px;
            background-color:#fffafa;
        }

        
        .form-container .right-column::-webkit-scrollbar {
            width: 0.5em; 
            background-color: #fffafa; 
        }

        .form-container .left-column::-webkit-scrollbar{
            width: 0.5em; 
            background-color: #fffafa; 
        }
        
        .form-container .left-column::-webkit-scrollbar-thumb,
        .form-container .right-column::-webkit-scrollbar-thumb {
            background-color: #999; 
        }
    </style>

        
    <div class="form-container">
        <div class="left-column">
            <form action="" method="post" enctype="multipart/form-data" >
                <input type="text" name="edi" readonly value="<?=$edi?>" style="border:none; outline:none; background-color: #fffafa;"><br>
                <textarea id="textarea" name="comment" rows="10"  oninput="adjustTextareaRows('textarea')"><?=$comment_v?></textarea><br>
                <input type="file" name="file"><br>
                <input type="submit" name="submit"><br><br>

            
                削除番号：<br>
                <input type="number" name="delete" placeholder="削除番号"><br>
                <input type="submit" name="del_sub"><br><br>
                編集番号：<br>
                <input type="number" name="edit" placeholder="編集番号"><br>
                <input type="submit" name="edit_sub"><br>
                <input type="hidden" name="edit_num" value="<?=$edit_v?>"><br>
            </form>
            <br><br>
            <a href= "https://tech-base.net/tb-250025/m6/m6-02-login.php" >ログアウト</a><br><br>
        </div>
        <div class="right-column">
            <script>

                function adjustTextareaRows(textareaId) {
                    var textarea = document.getElementById(textareaId);
                    textarea.style.height = "auto";
                    textarea.style.height = textarea.scrollHeight + "px";
                }


            </script> 

            <?php


                    
                
                if(isset($_POST["submit"])&&isset($_POST["comment"])){
                    if(($_POST["comment"])!=""){
                        if (!empty($_FILES["file"]["name"])) {
                            // 特定のファイル形式の許可
                            $allowTypes = array('jpg', 'png', 'jpeg', 'gif', 'pdf');
                            if (in_array($fileType, $allowTypes)) {
                                if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)) {
                                    $image = $targetFilePath;
                                }
                            } else {
                                echo "エラー: 不正なファイル形式です。";
                            }
                        }
                        
                        
                        if(!empty($edit)){
                            $id = $edit; 
                            $sql = 'UPDATE table6_2 SET name=:name,comment=:comment,image =:image,post_date=:post_date,pass=:pass WHERE id=:id';
                            $stmt = $pdo->prepare($sql);
                            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                            $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                            $stmt->bindParam(':image', $image, PDO::PARAM_STR);
                            $stmt->bindParam(':post_date', $post_date, PDO::PARAM_STR);
                            $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
                            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                            $stmt->execute();
                            
                        }else{
                        
                            $sql = $pdo -> prepare("INSERT INTO table6_2 (name, comment,image, post_date, pass) VALUES (:name, :comment,:image, :post_date, :pass)");
                            $sql -> bindParam(':name', $name, PDO::PARAM_STR);
                            $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
                            $sql ->bindParam(':image', $image, PDO::PARAM_STR);
                            $sql -> bindParam(':post_date', $post_date, PDO::PARAM_STR);
                            $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
                            $sql -> execute();
                            

                        }
                        
                        $image = "";
                        $comment = "";
                        echo "コメントの投稿が完了しました！"."<br><br>";
                        header("Location: {$_SERVER['REQUEST_URI']}");
                        exit();
                    }else{
                        echo "コメントを入力してください"."<br><br>";
                    }
                }
                
                
                if(isset($_POST["del_sub"])&&isset($_POST["delete"])){
                    $delete = $_POST["delete"];
                

                        $id = $delete;
                        $sql = 'delete from table6_2 where id=:id AND pass=:pass';
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                        $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
                        $stmt->execute();


                }

                
                $sql = 'SELECT * FROM table6_2';
                $stmt = $pdo->query($sql);
                $results = $stmt->fetchAll();
                echo "【投稿一覧】"."<br>";
                echo "<hr>";
                foreach ($results as $row){
                    echo $row['id'].' ';
                    echo $row['name'].' ';
                    echo $row['post_date']."<br>";
                    echo nl2br($row['comment'])."<br>";
                    if($row['image'] != NULL){
                    ?>
                    <a href="<?php echo $row['image']; ?>" target="_blank">
                    <img src="<?php echo $row['image']; ?>" class="max-size-image" alt="" />
                    </a>
                    <br>
                    <?php
                    }
                echo "<hr>";
                } 
                
                
            ?>
            
</body>
</html>
