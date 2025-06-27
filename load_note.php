<?php
header('Content-Type: application/json');

$notes = [];
if (file_exists('notes.csv')) {
    $lines = file('notes.csv', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        [$time, $title, $artist, $note] = str_getcsv($line);
        // 空データの場合はスキップ
        if (trim($title) === '' && trim($artist) === '' && trim($note) === '') {
            continue;
        }
        $notes[] = compact('time', 'title', 'artist', 'note');
    }
}
echo json_encode($notes);
?>
