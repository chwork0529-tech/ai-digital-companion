<?php
session_start();

function call_get_audio_api($role, $msg, $filename) {
    $url = 'http://localhost:5002/get_audio';

    $data = json_encode([
        'text' => $msg,
        'user' => $role,
        'filename' => $filename
    ]);

    $options = [
        'http' => [
            'header'  => "Content-Type: application/json\r\n",
            'method'  => 'POST',
            'content' => $data,
            'timeout' => 15
        ],
    ];

    $context = stream_context_create($options);
    $result = @file_get_contents($url, false, $context);

    if ($result === false) {
        return [
            'error' => true,
            'message' => 'Audio API request failed'
        ];
    }

    $decoded = json_decode($result, true);

    if (!is_array($decoded)) {
        return [
            'error' => true,
            'message' => 'Invalid JSON response from audio API'
        ];
    }

    return $decoded;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['username'])) {
        echo 'Unauthorized';
        exit();
    }

    $msg = $_POST['msg'] ?? '';
    $user = $_POST['user'] ?? '';
    $username = $_SESSION['username'];

    if ($msg === '' || $user === '') {
        echo 'Missing required parameters';
        exit();
    }

    // 改成依照目前專案結構取得 frontend 路徑
    $baseFrontPath = __DIR__ . '/../frontend/';
    $audioFolder = 'family/' . $username . '/audio';
    $fullAudioFolder = $baseFrontPath . $audioFolder;

    if (!file_exists($fullAudioFolder)) {
        mkdir($fullAudioFolder, 0777, true);
    }

    $outputFilename = date("YmdHis") . '.wav';
    $relativeOutputPath = $audioFolder . '/' . $outputFilename;
    $fullOutputPath = $baseFrontPath . $relativeOutputPath;

    $role = ($user === 'user2') ? 'user2' : 'user1';

    $responseData = call_get_audio_api($role, $msg, $fullOutputPath);

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