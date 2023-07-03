<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_6-2_login</title>
</head>
<body>

    <style>
    body {
        background-color: #fffafa;
        text-align: center;
    }

    </style>

    ログイン<br>
    
    <form action="" method="post" autocomplete="on">
        　　お名前：<input type="text" name="name" autocomplete="username" placeholder="username"><br>
        パスワード：<input type="password" name="pass" autocomplete="current-password" placeholder="password"><br>
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
            // ログインを確認するためのクエリ
            $loginQuery = $pdo->prepare("SELECT id, pass FROM table_user WHERE name = :name");
            $loginQuery->bindParam(':name', $name, PDO::PARAM_STR);
            $loginQuery->execute();

            $row = $loginQuery->fetch();

            // クエリ結果からログインをチェック
            if ($row !== false && password_verify($pass, $row['pass'])) {
                $user_id = $row['id'];
                $insertQuery = $pdo->prepare("INSERT INTO table_login (user_id, login_date) VALUES (:user_id, now())");
                $insertQuery->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $insertQuery->execute();

                // ログインが成功した場合、他のページにリダイレクト
                $redirectURL = "https://tech-base.net/tb-250025/m6/m6-02.php";
                header("Location: " . $redirectURL . "?user_name=" . urlencode($name) . "&user_pass=" . urlencode($pass));

                exit();
            } else {
                // ログインが失敗した場合の処理を行う（エラーメッセージ表示など）
                echo "ログインに失敗しました。<br>";
            }
        } else {
            // 空の入力フィールドがある場合の処理を行う（エラーメッセージ表示など）
            echo "お名前とパスワードを入力してください。<br>";
        }
    ?>
    <br><br>
    <a href= "https://tech-base.net/tb-250025/m6/m6-02-register.php" >新規登録</a>

</body>
</html>
