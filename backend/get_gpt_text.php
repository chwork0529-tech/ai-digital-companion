<?php

function call_get_gpt_text_api($msg) {
    $url = 'http://localhost:5001/get_gpt_text';

    $data = json_encode([
        'msg' => $msg
    ]);

    $options = [
        'http' => [
            'header'  => "Content-Type: application/json\r\n",
            'method'  => 'POST',
            'content' => $data,
            'timeout' => 10 // 防止卡住
        ],
    ];

    $context = stream_context_create($options);

    $result = @file_get_contents($url, false, $context);

    if ($result === FALSE) {
        return [
            'error' => true,
            'message' => 'API request failed'
        ];
    }

    return json_decode($result, true);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $msg = $_POST['msg'] ?? '';

    if (empty($msg)) {
        echo json_encode(['error' => 'Empty message']);
        exit();
    }

    $responseData = call_get_gpt_text_api($msg);

    if (isset($responseData['error'])) {
        echo 'AI service unavailable';
        exit();
    }

    if (isset($responseData['status'])) {
        echo $responseData['status'];
    } else {
        echo 'Invalid API response';
    }
}
?>