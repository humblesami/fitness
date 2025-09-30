<?php
// $_POST
// $_SERVER
// $_SESSION
include("config/site.php"); // include your site config file ?>
<!DOCTYPE html>
<html>
<head>
    <title>Fitness Club Management System</title>
    <link rel="stylesheet" href="<?php echo $base_url; ?>/css/site.css"/>
</head>
<body>

<div class="top_head">
    <h1>Fitness Club Management System</h1>
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
