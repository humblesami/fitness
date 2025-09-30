<?php
session_start();
include("config/db.php");
include("server_files/sign_in.php"); // include your php code file
try {
    $message = loginUser($conn);
} catch(Exception $ex){
    $message = $ex->getMessage();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="css/login.css"/>
    <script>
        function refreshCaptcha() {
            fetch("?action=refresh_captcha")
                .then(response => response.text())
                .then(data => {
                    document.getElementById('captchaText').innerText = data;
                });
        }
    </script>
</head>
<body>
<div class="container">
    <h2>Login</h2>
    <div class="message"><?php echo $message; ?></div>
    <form method="post">
        <div class="form-group">
            <label>Username or Email</label>
            <input type="text" name="user_identity" required>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>

        <!-- CAPTCHA -->
        <div class="form-group captcha-box">
            <span class="captcha-text" id="captchaText">
                <?php echo isset($_SESSION['login_captcha']) ? $_SESSION['login_captcha'] : generateCaptcha(); ?>
            </span>
            <input type="text" name="captcha" placeholder="Enter CAPTCHA" required>
            <span class="reload" onclick="refreshCaptcha()">🔄</span>
        </div>

        <button type="submit" class="btn">Login</button>
    </form>
<!-- Back to Home Button -->
<div class="back-btn-container">
    <a href="index.php" class="back-btn">← Back to Home</a>
</div>

    <div class="link">
        <a href="register.php">Don't have an account? Register here</a>
    </div>
</div>
</body>
</html>
