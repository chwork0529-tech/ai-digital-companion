<?php
session_start();

// 如果已登入，直接跳首頁
if (isset($_SESSION['username'])) {
    header('Location: user_page.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="./css/main.css">
  <link rel="stylesheet" href="./css/login.css">
</head>

<body>

<div class="title">
    <p>AI Digital Companion System</p>
</div>

<div class="card-bg">
    <div class="login-container">

        <form action="../backend/login.php" method="post" class="login-form">
            <h2>Login</h2>

            <?php if (isset($_SESSION['login_msg'])): ?>
                <p><?php echo $_SESSION['login_msg']; ?></p>
                <?php unset($_SESSION['login_msg']); ?>
            <?php endif; ?>

            <div class="input-group">
                <label>Username:</label>
                <input type="text" name="username" required>
            </div>

            <div class="input-group">
                <label>Password:</label>
                <input type="password" name="password" required>
            </div>

            <div class="form-links">
                <a href="register_page.php">Register</a>
            </div>

            <button class="login-btn" type="submit">Login</button>
        </form>

    </div>
</div>

</body>
</html>