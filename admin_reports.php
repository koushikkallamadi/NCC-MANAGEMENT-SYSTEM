<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>System Reports - Admin</title>
    <style>
        body { font-family: sans-serif; text-align: center; padding: 50px; background: #f0f2f5; }
        .card { background: white; padding: 40px; border-radius: 12px; display: inline-block; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .back { display: block; margin-top: 20px; color: #2b6cb0; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>
    <div class="card">
        <h1>Global Analytics & Reports</h1>
        <p>Generate system-wide metrics: <strong>Attendance Trends</strong>, <strong>Unit Benchmarking</strong>, and <strong>Certificate Statistics</strong>.</p>
        <p style="color: #718096;">(Advanced data visualization module pending integration)</p>
        <a href="admin_dashboard_v2.php" class="back">← Back to Dashboard</a>
    </div>
</body>
</html>
