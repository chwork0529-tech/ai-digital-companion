<?php
session_start();

function call_get_video_api($imagePath, $audioPath, $resultPath, $folderName) {
    $url = 'http://localhost:5003/get_video';

    $data = json_encode([
        'video_file'  => $imagePath,
        'vocal_file'  => $audioPath,
        'output_file' => $resultPath,
        'folder_name' => $folderName
    ]);

    $options = [
        'http' => [
            'header'  => "Content-Type: application/json\r\n",
            'method'  => 'POST',
            'content' => $data,
            'timeout' => 30
        ],
    ];

    $context = stream_context_create($options);
    $result = @file_get_contents($url, false, $context);

    if ($result === false) {
        return [
            'error' => true,
            'message' => 'Video API request failed'
        ];
    }

    $decoded = json_decode($result, true);

    if (!is_array($decoded)) {
        return [
            'error' => true,
            'message' => 'Invalid JSON response from video API'
        ];
    }

    return $decoded;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['username'])) {
        echo 'Unauthorized';
        exit();
    }

    $imagePathInput = $_POST['imagepath'] ?? '';
    $audioPathInput = $_POST['audiopath'] ?? '';
    $username = $_SESSION['username'];

    if ($imagePathInput === '' || $audioPathInput === '') {
        echo 'Missing required parameters';
        exit();
    }

    // 依照目前專案結構取得 frontend 路徑
    $baseFrontPath = __DIR__ . '/../frontend/';

    $imagePath = $baseFrontPath . 'family/' . ltrim($imagePathInput, './');
    $audioPath = $baseFrontPath . ltrim($audioPathInput, './');

    $videoFolder = 'family/' . $username . '/video';
    $fullVideoFolder = $baseFrontPath . $videoFolder;

    if (!file_exists($fullVideoFolder)) {
        mkdir($fullVideoFolder, 0777, true);
    }

    $outputFilename = date("YmdHis") . '.mp4';
    $relativeOutputPath = $videoFolder . '/' . $outputFilename;
    $fullOutputPath = $baseFrontPath . $relativeOutputPath;

    $parts = explode('/', $imagePathInput);
    $folderName = isset($parts[1]) ? pathinfo($parts[1], PATHINFO_FILENAME) : 'default';

    $responseData = call_get_video_api($imagePath, $audioPath, $fullOutputPath, $folderName);

    if (isset($responseData['error'])) {
        echo $responseData['message'];
        exit();
    }

    if (isset($responseData['status'])) {
        echo './' . $relativeOutputPath;
    } else {
        echo 'Invalid API response';
    }
}
?>