<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo 'Invalid request method';
    exit();
}

if (!isset($_FILES['audio']) || $_FILES['audio']['error'] !== UPLOAD_ERR_OK) {
    echo 'No audio file uploaded or upload failed';
    exit();
}

$uploadDir = __DIR__ . '/../frontend/uploads/';

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$filename = basename($_FILES['audio']['name']);
$uploadFile = $uploadDir . $filename;

if (move_uploaded_file($_FILES['audio']['tmp_name'], $uploadFile)) {
    echo 'Audio file successfully saved as ' . $uploadFile;
} else {
    echo 'Error saving audio file';
}
?>