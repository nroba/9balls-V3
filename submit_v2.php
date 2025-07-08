<?php
// submit_v2.php

header('Content-Type: application/json');

// JSONデータを受け取る
$raw = file_get_contents("php://input");
$data = json_decode($raw, true);

// 安全確認
if (!isset($data['score1'], $data['score2'], $data['balls'])) {
    echo json_encode(['status' => 'error', 'message' => '不正な入力です']);
    exit;
}

// DB接続情報
$dsn = 'mysql:host=mysql31.conoha.ne.jp;dbname=k75zo_9balls;charset=utf8mb4';
$user = 'k75zo_9balls';
$pass = 'nPxjk13@j';

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    // 仮のプレイヤー名（今後拡張可能）
    $player1 = 'Player 1';
    $player2 = 'Player 2';

    // INSERT文実行
    $stmt = $pdo->prepare("
        INSERT INTO match_detail (date, player1, player2, score1, score2, balls_json)
        VALUES (CURDATE(), ?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $player1,
        $player2,
        intval($data['score1']),
        intval($data['score2']),
        json_encode($data['balls'], JSON_UNESCAPED_UNICODE)
    ]);

    echo json_encode(['status' => 'success', 'message' => '登録が完了しました']);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'DBエラー: ' . $e->getMessage()]);
}
