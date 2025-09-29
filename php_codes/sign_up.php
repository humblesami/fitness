<?php
function registerUsrer ($conn, $base_url,  $message){

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
    return $message;
}
?>