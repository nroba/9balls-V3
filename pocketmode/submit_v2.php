<?php
// /pocketmode/submit_v2.php
// JavaScriptから送信された対戦データ（ルール、プレイヤー、ボールごとの結果など）を match_detail テーブルに登録
header('Content-Type: application/json');

require_once __DIR__ . '/../sys/db_connect.php';

try {
    $data = json_decode(file_get_contents("php://input"), true);

    if (!$data) {
        throw new Exception("JSONデータの読み取りに失敗しました");
    }

    $stmt = $pdo->prepare("INSERT INTO match_detail (
        date, rule, shop, player1, player2,
        ball_number, assigned, multiplier,
        score1, score2, ace1, ace2, created_at
    ) VALUES (
        ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW()
    )");

    $date = date('Y-m-d');
    $rule = $data['rule'] ?? '';
    $shop = $data['shop'] ?? '';
    $player1 = $data['player1'] ?? '';
    $player2 = $data['player2'] ?? '';
    $score1 = intval($data['score1'] ?? 0);
    $score2 = intval($data['score2'] ?? 0);

    foreach ($data['balls'] as $num => $info) {
        $ball_number = intval($num);
        $assigned = $info['assigned'] ?? null;
        $multiplier = $info['multiplier'] ?? 1;

        $stmt->execute([
            $date, $rule, $shop, $player1, $player2,
            $ball_number, $assigned, $multiplier,
            $score1, $score2, 0, 0
        ]);
    }

    echo json_encode(['status' => 'success', 'message' => '登録しました']);

} catch (Exception $e) {
    error_log("submit_v2.php error: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => '送信エラー: ' . $e->getMessage()]);
}
