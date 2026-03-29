<?php
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../frontend/login_page.php');
    exit();
}

$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');

if ($username === '' || $password === '') {
    $_SESSION['login_msg'] = '請輸入帳號與密碼';
    header('Location: ../frontend/login_page.php');
    exit();
}

// 使用預處理語句避免 SQL Injection
$stmt = $link->prepare("SELECT username, password, email, phone, position FROM account WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    // 目前沿用你原本的明碼比對方式
    // 如果之後要升級，可以改成 password_hash / password_verify
    if ($password === $row['password']) {
        unset($_SESSION['login_msg']);

        $_SESSION['username'] = $row['username'];
        $_SESSION['email'] = $row['email'];
        $_SESSION['phone'] = $row['phone'];
        $_SESSION['position'] = $row['position'];

        header('Location: ../frontend/user_page.php');
        exit();
    }
}

$_SESSION['login_msg'] = '帳號或密碼錯誤';
header('Location: ../frontend/login_page.php');
exit();
?>