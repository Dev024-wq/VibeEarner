<?php
// Store unique visits (not page refreshes) per visitor per day per IP
$file = __DIR__ . '/real_visitors.txt';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Identify user (here: IP+date). For a real-world project, use cookies or localstorage instead
    $visitor_id = $_SERVER['REMOTE_ADDR'] . '_' . date('Y-m-d');

    $data = file_exists($file) ? file($file, FILE_IGNORE_NEW_LINES) : [];
    if(!in_array($visitor_id, $data)) {
        $data[] = $visitor_id;
        file_put_contents($file, implode(PHP_EOL, $data));
    }
    exit;
} else {
    // Count unique lines (visitors)
    $count = file_exists($file) ? count(file($file, FILE_IGNORE_NEW_LINES)) : 0;
    echo $count;
}
?>
