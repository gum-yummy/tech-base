<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_6-2_register</title>
</head>
<body>
    <style>
        body {
            background-color: #fffafa;
            text-align: center;
        }

    </style>
    新規登録<br>


    <form action="" method="post" >
        　　お名前：<input type="text" name="name" placeholder="username"><br>
        パスワード：<input type="password" name="pass" placeholder="password"><br>
        <input type="submit" name="login"><br>
    </form>

    <?php
        include 'dbConfig.php';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST["name"] ?? "";
            $pass = $_POST["pass"] ?? "";
        }

        // 入力フィールドの値が空でないかチェック
        if (!empty($name) && !empty($pass)) {
            // データの重複を確認するためのクエリ
            $hashedPassword = password_hash($pass, PASSWORD_DEFAULT);
            $checkDuplicateQuery = $pdo->prepare("SELECT COUNT(*) FROM table_user WHERE name = :name OR pass = :pass");
            $checkDuplicateQuery->bindParam(':name', $name, PDO::PARAM_STR);
            $checkDuplicateQuery->bindParam(':pass', $hashedPassword, PDO::PARAM_STR);
            $checkDuplicateQuery->execute();

            // クエリ結果から重複をチェック
            if ($checkDuplicateQuery->fetchColumn() == 0) {
                // 重複がない場合のみデータを挿入するクエリ
                $insertQuery = $pdo->prepare("INSERT INTO table_user (name, pass) VALUES (:name, :pass)");
                $insertQuery->bindParam(':name', $name, PDO::PARAM_STR);
                $insertQuery->bindParam(':pass', $hashedPassword, PDO::PARAM_STR);
                $insertQuery->execute();
                
                $redirectURL ="https://tech-base.net/tb-250025/m6/m6-02_register_success.php";
                header("Location: ".$redirectURL);
                exit();
                
            } else {
                // 重複がある場合の処理を行う（エラーメッセージ表示など）
                echo "重複するデータが存在します。"."<br>";
            }
        } else {
            // 空の入力フィールドがある場合の処理を行う（エラーメッセージ表示など）
            echo "お名前とパスワードを入力してください。"."<br>";
        }


    ?>

    
</body>
</html>
