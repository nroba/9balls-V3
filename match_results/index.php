<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>9Balls_V3 - 日別サマリ一覧</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    th.sortable:hover {
      cursor: pointer;
      text-decoration: underline;
    }
    .filter-group {
      display: flex;
      flex-wrap: wrap;
      gap: 1em;
      align-items: center;
      margin-bottom: 1em;
    }
    .pagination { justify-content: center; }
  </style>
</head>
<body class="bg-light">
  <div class="container py-5">
    <h1 class="text-center mb-4">📊 日別サマリ一覧</h1>

    <div class="mb-4 d-flex justify-content-end gap-2">
      <a href="../index.php" class="btn btn-secondary">＋ 新規登録へ</a>
      <a href="../index.php" class="btn btn-outline-primary">📅 登録画面に戻る</a>
      <button class="btn btn-outline-success ms-auto" id="exportCsv">📄 CSVダウンロード</button>
    </div>

    <div class="filter-group">
      <label for="filterRule">ルール：</label>
      <select id="filterRule" class="form-select" style="width: 160px;"></select>

      <label for="filterDate">日付：</label>
      <input type="date" id="filterDate" class="form-control" style="width: 200px;">

      <button class="btn btn-outline-secondary" id="clearFilters">フィルター解除</button>
    </div>

    <table class="table table-striped" id="summaryTable">
      <thead>
        <tr>
          <th class="sortable" data-key="date">日付</th>
          <th class="sortable" data-key="rule">ルール</th>
          <th class="sortable" data-key="shop">店舗</th>
          <th class="sortable" data-key="player1">プレイヤー1</th>
          <th class="sortable" data-key="player2">プレイヤー2</th>
          <th>スコア</th>
          <th class="sortable" data-key="total_games">ゲーム数</th>
          <th>勝者</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>

    <nav>
      <ul class="pagination"></ul>
    </nav>
  </div>

  <script>
    let summaryData = [];
    let currentSort = { key: '', asc: true };
    let currentPage = 1;
    const rowsPerPage = 10;

    function fetchAndRender() {
      fetch("fetch_results.php")
        .then(res => res.json())
        .then(data => {
          summaryData = data;
          const ruleSet = new Set(data.map(r => r.rule));
          const ruleSelect = document.getElementById("filterRule");
          ruleSet.forEach(rule => {
            const opt = document.createElement("option");
            opt.value = rule;
            opt.textContent = rule;
            ruleSelect.appendChild(opt);
          });

          loadFiltersFromStorage();
          renderTable();
        });
    }

    function saveFiltersToStorage() {
      localStorage.setItem("filterRule", document.getElementById("filterRule").value);
      localStorage.setItem("filterDate", document.getElementById("filterDate").value);
    }

    function loadFiltersFromStorage() {
      const rule = localStorage.getItem("filterRule");
      const date = localStorage.getItem("filterDate");
      if (rule) document.getElementById("filterRule").value = rule;
      if (date) document.getElementById("filterDate").value = date;
    }

    function renderTable() {
      const rule = document.getElementById("filterRule").value;
      const date = document.getElementById("filterDate").value;
      saveFiltersToStorage();

      const tbody = document.querySelector("#summaryTable tbody");
      tbody.innerHTML = "";

      let filtered = summaryData.filter(row => {
        return (!rule || row.rule === rule) && (!date || row.date === date);
      });

      if (currentSort.key) {
        const { key, asc } = currentSort;
        filtered.sort((a, b) => (a[key] > b[key] ? 1 : -1) * (asc ? 1 : -1));
      }

      const totalPages = Math.ceil(filtered.length / rowsPerPage);
      currentPage = Math.min(currentPage, totalPages) || 1;
      const start = (currentPage - 1) * rowsPerPage;
      const pageData = filtered.slice(start, start + rowsPerPage);

      pageData.forEach(row => {
        const winner = row.total_score1 > row.total_score2
          ? row.player1
          : row.total_score1 < row.total_score2
            ? row.player2
            : "引き分け";
        const tr = document.createElement("tr");
        tr.innerHTML = `
          <td>${row.date}</td>
          <td>${row.rule}</td>
          <td>${row.shop || '-'}</td>
          <td>${row.player1}</td>
          <td>${row.player2}</td>
          <td>${row.total_score1} - ${row.total_score2}</td>
          <td>${row.total_games}</td>
          <td>${winner}</td>
        `;
        tbody.appendChild(tr);
      });

      renderPagination(totalPages);
    }

    function renderPagination(totalPages) {
      const ul = document.querySelector(".pagination");
      ul.innerHTML = "";
      for (let i = 1; i <= totalPages; i++) {
        const li = document.createElement("li");
        li.className = `page-item ${i === currentPage ? 'active' : ''}`;
        li.innerHTML = `<a class="page-link" href="#">${i}</a>`;
        li.onclick = e => {
          e.preventDefault();
          currentPage = i;
          renderTable();
        };
        ul.appendChild(li);
      }
    }

    document.getElementById("filterRule").addEventListener("change", () => { currentPage = 1; renderTable(); });
    document.getElementById("filterDate").addEventListener("change", () => { currentPage = 1; renderTable(); });
    document.getElementById("clearFilters").addEventListener("click", () => {
      document.getElementById("filterRule").value = "";
      document.getElementById("filterDate").value = "";
      currentPage = 1;
      renderTable();
    });

    document.querySelectorAll("th.sortable").forEach(th => {
      th.addEventListener("click", () => {
        const key = th.dataset.key;
        if (currentSort.key === key) {
          currentSort.asc = !currentSort.asc;
        } else {
          currentSort.key = key;
          currentSort.asc = true;
        }
        renderTable();
      });
    });

    document.getElementById("exportCsv").addEventListener("click", () => {
      const rule = document.getElementById("filterRule").value;
      const date = document.getElementById("filterDate").value;
      let filtered = summaryData.filter(row => {
        return (!rule || row.rule === rule) && (!date || row.date === date);
      });

      if (!filtered.length) return alert("出力対象のデータがありません");

      const headers = ["日付", "ルール", "店舗", "プレイヤー1", "プレイヤー2", "スコア1", "スコア2", "ゲーム数", "勝者"];
      const csv = [headers.join(",")];

      filtered.forEach(row => {
        const winner = row.total_score1 > row.total_score2
          ? row.player1
          : row.total_score1 < row.total_score2
            ? row.player2
            : "引き分け";
        const line = [
          row.date, row.rule, row.shop || '', row.player1, row.player2,
          row.total_score1, row.total_score2, row.total_games, winner
        ];
        csv.push(line.map(cell => `"${cell}"`).join(","));
      });

      const BOM = "\uFEFF";
      const blob = new Blob([BOM + csv.join("\n")], { type: "text/csv;charset=utf-8" });
      const dateStr = new Date().toISOString().slice(0, 10);
      const a = document.createElement("a");
      a.href = URL.createObjectURL(blob);
      a.download = `match_summary_${dateStr}.csv`;
      a.click();
      URL.revokeObjectURL(a.href);
    });

    fetchAndRender();
  </script>
</body>
</html>
