<?php
// settings.php
$pdo = new PDO('mysql:host=mysql31.conoha.ne.jp;dbname=k75zo_9balls;charset=utf8mb4', 'k75zo_9balls', 'nPxjk13@j');

// 登録処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type'] ?? '';
    $name = trim($_POST['name'] ?? '');

    if ($name && in_array($type, ['user', 'shop'], true)) {
        $table = $type === 'user' ? 'user_master' : 'shop_master';
        $stmt = $pdo->prepare("INSERT IGNORE INTO {$table} (name) VALUES (?)");
        $stmt->execute([$name]);
    }
    header("Location: settings.php");
    exit;
}

// データ取得
$users = $pdo->query("SELECT name FROM user_master ORDER BY name")->fetchAll(PDO::FETCH_COLUMN);
$shops = $pdo->query("SELECT name FROM shop_master ORDER BY name")->fetchAll(PDO::FETCH_COLUMN);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>設定画面 - 9Balls_V3</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container py-5">
    <h1 class="text-center mb-4">設定画面</h1>

    <div class="row g-4">
      <!-- ユーザー名マスタ -->
      <div class="col-md-6">
        <div class="card">
          <div class="card-header bg-info text-white">ユーザー名マスタ登録</div>
          <div class="card-body">
            <form method="post">
              <input type="hidden" name="type" value="user">
              <div class="input-group">
                <input type="text" name="name" class="form-control" placeholder="新しいユーザー名" required>
                <button class="btn btn-info" type="submit">追加</button>
              </div>
            </form>
            <ul class="list-group list-group-flush mt-3">
              <?php foreach ($users as $u): ?>
              <li class="list-group-item"><?= htmlspecialchars($u) ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
        </div>
      </div>

      <!-- 店舗名マスタ -->
      <div class="col-md-6">
        <div class="card">
          <div class="card-header bg-warning">店舗名マスタ登録</div>
          <div class="card-body">
            <form method="post">
              <input type="hidden" name="type" value="shop">
              <div class="input-group">
                <input type="text" name="name" class="form-control" placeholder="新しい店舗名" required>
                <button class="btn btn-warning" type="submit">追加</button>
              </div>
            </form>
            <ul class="list-group list-group-flush mt-3">
              <?php foreach ($shops as $s): ?>
              <li class="list-group-item"><?= htmlspecialchars($s) ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <div class="text-center mt-5">
      <a href="index.php" class="btn btn-outline-primary">← 日別まとめに戻る</a>
    </div>
  </div>
</body>
</html>