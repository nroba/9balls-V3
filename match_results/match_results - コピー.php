<?php
// /match_results/match_results.php
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ビリヤード対戦集計結果</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
    <h1 class="mb-4 text-center">ビリヤード対戦 集計結果</h1>

    <div class="table-responsive">
        <table class="table table-striped table-bordered align-middle text-center">
            <thead class="table-dark">
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
            <tbody id="table-body">
                <tr><td colspan="8">読み込み中...</td></tr>
            </tbody>
        </table>
    </div>

    <div class="text-center mt-4">
        <a href="/index.php" class="btn btn-secondary">メニューに戻る</a>
    </div>
</div>

<script>
fetch('/api/fetch_results.php')
    .then(response => response.json())
    .then(data => {
        const tbody = document.getElementById('table-body');
        tbody.innerHTML = ''; // 初期表示削除

        if (data.error) {
            tbody.innerHTML = `<tr><td colspan="8">${data.error}</td></tr>`;
            return;
        }

        if (data.length === 0) {
            tbody.innerHTML = `<tr><td colspan="8">データがありません</td></tr>`;
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
        document.getElementById('table-body').innerHTML =
            `<tr><td colspan="8">エラーが発生しました: ${error}</td></tr>`;
    });
</script>

</body>
</html>
