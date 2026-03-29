<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
  <title>Register</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="./css/main.css">
  <link rel="stylesheet" href="./css/register.css">
</head>

<body>

<div class="title">
    <p>AI Digital Companion System</p>
</div>

<div class="card-bg">
    <div class="login-container">

        <form action="../backend/register.php" method="post" class="login-form">
            <h2>Register</h2>

            <?php if (isset($_GET['msg'])): ?>
                <p>Username already exists</p>
            <?php endif; ?>

            <div class="input-group">
                <label>Username:</label>
                <input type="text" name="username" required>
            </div>

            <div class="input-group">
                <label>Password:</label>
                <input type="password" name="password" required>
            </div>

            <div class="input-group">
                <label>Email:</label>
                <input type="email" name="email" required>
            </div>

            <div class="input-group">
                <label>Phone:</label>
                <input type="text" name="phone" required>
            </div>

            <div class="input-group">
                <label>Birthday:</label>
                <input type="date" name="birthday" required>
            </div>

            <div class="form-links">
                <a href="login_page.php">Login</a>
            </div>

            <button class="login-btn" type="submit">Register</button>
        </form>

    </div>
</div>

</body>
</html>