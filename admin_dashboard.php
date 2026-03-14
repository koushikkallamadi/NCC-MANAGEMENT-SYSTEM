<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Simple role check if stored in session
// if ($_SESSION['role'] !== 'Admin') {
//     header("Location: role_select.php");
//     exit();
// }

// Connect to database to fetch stats if needed
require_once 'db.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$cadet_count_query = "SELECT COUNT(*) as count FROM cadets";
$cadet_count_result = $conn->query($cadet_count_query);
$cadet_count = $cadet_count_result->fetch_assoc()['count'];

$event_count_query = "SELECT COUNT(*) as count FROM events"; // corrected table name from events1 to events
$event_count_result = $conn->query($event_count_query);
$event_count = ($event_count_result) ? $event_count_result->fetch_assoc()['count'] : 0;

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - NCC Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        nav {
            background-color: #0047AB;
            padding: 15px;
            text-align: center;
        }
        nav a {
            color: white;
            text-decoration: none;
            padding: 14px 20px;
            font-size: 18px;
            font-weight: bold;
            display: inline-block;
        }
        nav a:hover {
            background-color: gold;
            color: black;
            border-radius: 5px;
        }
        header {
            background-color: #0047AB;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .container {
            padding: 20px;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }
        .card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            width: 250px;
            text-align: center;
        }
        .card h3 {
            margin-top: 0;
            color: #0047AB;
        }
        .card p {
            font-size: 24px;
            font-weight: bold;
        }
        .actions {
            margin-top: 20px;
            text-align: center;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #0047AB;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 5px;
        }
        .btn:hover {
            background-color: gold;
            color: black;
        }
    </style>
</head>
<body>
    <nav>
        <a href="index.php">Home</a>
        <a href="cadet_management.php">Cadet Management</a>
        <a href="attendance.php">Attendance</a>
        <a href="events.php">Events</a>
        <a href="gallery page.html">Gallery</a>
        <a href="role_select.php">Switch Role</a>
        <a href="signup.php">Logout</a>
    </nav>

    <header>
        <h1>Admin Dashboard</h1>
    </header>

    <div class="container">
        <div class="card">
            <h3>Total Cadets</h3>
            <p><?php echo $cadet_count; ?></p>
        </div>
        <div class="card">
            <h3>Upcoming Events</h3>
            <p><?php echo $event_count; ?></p>
        </div>
    </div>

    <div class="actions">
        <h2>Quick Actions</h2>
        <a href="cadet_management.php" class="btn">Manage Cadets</a>
        <a href="attendance.php" class="btn">View Attendance</a>
        <a href="events.php" class="btn">Manage Events</a>
    </div>

</body>
</html>
