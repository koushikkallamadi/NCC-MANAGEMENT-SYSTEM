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
    <title>Reports - NCC Officer</title>
    <style>
        body { font-family: sans-serif; text-align: center; padding: 50px; background: #f4f4f4; }
        .card { background: white; padding: 40px; border-radius: 12px; display: inline-block; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .back { display: block; margin-top: 20px; color: #0047AB; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>
    <div class="card">
        <h1>Unit Performance Reports</h1>
        <p>This module will allow you to generate <strong>Monthly Attendance PDF</strong> and <strong>Camp Participation Reports</strong>.</p>
        <p style="color: #666;">(Integration with PDF library required for production export)</p>
        <a href="officer_dashboard.php" class="back">← Back to Dashboard</a>
    </div>
</body>
</html>
