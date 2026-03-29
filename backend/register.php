<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../frontend/register_page.php');
    exit();
}

$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$birthday = trim($_POST['birthday'] ?? '');

if ($username === '' || $password === '' || $email === '' || $phone === '' || $birthday === '') {
    header('Location: ../frontend/register_page.php?msg=1');
    exit();
}

// 檢查帳號是否已存在
$stmt = $link->prepare("SELECT id FROM account WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    header('Location: ../frontend/register_page.php?msg=1');
    exit();
}

// 新增使用者資料
$stmt = $link->prepare("
    INSERT INTO account (username, password, email, phone, birthday)
    VALUES (?, ?, ?, ?, ?)
");
$stmt->bind_param("sssss", $username, $password, $email, $phone, $birthday);

if ($stmt->execute()) {
    $userFolder = '../frontend/family/' . $username . '/';

    if (!is_dir($userFolder)) {
        mkdir($userFolder, 0777, true);
    }

    header('Location: ../frontend/login_page.php');
    exit();
}

header('Location: ../frontend/register_page.php?msg=1');
exit();
?>