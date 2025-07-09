<?php
// /api/fetch_master.php
// 店舗名（shop_master）とユーザー名（user_master）のマスタデータを取得
// 結果は JSON 形式で返され、main.js や /daily/daily.php などのセレクトボックスの初期化に使用可能
header('Content-Type: application/json');

require_once __DIR__ . '/../sys/db_connect.php';

try {
    $shops = $pdo->query("SELECT name FROM shop_master ORDER BY name")
                ->fetchAll(PDO::FETCH_COLUMN);

    $users = $pdo->query("SELECT name FROM user_master ORDER BY name")
                ->fetchAll(PDO::FETCH_COLUMN);

    echo json_encode([
        'status' => 'success',
        'shops' => $shops,
        'users' => array_map(fn($name) => ['name' => $name], $users)
    ]);

} catch (PDOException $e) {
    error_log("fetch_master.php error: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'DBエラー: ' . $e->getMessage()]);
}
