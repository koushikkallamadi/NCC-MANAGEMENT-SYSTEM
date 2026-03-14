<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'db.php';

$user_id = $_SESSION['user_id'];

// Verify Admin
$user = $conn->query("SELECT role FROM users WHERE id = $user_id")->fetch_assoc();
if ($user['role'] !== 'Admin') exit("Unauthorized");

// Handle Password Change
if (isset($_POST['change_pass'])) {
    $new_pass = trim($_POST['new_pass']);
    if (!empty($new_pass)) {
        $hashed = password_hash($new_pass, PASSWORD_DEFAULT);
        $conn->query("UPDATE users SET password = '$hashed' WHERE id = $user_id");
        $msg = "Admin password updated successfully!";
    }
}

// Handle System Config (Mock)
if (isset($_POST['update_conf'])) {
    $msg = "System configurations updated!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Settings - Admin</title>
    <style>
        body { font-family: sans-serif; background: #f0f2f5; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: auto; }
        .card { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); margin-bottom: 25px; }
        h2 { margin-top: 0; color: #1a202c; border-bottom: 2px solid #edf2f7; padding-bottom: 10px; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; color: #4a5568; }
        input { width: 100%; padding: 10px; border: 1px solid #e2e8f0; border-radius: 6px; box-sizing: border-box; }
        .btn { background: #2d3748; color: white; padding: 12px; border: none; border-radius: 6px; cursor: pointer; width: 100%; font-weight: bold; }
        .back { display: block; margin-bottom: 20px; text-decoration: none; color: #2b6cb0; font-weight: bold; }
    </style>
</head>
<body>

<div class="container">
    <a href="admin_dashboard_v2.php" class="back">← Back to Dashboard</a>
    
    <?php if (isset($msg)) echo "<div style='color:green; font-weight:bold; margin-bottom:15px;'>$msg</div>"; ?>

    <div class="card">
        <h2>Appearance & Logo</h2>
        <form method="POST">
            <div class="form-group">
                <label>System Name</label>
                <input type="text" name="sys_name" value="NCC Management System">
            </div>
            <div class="form-group">
                <label>Attendance Threshold (%)</label>
                <input type="number" name="threshold" value="75">
            </div>
            <button type="submit" name="update_conf" class="btn">Save Configuration</button>
        </form>
    </div>

    <div class="card">
        <h2>Security Settings</h2>
        <form method="POST">
            <div class="form-group">
                <label>Change Admin Password</label>
                <input type="password" name="new_pass" placeholder="Enter new password">
            </div>
            <button type="submit" name="change_pass" class="btn" style="background: #e53e3e;">Update Password</button>
        </form>
    </div>
</div>

</body>
</html>
