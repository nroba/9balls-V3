<?php
// submit_v1.php

// DB接続情報
$dsn = 'mysql:host=mysql31.conoha.ne.jp;dbname=k75zo_9balls;charset=utf8mb4';
$user = 'k75zo_9balls';
$pass = 'nPxjk13@j';

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    $stmt = $pdo->prepare("
        INSERT INTO match_summary (
            date, player1, player2, total_score1, total_score2,
            total_ace1, total_ace2, total_games, comment
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $_POST['date'],
        $_POST['player1'],
        $_POST['player2'],
        intval($_POST['total_score1']),
        intval($_POST['total_score2']),
        intval($_POST['total_ace1']),
        intval($_POST['total_ace2']),
        intval($_POST['total_games']),
        $_POST['comment'] ?? ''
    ]);

    header("Location: index.php?success=1");
    exit;

} catch (Exception $e) {
    echo "DBエラー: " . $e->getMessage();
}
