<?php
header('Content-Type: application/json');

try {
  $pdo = new PDO('mysql:host=mysql31.conoha.ne.jp;dbname=k75zo_9balls;charset=utf8mb4', 'k75zo_9balls', 'nPxjk13@j', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
  ]);

  $stmt = $pdo->query("
    SELECT 
      date, rule, shop, player1, player2,
      SUM(score1) AS total_score1,
      SUM(score2) AS total_score2,
      COUNT(*) AS total_games
    FROM match_detail
    GROUP BY date, rule, shop, player1, player2
    ORDER BY date DESC
  ");

  echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
} catch (PDOException $e) {
  echo json_encode(['error' => 'DBエラー: ' . $e->getMessage()]);
}
