<?php

// 値を受け取る（まずは$_REQUESTで受けてみる）
$title = htmlspecialchars($_REQUEST['title'] ?? '', ENT_QUOTES, 'UTF-8');
$artist = htmlspecialchars($_REQUEST['artist'] ?? '', ENT_QUOTES, 'UTF-8');
$note = htmlspecialchars($_REQUEST['note'] ?? '', ENT_QUOTES, 'UTF-8');

// 改行を "\n" に変換して1行で保存
$note = str_replace(["\r\n","\r","\n"], '\n', $note);

// チェック：どれか1つでも空ならエラー
if (!$title || !$artist || !$note) {
    echo json_encode([
        "status" => "error",
        "message" => "すべての項目を入力してください"
    ]);
    exit;
}

// 時刻取得
$time = date('Y-m-d H:i:s');

// 保存（CSV）
$line = "\"$time\",\"$title\",\"$artist\",\"$note\"\n";
file_put_contents('notes.csv', $line, FILE_APPEND | LOCK_EX);

// レスポンス
echo json_encode(["status" => "ok"]);
?>
