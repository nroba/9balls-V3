<?php
// fetch_summary.php
header('Content-Type: application/json');

$date = $_GET['date'] ?? '';
$rule = $_GET['rule'] ?? '';

if (!$date || !$rule) {
    echo json_encode(['status' => 'error', 'message' => '日付またはルールが指定されていません']);
    exit;
}

try {
    $pdo = new PDO(
        'mysql:host=mysql31.conoha.ne.jp;dbname=k75zo_9balls;charset=utf8mb4',
        'k75zo_9balls',
        'nPxjk13@j',
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    $stmt = $pdo->prepare("SELECT * FROM match_detail WHERE date = ? AND rule = ?");
    $stmt->execute([$date, $rule]);
    $matches = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($matches) === 0) {
        echo json_encode(['status' => 'error', 'message' => '該当するデータがありません']);
        exit;
    }

    $shop = $matches[0]['shop'] ?? '';
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
        'shop' => $shop,
        'player1' => $player1,
        'player2' => $player2,
        'total_score1' => $score1,
        'total_score2' => $score2,
        'total_ace1' => $ace1,
        'total_ace2' => $ace2,
        'total_games' => $games
    ]);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'DBエラー: ' . $e->getMessage()]);
}
