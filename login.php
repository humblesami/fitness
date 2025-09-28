<?php
session_start();
include("config/db.php"); // include your DB connection file

// Generate random text CAPTCHA
function generateCaptcha() {
    return substr(str_shuffle("ABCDEFGHJKLMNPQRSTUVWXYZ23456789"), 0, 6);
}

// Ensure CAPTCHA is always set
if (!isset($_SESSION['login_captcha']) || empty($_SESSION['login_captcha'])) {
    $_SESSION['login_captcha'] = generateCaptcha();
}

// Handle AJAX request for CAPTCHA refresh
if (isset($_GET['action']) && $_GET['action'] === 'refresh_captcha') {
    $_SESSION['login_captcha'] = generateCaptcha();
    echo $_SESSION['login_captcha'];
    exit;
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username_or_email = trim($_POST['username_or_email']);
    $password = trim($_POST['password']);
    $captcha = trim($_POST['captcha']);

    // Empty field check
    if (empty($username_or_email) || empty($password) || empty($captcha)) {
        $message = "<p style='color:red;'>All fields are required</p>";
        $_SESSION['login_captcha'] = generateCaptcha();
    }
    // CAPTCHA check
    elseif ($captcha !== $_SESSION['login_captcha']) {
        $message = "<p style='color:red;'>Invalid CAPTCHA</p>";
        $_SESSION['login_captcha'] = generateCaptcha();
    } else {
        // Fetch user from database
        $sql = "SELECT id, username, email, password FROM users WHERE username=? OR email=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $username_or_email, $username_or_email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            // Verify password
            if (password_verify($password, $user['password'])) {
                // Login successful
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                unset($_SESSION['login_captcha']); // remove CAPTCHA after success
                header("Location: dashboard.php"); // Redirect to dashboard
                exit;
            } else {
                $message = "<p style='color:red;'>Incorrect password</p>";
                $_SESSION['login_captcha'] = generateCaptcha();
            }
        } else {
            $message = "<p style='color:red;'>User not found</p>";
            $_SESSION['login_captcha'] = generateCaptcha();
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
/* General Body */
body {
    font-family: Arial, sans-serif;
    background: #f4f4f4;
}

/* Container for forms */
.container {
    width: 400px;
    margin: 60px auto;
    background: #fff;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0px 2px 10px rgba(0,0,0,0.2);
}

/* Headings */
h2 {
    text-align: center;
    margin-bottom: 20px;
}

/* Form Groups */
.form-group {
    margin-bottom: 15px;
}

.form-group label {
    font-weight: bold;
    display: block;
    margin-bottom: 5px;
}

.form-group input {
    width: 100%;
    padding: 8px;
    border-radius: 6px;
    border: 1px solid #ccc;
}

/* CAPTCHA Styles */
.captcha-box {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 10px;
}

.captcha-text {
    font-weight: bold;
    font-size: 20px;
    background: #333;
    color: #fff;
    padding: 6px 12px;
    border-radius: 6px;
    letter-spacing: 3px;
}

.reload {
    cursor: pointer;
    font-size: 18px;
    color: #007BFF;
    text-decoration: none;
}

.reload:hover {
    color: #0056b3;
}

/* Buttons */
.btn {
    width: 100%;
    background: #007BFF;
    color: #fff;
    padding: 10px;
    border: none;
    font-size: 16px;
    border-radius: 6px;
    cursor: pointer;
}

.btn:hover {
    background: #0056b3;
}

/* Messages */
.message {
    text-align: center;
    margin-bottom: 15px;
    font-weight: bold;
}

/* Links */
.link {
    text-align: center;
    margin-top: 15px;
}

/* ------------------------------
   Back to Home Button Styles
--------------------------------*/
.back-btn-container {
    text-align: center;
    margin-top: 20px;
}

.back-btn {
    display: inline-block;
    background: #6c757d;
    color: #fff;
    padding: 8px 18px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: bold;
    transition: background 0.3s ease;
}

.back-btn:hover {
    background: #5a6268;
}

    </style>
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
        <a href="register.php">Don't have an account? Register here</a>
    </div>
</div>
</body>
</html>
