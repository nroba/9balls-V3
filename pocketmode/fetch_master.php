<?php
header('Content-Type: application/json');
$pdo = new PDO('mysql:host=mysql31.conoha.ne.jp;dbname=k75zo_9balls;charset=utf8mb4', 'k75zo_9balls', 'nPxjk13@j');
$shops = $pdo->query("SELECT name FROM shop_master ORDER BY name")->fetchAll(PDO::FETCH_COLUMN);
$users = $pdo->query("SELECT name FROM user_master ORDER BY name")->fetchAll(PDO::FETCH_COLUMN);
echo json_encode(['shops' => $shops, 'users' => $users]);
