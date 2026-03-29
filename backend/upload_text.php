<?php
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo 'Invalid request';
    exit();
}

if (!isset($_SESSION['username'])) {
    echo 'Unauthorized';
    exit();
}

$msg = trim($_POST['msg'] ?? '');
$user = trim($_POST['user'] ?? '');
$relative_picture_path = trim($_POST['family_picture'] ?? '');
$username = $_SESSION['username'];

if ($msg === '' || $relative_picture_path === '') {
    echo 'Missing required parameters';
    exit();
}

// 取得使用者 ID
$stmt = $link->prepare("SELECT id FROM account WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$userRow = $result->fetch_assoc();
$stmt->close();

if (!$userRow) {
    echo 'User not found';
    exit();
}

$user_id = $userRow['id'];

// 取得親人 ID
$stmt = $link->prepare("SELECT id FROM relative_information WHERE relative_picture_path = ?");
$stmt->bind_param("s", $relative_picture_path);
$stmt->execute();
$result = $stmt->get_result();
$familyRow = $result->fetch_assoc();
$stmt->close();

if (!$familyRow) {
    echo 'Relative not found';
    exit();
}

$family_id = $familyRow['id'];

// 寫入聊天紀錄
$stmt = $link->prepare("
    INSERT INTO family_chat (user_id, family_id, msg, who_sent)
    VALUES (?, ?, ?, ?)
");
$stmt->bind_param("iiss", $user_id, $family_id, $msg, $username);

if ($stmt->execute()) {
    echo 'success';
} else {
    echo 'Database insert failed';
}

$stmt->close();
$link->close();
?>