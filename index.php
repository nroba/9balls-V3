<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>9Balls メニュー</title>
  <link rel="manifest" href="manifest.json">
  <link rel="icon" href="images/icon-192.png">
  <meta name="theme-color" content="#0d6efd">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #f5f7fa, #c3cfe2);
      font-family: 'Segoe UI', sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }
    .menu-container {
      background-color: white;
      padding: 40px;
      border-radius: 20px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
      text-align: center;
      width: 90%;
      max-width: 400px;
    }
    .menu-container h1 {
      font-size: 2rem;
      margin-bottom: 30px;
    }
    .menu-btn {
      margin: 10px 0;
      font-size: 1.2rem;
      padding: 12px 24px;
      width: 100%;
      border-radius: 12px;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
    }
    .menu-btn:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
    }
    .menu-icon {
      font-size: 1.5rem;
    }
  </style>
</head>
<body>
  <div class="menu-container">
    <h1>🎱 9Balls メニュー</h1>
    <a href="daily/daily.php" class="btn btn-primary menu-btn"><span class="menu-icon">📅</span> 日別まとめの登録</a>
    <a href="match_results/match_results.php" class="btn btn-info text-white menu-btn"><span class="menu-icon">📊</span> 日別まとめの閲覧</a>
    <a href="pocketmode/index.html" class="btn btn-success menu-btn"><span class="menu-icon">🎯</span> Pocketmode</a>
    <a href="settings.php" class="btn btn-secondary menu-btn"><span class="menu-icon">⚙</span> 各種マスタ設定</a>
  </div>

  <script>
    if ('serviceWorker' in navigator) {
      navigator.serviceWorker.register('service-worker.js')
        .then(() => console.log("Service Worker Registered"))
        .catch(err => console.error("SW registration failed:", err));
    }
  </script>
</body>
</html>
