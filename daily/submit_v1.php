<?php
require_once __DIR__ . '/../sys/db_connect.php';

try {
    // POSTデータの取得とサニタイズ
    $date = $_POST['date'] ?? '';
    $rule = $_POST['rule'] ?? '';
    $shop = $_POST['shop'] ?? '';
    $player1 = $_POST['player1'] ?? '';
    $player2 = $_POST['player2'] ?? '';
    $total_score1 = intval($_POST['total_score1'] ?? 0);
    $total_score2 = intval($_POST['total_score2'] ?? 0);
    $total_ace1 = intval($_POST['total_ace1'] ?? 0);
    $total_ace2 = intval($_POST['total_ace2'] ?? 0);
    $total_games = intval($_POST['total_games'] ?? 0);
    $comment = $_POST['comment'] ?? '';

    // 入力バリデーション
    if (!$date || !$rule || !$player1 || !$player2 || $total_games <= 0) {
        throw new Exception("必要な情報が不足しています");
    }

    // 重複チェック（同日・同ルール・同プレイヤー）
    $check = $pdo->prepare("SELECT COUNT(*) FROM match_summary WHERE date = ? AND rule = ? AND player1 = ? AND player2 = ?");
    $check->execute([$date, $rule, $player1, $player2]);
    if ($check->fetchColumn() > 0) {
        header("Location: daily.php?duplicate=1");
        exit;
    }

    // DB登録処理
    $stmt = $pdo->prepare("
        INSERT INTO match_summary (
            date, rule, shop, player1, player2,
            total_score1, total_score2,
            total_ace1, total_ace2,
            total_games, comment, created_at
        ) VALUES (
            ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW()
        )
    ");

    $stmt->execute([
        $date, $rule, $shop, $player1, $player2,
        $total_score1, $total_score2,
        $total_ace1, $total_ace2,
        $total_games, $comment
    ]);

    // 成功時にフォームへリダイレクト（スクロール位置とポップアップをJSで処理）
    header("Location: daily.php?success=1#form-top");
    exit;

} catch (Exception $e) {
    error_log("/daily/submit_v1.php error: " . $e->getMessage());
    echo "<p class='text-danger'>エラーが発生しました。再度お試しください。</p>";
}
