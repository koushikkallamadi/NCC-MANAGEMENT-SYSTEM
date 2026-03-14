<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'db.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];

// Fetch Officer Data
$stmt = $conn->prepare("SELECT u.*, un.name as unit_name FROM users u 
                        LEFT JOIN units un ON u.unit_id = un.id 
                        WHERE u.id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$officer = $stmt->get_result()->fetch_assoc();

if ($officer['role'] !== 'NCC Officer') {
    header("Location: role_select.php");
    exit();
}

$unit_id = $officer['unit_id'];

// Fetch Stats
// 1. Total Cadets
$cadet_count_query = $unit_id ? "SELECT COUNT(*) as count FROM users WHERE role = 'Cadet' AND unit_id = $unit_id" : "SELECT COUNT(*) as count FROM users WHERE role = 'Cadet'";
$total_cadets = $conn->query($cadet_count_query)->fetch_assoc()['count'];

// 2. Pending Approvals
$pending_query = $unit_id ? "SELECT COUNT(*) as count FROM users WHERE role = 'Cadet' AND status = 'Pending' AND unit_id = $unit_id" : "SELECT COUNT(*) as count FROM users WHERE role = 'Cadet' AND status = 'Pending'";
$pending_approvals = $conn->query($pending_query)->fetch_assoc()['count'];

// 3. Today's Attendance (Simplified: count of 'Present' today)
$today = date('Y-m-d');
$today_att_query = $unit_id ? 
    "SELECT COUNT(*) as count FROM attendance a JOIN users u ON a.user_id = u.id WHERE a.date = '$today' AND a.status = 'Present' AND u.unit_id = $unit_id" :
    "SELECT COUNT(*) as count FROM attendance WHERE date = '$today' AND status = 'Present'";
$today_present = $conn->query($today_att_query)->fetch_assoc()['count'];

// 4. Upcoming Camps
$upcoming_camps = $conn->query("SELECT COUNT(*) as count FROM camps_events WHERE type = 'Camp' AND status = 'Upcoming'")->fetch_assoc()['count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Officer Dashboard - NCC</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; margin: 0; background-color: #f8f9fa; }
        header { background-color: #0047AB; color: white; padding: 20px 40px; display: flex; justify-content: space-between; align-items: center; }
        .nav-links a { color: white; text-decoration: none; margin-left: 20px; font-weight: bold; }
        .container { max-width: 1200px; margin: 30px auto; padding: 0 20px; }
        
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .stat-card { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); border-top: 4px solid #0047AB; }
        .stat-card h3 { margin: 0; color: #666; font-size: 14px; text-transform: uppercase; }
        .stat-card .value { font-size: 32px; font-weight: bold; color: #0047AB; margin: 10px 0; }
        
        .main-content { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; }
        .card { background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        .card h2 { margin-top: 0; color: #0047AB; border-bottom: 2px solid #eee; padding-bottom: 10px; }
        
        .list-item { padding: 12px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; }
        .list-item:last-child { border-bottom: none; }
        .btn-small { background: #0047AB; color: white; padding: 5px 12px; border-radius: 4px; text-decoration: none; font-size: 13px; }
        .btn-small:hover { background: gold; color: black; }
        
        .action-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
        .action-btn { background: #eef2f7; color: #0047AB; padding: 20px; text-align: center; border-radius: 8px; text-decoration: none; font-weight: bold; transition: 0.3s; }
        .action-btn:hover { background: #0047AB; color: white; }
    </style>
</head>
<body>

<header>
    <div>
        <h2 style="margin:0;">Officer Dashboard</h2>
        <p style="margin:5px 0 0; opacity:0.8; font-size:14px;">Welcome, <?php echo htmlspecialchars($officer['name']); ?> | Unit: <?php echo htmlspecialchars($officer['unit_name'] ?? 'General'); ?></p>
    </div>
    <div class="nav-links">
        <a href="officer_dashboard.php">Home</a>
        <a href="logout.php" style="color: #ff4d4d;">Logout</a>
    </div>
</header>

<div class="container">
    <div class="stats-grid">
        <div class="stat-card">
            <h3>Total Cadets</h3>
            <div class="value"><?php echo $total_cadets; ?></div>
        </div>
        <div class="stat-card">
            <h3>Pending Approval</h3>
            <div class="value" style="color: #dc3545;"><?php echo $pending_approvals; ?></div>
        </div>
        <div class="stat-card">
            <h3>Present Today</h3>
            <div class="value"><?php echo $today_present; ?></div>
        </div>
        <div class="stat-card">
            <h3>Upcoming Camps</h3>
            <div class="value"><?php echo $upcoming_camps; ?></div>
        </div>
    </div>

    <div class="main-content">
        <div class="card">
            <h2>Management Tools</h2>
            <div class="action-grid">
                <a href="officer_cadets.php" class="action-btn">Cadet Management</a>
                <a href="officer_mark_attendance.php" class="action-btn">Mark Attendance</a>
                <a href="officer_camps.php" class="action-btn">Camp & Event Mgmt</a>
                <a href="officer_certificates.php" class="action-btn">Issue Certificates</a>
                <a href="officer_announcements.php" class="action-btn">Post Announcement</a>
                <a href="officer_reports.php" class="action-btn">Generate Reports</a>
            </div>
        </div>

        <div class="card">
            <h2>Recent Activity</h2>
            <div class="list-item">
                <span>New Cadet Registration: Arjun Kumar</span>
                <a href="officer_cadets.php" class="btn-small">Review</a>
            </div>
            <div class="list-item">
                <span>Camp Enrolling: CATC 2026</span>
                <a href="officer_camps.php" class="btn-small">View</a>
            </div>
            <div class="list-item">
                <span>Parade Attendance: <?php echo date('d M'); ?></span>
                <a href="officer_mark_attendance.php" class="btn-small">Update</a>
            </div>
        </div>
    </div>
</div>

</body>
</html>
