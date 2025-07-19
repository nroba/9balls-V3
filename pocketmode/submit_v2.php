<?php
// /pocketmode/submit_v2.php
header('Content-Type: application/json');

require_once __DIR__ . '/../sys/db_connect.php';

try {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!$data || !isset($data['balls'])) {
        throw new Exception("無効な入力データです。");
    }

    $game_id = $data['game_id'] ?? uniqid('game_', true); // UUIDが来ない場合も対応
    $date = date('Y-m-d');
    $rule = $data['rule'] ?? '';
    $shop = $data['shop'] ?? '';
    $player1 = $data['player1'] ?? '';
    $player2 = $data['player2'] ?? '';
    $score1 = $data['score1'] ?? 0;
    $score2 = $data['score2'] ?? 0;
    $ace1 = $data['ace1'] ?? 0;
    $ace2 = $data['ace2'] ?? 0;

    $pdo->beginTransaction();

    foreach ($data['balls'] as $ball_number => $ball) {
        $assigned = $ball['assigned'] ?? null;
        $multiplier = $ball['multiplier'] ?? 1;

        $stmt = $pdo->prepare("
            INSERT INTO match_detail 
            (game_id, date, rule, shop, player1, player2, ball_number, assigned, multiplier, score1, score2, ace1, ace2)
            VALUES 
            (:game_id, :date, :rule, :shop, :player1, :player2, :ball_number, :assigned, :multiplier, :score1, :score2, :ace1, :ace2)
        ");

        $stmt->execute([
            ':game_id' => $game_id,
            ':date' => $date,
            ':rule' => $rule,
            ':shop' => $shop,
            ':player1' => $player1,
            ':player2' => $player2,
            ':ball_number' => $ball_number,
            ':assigned' => $assigned,
            ':multiplier' => $multiplier,
            ':score1' => $score1,
            ':score2' => $score2,
            ':ace1' => $ace1,
            ':ace2' => $ace2
        ]);
    }

    $pdo->commit();
    echo json_encode(['status' => 'success', 'message' => '登録完了', 'game_id' => $game_id]);

} catch (Exception $e) {
    if ($pdo && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log("submit_v2.php エラー: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => '登録失敗: ' . $e->getMessage()]);
}
