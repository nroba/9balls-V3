<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>9Balls_V3 - 日別記録</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body class="bg-light">
  <div class="container py-5">

    <div class="card shadow">
      <div class="card-header bg-primary text-white">日別まとめ記録（V1形式）</div>
      <div class="card-body">
        <form action="submit_v1.php" method="post">
          <div class="mb-3">
            <label class="form-label">対戦日</label>
            <input type="date" name="date" id="date" class="form-control" required>
          </div>

          <div class="text-end mb-3">
            <button type="button" class="btn btn-outline-secondary" onclick="fetchPocketSummary()">pocketmodeから取得</button>
          </div>

          <div class="row">
            <div class="col-md-6">
              <label class="form-label">プレイヤー1名</label>
              <input type="text" name="player1" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">プレイヤー2名</label>
              <input type="text" name="player2" class="form-control" required>
            </div>
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
            <button type="submit" class="btn btn-primary btn-lg">この内容で記録する</button>
          </div>
        </form>

        <div class="text-center mt-4">
          <a href="pocketmode/index.html" class="btn btn-success">▶ Pocketmode（V2）に進む</a>
        </div>
      </div>
    </div>
  </div>

  <script>
    function fetchPocketSummary() {
      const date = document.getElementById("date").value;
      if (!date) {
        alert("日付を選択してください");
        return;
      }

      fetch("fetch_summary.php?date=" + encodeURIComponent(date))
        .then(res => res.json())
        .then(data => {
          if (data.status === "success") {
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
