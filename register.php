<?php
session_start();
include("config/site.php"); // include your site config file
include("config/db.php"); // include your DB connection file

// Generate random text CAPTCHA

function initRegistration ($conn, $base_url){

    function generateCaptcha() {
        return substr(str_shuffle("ABCDEFGHJKLMNPQRSTUVWXYZ23456789"), 0, 6);
    }

    // Ensure CAPTCHA is always set
    if (!isset($_SESSION['captcha_text']) || empty($_SESSION['captcha_text'])) {
        $_SESSION['captcha_text'] = generateCaptcha();
    }

    // Handle AJAX request for CAPTCHA refresh
    if (isset($_GET['action']) && $_GET['action'] === 'refresh_captcha') {
        $_SESSION['captcha_text'] = generateCaptcha();
        echo $_SESSION['captcha_text'];
        exit;
    }

    // Form handling
    $message = "";
    if (isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
        $name           = trim($_POST['name']);
        $surname        = trim($_POST['surname']);
        $username       = trim($_POST['username']);
        $password       = trim($_POST['password']);
        $confirm_pass   = trim($_POST['confirm_password']);
        $email          = trim($_POST['email']);
        $confirm_email  = trim($_POST['confirm_email']);
        $language       = trim($_POST['language']);
        $captcha        = trim($_POST['captcha']);
        $terms          = isset($_POST['terms']) ? true : false;

        // Empty field check
        if (empty($name) || empty($surname) || empty($username) || empty($password) || empty($confirm_pass) || empty($email) || empty($confirm_email) || empty($language) || empty($captcha)) {
            $message = "<p style='color:red;'>All fields are required</p>";
            $_SESSION['captcha_text'] = generateCaptcha(); // regenerate CAPTCHA on fail
        }
        // Email match check
        elseif ($email !== $confirm_email) {
            $message = "<p style='color:red;'>Emails do not match</p>";
            $_SESSION['captcha_text'] = generateCaptcha();
        }
        // Password match check
        elseif ($password !== $confirm_pass) {
            $message = "<p style='color:red;'>Passwords do not match</p>";
            $_SESSION['captcha_text'] = generateCaptcha();
        }
        // CAPTCHA check
        elseif ($captcha !== $_SESSION['captcha_text']) {
            $message = "<p style='color:red;'>Invalid CAPTCHA</p>";
            $_SESSION['captcha_text'] = generateCaptcha();
        }
        // Terms check
        elseif (!$terms) {
            $message = "<p style='color:red;'>You must agree to the terms</p>";
            $_SESSION['captcha_text'] = generateCaptcha();
        }
        else {
            // Insert into DB
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            $sql = "INSERT INTO users (name, surname, username, email, password, preferred_language) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssss", $name, $surname, $username, $email, $hashed_password, $language);

            if ($stmt->execute()) {
                $message = "<p style='color:green;'>Registration successful!</p>";
                unset($_SESSION['captcha_text']); // Reset CAPTCHA after success
            } else {
                $message = "<p style='color:red;'>Error: " . $stmt->error . "</p>";
                $_SESSION['captcha_text'] = generateCaptcha();
            }
            $stmt->close();
            header('Location: '.$base_url);
        }
    }
}
initRegistration($conn, $base_url);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Registration</title>
    <link rel="stylesheet" href="<?php echo $base_url; ?>/css/register.css"/>
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
    <div class="message"><?php echo $message; ?></div>
    <form method="post">
        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" required>
        </div>
        <div class="form-group">
            <label>Surname</label>
            <input type="text" name="surname" required>
        </div>
        <div class="form-group">
            <label>User Name</label>
            <input type="text" name="username" required>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>
        <div class="form-group">
            <label>Confirm Password</label>
            <input type="password" name="confirm_password" required>
        </div>
        <div class="form-group">
            <label>Email Address</label>
            <input type="email" name="email" required>
        </div>
        <div class="form-group">
            <label>Confirm Email Address</label>
            <input type="email" name="confirm_email" required>
        </div>
        <div class="form-group">
            <label>Preferred Language</label>
            <select name="language" required>
                <option value="">Select Language</option>
                <option value="English">English</option>
                <option value="Urdu">Urdu</option>
                <option value="Spanish">Spanish</option>
                <option value="French">French</option>
            </select>
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
        <div class="form-group terms">
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
