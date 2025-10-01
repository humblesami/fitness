<?php
session_start();
include("config/site.php"); // include your site config file 
// $_SESSION['any_key'] = '1000';
// session_destroy();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Fitness Club Management System</title>
    <link rel="stylesheet" href="css/site.css"/>
</head>
<body>

<div class="top_head">
    <h1>Fitness Club Management System</h1>
    <label>Value from session => </label>
    <?php echo $_SESSION['any_key'] ?? ''; ?>
</div>

<section class="page-content">
    <h2>Welcome to Your Fitness Club</h2>
    <p>Manage your members, workouts, diets, staff, and finances efficiently with our all-in-one Fitness Club Management System.</p>
    <a href="login.php">Login</a>
    <a href="register.php">Register</a>
</section>

<footer>
    &copy; <?php echo date("Y"); ?> Fitness Club Management System. All Rights Reserved.
</footer>

</body>
</html>
