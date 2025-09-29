<?php
session_start();
include("config/site.php"); // include your site config file
include("config/db.php"); // include your DB connection file
include("php_codes/sign_in.php"); // include your php code file
$message = loginUser($conn, $base_url,  $message);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="<?php echo $base_url; ?>/css/login.css"/>
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
            <input type="text" name="username_or_email" required>
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
        <a href="<?php echo $base_url; ?>/register.php">Don't have an account? Register here</a>
    </div>
</div>
</body>
</html>
