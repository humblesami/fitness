<?php
session_start();
include("config/db.php"); // include your DB connection file
include("server_files/sign_up.php"); // include your php code file
$message = registerUsrer($conn);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Registration</title>
    <link rel="stylesheet" href="css/register.css"/>
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
    <h2>Registration Form</h2>
    <div class="message error"><?php echo $message; ?></div>
    <form method="post">
        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" value="<?php echo $_POST['name'] ?? '' ?>" required>
        </div>
        <div class="form-group">
            <label>User Name</label>
            <input type="text" name="username" value="<?php echo $_POST['username'] ?? '' ?>" required>
        </div>
        <div class="form-group">
            <label>Email Address</label>
            <input type="email" name="email" value="<?php echo $_POST['email'] ?? '' ?>" required>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>
        <div class="form-group">
            <label>Confirm Password</label>
            <input type="password" name="confirm_password" required>
        </div>
        

        <!-- CAPTCHA -->
        <div class="form-group captcha-box">
            <span class="captcha-text" id="captchaText">
                <?php echo isset($_SESSION['captcha_text']) ? $_SESSION['captcha_text'] : generateCaptcha(); ?>
            </span>
            <input type="text" name="captcha" placeholder="Enter CAPTCHA" required>
            <span class="reload" onclick="refreshCaptcha()">🔄</span>
        </div>
        <label style="font-size:0.85rem; color:#555; display:block; margin-bottom:10px;">
            Please enter the text shown above
        </label>

        <!-- Terms Checkbox -->
        <div class="">
            <input type="checkbox" id="terms" name="terms" required>
            <label for="terms">
                I agree with the <a href="#">terms of use</a> and I am aware that I am obliged to provide accurate data
            </label>
        </div>

        <button type="submit" class="btn">Register</button>
    </form>
<!-- Back to Home Button -->
<div class="back-btn-container">
    <a href="index.php" class="back-btn">← Back to Home</a>
</div>

</div>

</body>
</html>
