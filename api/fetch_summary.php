<?php
// /api/fetch_summary.php（本番用）
header('Content-Type: application/json');

require_once __DIR__ . '/../sys/db_connect.php';

try {
    $stmt = $pdo->query("SELECT * FROM match_summary ORDER BY date DESC");
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($results);

} catch (PDOException $e) {
    error_log("fetch_summary.php error: " . $e->getMessage());
    echo json_encode(['error' => 'DBエラー: ' . $e->getMessage()]);
}
