<?php
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
    <style>
        body { font-family: Arial, sans-serif; margin:0; background: #f4f4f4; }

        /* Header */
        header {
            background: #007BFF;
            color: #fff;
            padding: 20px 30px;
            position: relative;
        }
        .header-container {
            display: flex;
            justify-content: center; /* center horizontally */
            align-items: center;     /* center vertically */
            position: relative;
        }
        header h1 {
            margin: 0;
            font-size: 24px;
            text-align: center;
        }
        header .logout {
            position: absolute;
            right: 30px;
            top: 50%;
            transform: translateY(-50%);
        }
        header .logout a {
            color: #fff;
            text-decoration: none;
            background: #0056b3;
            padding: 6px 12px;
            border-radius: 5px;
        }
        header .logout a:hover { background: #003f7f; }

        /* Sidebar */
        .sidebar {
            width: 220px;
            background: #333;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 80px; /* leave space for header */
        }
        .sidebar a {
            display: block;
            color: #fff;
            padding: 15px 20px;
            text-decoration: none;
            font-weight: bold;
        }
        .sidebar a:hover { background: #444; }

        /* Content */
        .content {
            margin-left: 220px;
            padding: 30px;
        }
        .card {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
            margin-bottom: 20px;
        }
        .card h2 { margin-top: 0; }

        /* Responsive */
        @media(max-width:768px) {
            .sidebar { width: 100%; height: auto; position: relative; padding-top: 20px; }
            .content { margin-left: 0; }
            header .logout { position: static; transform: none; margin-top: 10px; text-align: center; }
        }
    </style>
</head>
<body>

<header>
    <div class="header-container">
        <h1>Fitness Club Dashboard</h1>
        <div class="logout">
            Welcome, <?php echo htmlspecialchars($username); ?> | <a href="logout.php">Logout</a>
        </div>
    </div>
</header>

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
