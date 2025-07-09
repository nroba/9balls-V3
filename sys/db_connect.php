<?php
// db_connect.php
try {
    $pdo = new PDO(
        'mysql:host=mysql31.conoha.ne.jp;dbname=k75zo_9balls;charset=utf8mb4',
        'k75zo_9balls',
        'nPxjk13@j',
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    error_log("DB接続エラー: " . $e->getMessage());
    exit("データベース接続に失敗しました。");
}
