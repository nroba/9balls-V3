<?php
require_once __DIR__ . '/../sys/db_connect.php';

$shop_list = $pdo->query("SELECT name FROM shop_master ORDER BY name")
                  ->fetchAll(PDO::FETCH_COLUMN);
$user_list = $pdo->query("SELECT name FROM user_master ORDER BY name")
                  ->fetchAll(PDO::FETCH_COLUMN);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>9Balls_V3 - 日別記録フォーム</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../style.css">
</head>
<body class="bg-light">
  <div class="container py-5">
    <h1 class="text-center mb-4">📝 日別まとめ登録</h1>

    <?php if (isset($_GET['success'])): ?>
      <div class="alert alert-success" id="form-top">✅ 登録が完了しました！</div>
    <?php elseif (isset($_GET['duplicate'])): ?>
      <div class="alert alert-warning" id="form-top">⚠ 同じ内容が既に登録されています。</div>
    <?php endif; ?>

    <div class="card shadow">
      <div class="card-header bg-primary text-white">フォーム入力</div>
      <div class="card-body">
        <form action="submit_v1.php" method="post">
          <div class="mb-3">
            <label class="form-label">対戦日</label>
            <input type="date" name="date" id="date" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">ルール</label>
            <select name="rule" id="rule" class="form-select" required>
              <option value="A">ルールA（奇数のみ）</option>
              <option value="B">ルールB（全ボール）</option>
              <option value="custom">カスタム（準備中）</option>
            </select>
          </div>

          <div class="text-end mb-3">
            <button type="button" class="btn btn-outline-secondary" onclick="fetchPocketSummary()">📥 Pocketmodeから取得</button>
          </div>

          <div class="mb-3">
            <label class="form-label">店舗名</label>
            <input type="text" name="shop" class="form-control" list="shop_list" required>
            <datalist id="shop_list">
              <?php foreach ($shop_list as $s): ?>
                <option value="<?= htmlspecialchars($s) ?>">
              <?php endforeach; ?>
            </datalist>
          </div>

          <div class="row">
            <div class="col-md-6">
              <label class="form-label">プレイヤー1名</label>
              <input type="text" name="player1" class="form-control" list="user_list" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">プレイヤー2名</label>
              <input type="text" name="player2" class="form-control" list="user_list" required>
            </div>
            <datalist id="user_list">
              <?php foreach ($user_list as $u): ?>
                <option value="<?= htmlspecialchars($u) ?>">
              <?php endforeach; ?>
            </datalist>
          </div>

          <div class="row mt-3">
            <div class="col-md-6">
              <label class="form-label">プレイヤー1 総得点</label>
              <input type="number" name="total_score1" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">プレイヤー2 総得点</label>
              <input type="number" name="total_score2" class="form-control" required>
            </div>
          </div>

          <div class="row mt-3">
            <div class="col-md-6">
              <label class="form-label">プレイヤー1 エース数</label>
              <input type="number" name="total_ace1" class="form-control" value="0" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">プレイヤー2 エース数</label>
              <input type="number" name="total_ace2" class="form-control" value="0" required>
            </div>
          </div>

          <div class="mt-3">
            <label class="form-label">総ゲーム数</label>
            <input type="number" name="total_games" class="form-control" required>
          </div>

          <div class="mt-3">
            <label class="form-label">コメント（任意）</label>
            <textarea name="comment" class="form-control" rows="3"></textarea>
          </div>

          <div class="d-grid mt-4">
            <button type="submit" class="btn btn-primary btn-lg">✅ この内容で記録する</button>
          </div>
        </form>

        <div class="text-center mt-4">
          <a href="../pocketmode/index.html" class="btn btn-success">▶ Pocketmode（V2）に進む</a>
          <a href="../settings.php" class="btn btn-outline-dark ms-2">⚙ 設定画面へ</a>
          <a href="../match_results/index.php" class="btn btn-outline-info ms-2">📊 サマリ一覧へ</a>
        </div>
      </div>
    </div>
  </div>

  <script>
    function fetchPocketSummary() {
      const date = document.getElementById("date").value;
      const rule = document.getElementById("rule").value;
      if (!date || !rule) {
        alert("日付とルールを選択してください");
        return;
      }

      fetch("../api/fetch_summary.php?date=" + encodeURIComponent(date) + "&rule=" + encodeURIComponent(rule))
        .then(res => res.json())
        .then(data => {
          if (data.status === "success") {
            document.querySelector('[name=shop]').value = data.shop;
            document.querySelector('[name=player1]').value = data.player1;
            document.querySelector('[name=player2]').value = data.player2;
            document.querySelector('[name=total_score1]').value = data.total_score1;
            document.querySelector('[name=total_score2]').value = data.total_score2;
            document.querySelector('[name=total_ace1]').value = data.total_ace1;
            document.querySelector('[name=total_ace2]').value = data.total_ace2;
            document.querySelector('[name=total_games]').value = data.total_games;
          } else {
            alert(data.message || "該当データが見つかりませんでした");
          }
        })
        .catch(err => {
          console.error(err);
          alert("通信エラーが発生しました");
        });
    }
  </script>
</body>
</html>
