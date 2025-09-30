<?php
include("config/site.php");
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$username = $_SESSION['username'] ?? 'User';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - Fitness Club Management</title>
    <link rel="stylesheet" href="css/dashboard.css"/>

</head>
<body>

    <div class="top_head">
        <h1>Fitness Club Dashboard</h1> 
        <div class="logout">
            Welcome, <?php echo htmlspecialchars($username); ?> | <a href="server_files/logout.php">Sign Out </a>
        </div>
    </div>

<div class="sidebar">
    <a href="#">Home</a>
    <a href="#">Customer Management</a>
    <a href="#">Workout Plans</a>
    <a href="#">Diet Plans</a>
    <a href="#">Staff Management</a>
    <a href="#">Financial Reports</a>
    <a href="#">Settings</a>
</div>

<div class="content">
    <div class="card">
        <h2>Welcome to your Dashboard</h2>
        <p>Use the sidebar to navigate through your Fitness Club Management System.</p>
    </div>

    <div class="card">
        <h2>Quick Stats</h2>
        <ul>
            <li>Total Customers: <strong>150</strong></li>
            <li>Active Instructors: <strong>12</strong></li>
            <li>Pending Payments: <strong>5</strong></li>
            <li>Monthly Revenue: <strong>$8,500</strong></li>
        </ul>
    </div>

    <div class="card">
        <h2>Announcements</h2>
        <p>No new announcements.</p>
    </div>
</div>

</body>
</html>
