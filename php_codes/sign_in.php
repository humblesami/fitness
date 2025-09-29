<?php 
function loginUser($conn, $base_url, $message=''){
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
    return $message;
}
?>