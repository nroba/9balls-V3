<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/sys/db_connect.php';

try {
  $shop_list = $pdo->query("SELECT * FROM shop_master ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
  $user_list = $pdo->query("SELECT * FROM user_master ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  die("データベースエラー: " . htmlspecialchars($e->getMessage()));
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>マスタ設定 - 9Balls</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #f0f2f5;
      padding: 20px;
    }
    .container {
      max-width: 600px;
      margin: auto;
    }
    h1 {
      text-align: center;
      margin-bottom: 30px;
    }
    .section {
      margin-bottom: 40px;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>⚙ マスタ設定</h1>

    <div class="section">
      <h4>🏪 店舗マスタ</h4>
      <form method="post" action="save_shop.php" class="d-flex gap-2">
        <input type="text" name="name" class="form-control" placeholder="新規店舗名" required>
        <button type="submit" class="btn btn-primary">追加</button>
      </form>
      <ul class="list-group mt-3">
        <?php foreach ($shop_list as $shop): ?>
          <li class="list-group-item d-flex justify-content-between align-items-center">
            <?= htmlspecialchars($shop['name']) ?>
            <form method="post" action="delete_shop.php" class="m-0">
              <input type="hidden" name="id" value="<?= $shop['id'] ?>">
              <button type="submit" class="btn btn-sm btn-outline-danger">削除</button>
            </form>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>

    <div class="section">
      <h4>👤 ユーザーマスタ</h4>
      <form method="post" action="save_user.php" class="d-flex gap-2">
        <input type="text" name="name" class="form-control" placeholder="新規ユーザー名" required>
        <button type="submit" class="btn btn-primary">追加</button>
      </form>
      <ul class="list-group mt-3">
        <?php foreach ($user_list as $user): ?>
          <li class="list-group-item d-flex justify-content-between align-items-center">
            <?= htmlspecialchars($user['name']) ?>
            <form method="post" action="delete_user.php" class="m-0">
              <input type="hidden" name="id" value="<?= $user['id'] ?>">
              <button type="submit" class="btn btn-sm btn-outline-danger">削除</button>
            </form>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>

    <div class="text-center">
      <a href="index.php" class="btn btn-outline-secondary">🏠 トップに戻る</a>
    </div>
  </div>
</body>
</html>
