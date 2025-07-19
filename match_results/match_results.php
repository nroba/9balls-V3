<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>対戦サマリ一覧</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container mt-4">
    <h1 class="mb-4">対戦サマリ一覧</h1>

    <div class="table-responsive">
      <table class="table table-striped" id="summary-table">
        <thead>
          <tr>
            <th>日付</th>
            <th>ルール</th>
            <th>店舗</th>
            <th>プレイヤー1</th>
            <th>プレイヤー2</th>
            <th>スコア1</th>
            <th>スコア2</th>
            <th>試合数</th>
          </tr>
        </thead>
        <tbody>
          <!-- データはここに挿入されます -->
        </tbody>
      </table>
    </div>
  </div>

  <script>
  document.addEventListener('DOMContentLoaded', function () {
    fetch('/api/fetch_summary.php')
      .then(response => response.json())
      .then(data => {
        const tbody = document.querySelector('#summary-table tbody');
        tbody.innerHTML = '';

        if (!Array.isArray(data)) {
          console.error('不正なデータ形式:', data);
          tbody.innerHTML = `<tr><td colspan="8">データの読み込みに失敗しました。</td></tr>`;
          return;
        }

        if (data.length === 0) {
          tbody.innerHTML = `<tr><td colspan="8">データが存在しません。</td></tr>`;
          return;
        }

        data.forEach(row => {
          const tr = document.createElement('tr');
          tr.innerHTML = `
            <td>${row.date}</td>
            <td>${row.rule}</td>
            <td>${row.shop}</td>
            <td>${row.player1}</td>
            <td>${row.player2}</td>
            <td>${row.total_score1}</td>
            <td>${row.total_score2}</td>
            <td>${row.total_games}</td>
          `;
          tbody.appendChild(tr);
        });
      })
      .catch(error => {
        console.error('取得エラー:', error);
        const tbody = document.querySelector('#summary-table tbody');
        tbody.innerHTML = `<tr><td colspan="8">データ取得エラーが発生しました。</td></tr>`;
      });
  });
  </script>
</body>
</html>
