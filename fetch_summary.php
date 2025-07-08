<?php
// fetch_summary.php

header('Content-Type: application/json');

$date = $_GET['date'] ?? '';
if (!$date) {
    echo json_encode(['status' => 'error', 'message' => '日付が指定されていません']);
    exit;
}

$pdo = new PDO('mysql:host=127.0.0.1;dbname=your_db;charset=utf8mb4', 'user', 'pass');

// その日付のV2記録をすべて取得
$stmt = $pdo->prepare("SELECT * FROM match_detail WHERE date = ?");
$stmt->execute([$date]);
$matches = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($matches) === 0) {
    echo json_encode(['status' => 'error', 'message' => 'データなし']);
    exit;
}

// プレイヤー名は最初の1件から
$player1 = $matches[0]['player1'];
$player2 = $matches[0]['player2'];
$score1 = 0;
$score2 = 0;
$ace1 = 0;
$ace2 = 0;
$games = count($matches);

foreach ($matches as $m) {
    $score1 += intval($m['score1']);
    $score2 += intval($m['score2']);
    $ace1 += intval($m['ace1'] ?? 0);
    $ace2 += intval($m['ace2'] ?? 0);
}

echo json_encode([
    'status' => 'success',
    'player1' => $player1,
    'player2' => $player2,
    'total_score1' => $score1,
    'total_score2' => $score2,
    'total_ace1' => $ace1,
    'total_ace2' => $ace2,
    'total_games' => $games
]);
