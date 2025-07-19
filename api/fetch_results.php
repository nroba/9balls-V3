<?php
// /api/fetch_results.php
header('Content-Type: application/json');

require_once __DIR__ . '/../sys/db_connect.php';

try {
    $stmt = $pdo->query("SELECT 
        date, rule, shop, player1, player2,
        SUM(score1) AS total_score1,
        SUM(score2) AS total_score2,
        COUNT(*) AS total_games
        FROM match_detail
        GROUP BY date, rule, shop, player1, player2
        ORDER BY date DESC
    ");

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($results);

} catch (PDOException $e) {
    error_log("fetch_results.php error: " . $e->getMessage());
    echo json_encode(['error' => 'DBエラー: ' . $e->getMessage()]);
}
