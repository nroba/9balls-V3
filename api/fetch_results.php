<?php
require_once __DIR__ . '/../sys/db_connect.php';

header('Content-Type: application/json');

$filters = [
    'shop' => isset($_GET['shop']) && $_GET['shop'] !== '' ? $_GET['shop'] : null,
    'user' => isset($_GET['user']) && $_GET['user'] !== '' ? $_GET['user'] : null
];

$sql = "SELECT * FROM match_summary ORDER BY match_date DESC";
$result = mysqli_query($conn, $sql);

$data = [];

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        // フィルタが指定されている場合のみ適用
        if (!is_null($filters['shop']) && $row['shop'] !== $filters['shop']) continue;
        if (!is_null($filters['user']) && $row['user'] !== $filters['user']) continue;

        $data[] = $row;
    }
}

mysqli_close($conn);

echo json_encode($data, JSON_UNESCAPED_UNICODE);
