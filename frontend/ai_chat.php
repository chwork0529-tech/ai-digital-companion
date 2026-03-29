<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: login_page.php');
    exit();
}

require_once '../backend/function.php';

$user = load_user($_SESSION['username']);

// 避免未定義變數造成 warning
$relative_picture_path = $relative_picture_path ?? '';
$videopath = $videopath ?? '';
?>
<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Chat</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/main.css">
    <link rel="stylesheet" href="./css/user.css">
    <link rel="stylesheet" href="./css/chat.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap"
        rel="stylesheet"
    >
</head>
<body>

    <div class="title">
        <p>AI Digital Companion System</p>
    </div>

    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="./user_page.php">Digital Twin</a>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown">
                <span class="navbar-toggler-icon">三</span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link" href="./user_page.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="./upload_family_information.php">Upload Relative Info</a></li>
                    <li class="nav-item"><a class="nav-link" href="./select_family_information.php">View Relative Info</a></li>
                    <li class="nav-item"><a class="nav-link" href="./ai_chat.php">AI Chat</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container chat-bg">
        <div class="container-left">
            <div id="loading" style="display: none;">
                <img src="./source/loading.gif" alt="Loading">
            </div>

            <img id="userImage" src="./images/user1.jpg" alt="Character">
        </div>

        <div class="container-right">
            <div class="wrapper">
                <div class="header">
                    <p>Virtual AI Assistant</p>
                </div>

                <div class="chat-page">
                    <div class="chats"></div>
                </div>

                <div class="chat-input">
                    <button id="recordButton" type="button">Start Voice Input</button>
                </div>
            </div>
        </div>
    </div>

    <div id="img1-path" data-value="<?php echo htmlspecialchars($relative_picture_path); ?>"></div>
    <div id="video-path" data-value="<?php echo htmlspecialchars($videopath); ?>"></div>
    <div id="user" data-value="<?php echo htmlspecialchars($user['username'] ?? ''); ?>"></div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script src="./js/home.js"></script>
    <script src="./js/script.js"></script>
    <script src="./js/recorder.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof recorder !== 'undefined' && recorder.init) {
                recorder.init('recordButton');
            }
        });
    </script>

</body>
</html>