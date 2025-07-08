<?php
// submit_v2.php

header('Content-Type: application/json');

// JSONデータ受け取り
$raw = file_get_contents("php://input");
$data = json_decode($raw, true);

// 必須項目チェック
if (!isset($data['score1'], $data['score2'], $data['balls'], $data['rule'], $data['shop'], $data['player1'], $data['player2'])) {
    echo json_encode(['status' => 'error', 'message' => '必要な情報が不足しています']);
    exit;
}

// 文字数制限チェック
$rule = mb_substr($data['rule'], 0, 10);
$shop = mb_substr($data['shop'], 0, 100);
$player1 = mb_substr($data['player1'], 0, 100);
$player2 = mb_substr($data['player2'], 0, 100);

try {
    $pdo = new PDO('mysql:host=mysql31.conoha.ne.jp;dbname=k75zo_9balls;charset=utf8mb4', 'k75zo_9balls', 'nPxjk13@j', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    $stmt = $pdo->prepare("
        INSERT INTO match_detail (
            date, player1, player2, score1, score2, balls_json, rule, shop
        ) VALUES (
            CURDATE(), ?, ?, ?, ?, ?, ?, ?
        )
    ");

    $stmt->execute([
        $player1,
        $player2,
        intval($data['score1']),
        intval($data['score2']),
        json_encode($data['balls'], JSON_UNESCAPED_UNICODE),
        $rule,
        $shop
    ]);

    echo json_encode(['status' => 'success', 'message' => '登録が完了しました']);
} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'DBエラー: ' . $e->getMessage()
    ]);
}
