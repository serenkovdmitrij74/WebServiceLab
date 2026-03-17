<?php
$upload_dir = __DIR__ . '/files/';
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777);
}

if (!isset($_FILES['file'])) {
    die('Файл не получен');
}

$file = $_FILES['file'];

$filename = 'services_exported_' . date('Y-m-d_H-i-s') . '.json';
$filepath = $upload_dir . $filename;

if (move_uploaded_file($file['tmp_name'], $filepath)) {
    $file_url = 'http://localhost/lrTest/files/' . $filename;
    
    echo json_encode(['file_url' => $file_url]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Ошибка сохранения']);
}