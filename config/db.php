<?php
$host = "localhost";
$user = "root";   // your MySQL username
$pass = "123";       // your MySQL password

$db   = "fitness_db";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
