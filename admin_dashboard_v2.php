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

// Verify Admin Role
$user = $conn->query("SELECT role FROM users WHERE id = $user_id")->fetch_assoc();
if ($user['role'] !== 'Admin') {
    header("Location: role_select.php");
    exit();
}

// Fetch System Stats
$cadet_count = $conn->query("SELECT COUNT(*) as count FROM users WHERE role = 'Cadet'")->fetch_assoc()['count'];
$officer_count = $conn->query("SELECT COUNT(*) as count FROM users WHERE role = 'NCC Officer'")->fetch_assoc()['count'];
$unit_count = $conn->query("SELECT COUNT(*) as count FROM units")->fetch_assoc()['count'];
$camp_count = $conn->query("SELECT COUNT(*) as count FROM camps_events WHERE type = 'Camp'")->fetch_assoc()['count'];

// System-wide Attendance %
$att_stats = $conn->query("SELECT COUNT(*) as total, SUM(CASE WHEN status = 'Present' THEN 1 ELSE 0 END) as present FROM attendance")->fetch_assoc();
$sys_attendance = ($att_stats['total'] > 0) ? round(($att_stats['present'] / $att_stats['total']) * 100, 2) : 100;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - NCC System</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; margin: 0; background-color: #f0f2f5; }
        header { background-color: #1a202c; color: white; padding: 20px 40px; display: flex; justify-content: space-between; align-items: center; }
        .nav-links a { color: #cbd5e0; text-decoration: none; margin-left: 20px; font-weight: bold; }
        .nav-links a:hover { color: white; }
        
        .container { max-width: 1200px; margin: 30px auto; padding: 0 20px; }
        
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .stat-card { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); text-align: center; }
        .stat-card h3 { margin: 0; color: #718096; font-size: 14px; text-transform: uppercase; letter-spacing: 1px; }
        .stat-card .value { font-size: 36px; font-weight: 800; color: #2d3748; margin: 10px 0; }
        
        .main-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 30px; }
        .card { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .card h2 { margin-top: 0; color: #2d3748; border-bottom: 2px solid #edf2f7; padding-bottom: 15px; margin-bottom: 20px; }
        
        .action-list { list-style: none; padding: 0; }
        .action-list li { margin-bottom: 10px; }
        .btn-action { display: block; background: #edf2f7; color: #2d3748; padding: 15px; border-radius: 8px; text-decoration: none; font-weight: bold; transition: 0.2s; border: 1px solid #e2e8f0; }
        .btn-action:hover { background: #E2E8F0; border-color: #cbd5e0; }
        
        .unit-list { width: 100%; border-collapse: collapse; }
        .unit-list td { padding: 12px 0; border-bottom: 1px solid #edf2f7; }
        .unit-list tr:last-child td { border-bottom: none; }
    </style>
</head>
<body>

<header>
    <h2 style="margin:0;">NCC System Administration</h2>
    <div class="nav-links">
        <a href="admin_dashboard_v2.php">Dashboard</a>
        <a href="admin_settings.php">Settings</a>
        <a href="logout.php">Logout</a>
    </div>
</header>

<div class="container">
    <div class="stats-grid">
        <div class="stat-card">
            <h3>Total Cadets</h3>
            <div class="value"><?php echo $cadet_count; ?></div>
        </div>
        <div class="stat-card">
            <h3>NCC Officers</h3>
            <div class="value"><?php echo $officer_count; ?></div>
        </div>
        <div class="stat-card">
            <h3>Units</h3>
            <div class="value"><?php echo $unit_count; ?></div>
        </div>
        <div class="stat-card">
            <h3>Camps</h3>
            <div class="value"><?php echo $camp_count; ?></div>
        </div>
        <div class="stat-card">
            <h3>Attendance</h3>
            <div class="value" style="color: #2b6cb0;"><?php echo $sys_attendance; ?>%</div>
        </div>
    </div>

    <div class="main-grid">
        <div class="card">
            <h2>Administrative Management</h2>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <a href="admin_officers.php" class="btn-action">👨‍✈️ Manage Officers</a>
                <a href="admin_units.php" class="btn-action">🏢 Manage Units</a>
                <a href="admin_cadets_global.php" class="btn-action">🎖️ Global Cadet List</a>
                <a href="admin_camps_global.php" class="btn-action">⛺ Global Camp Records</a>
                <a href="admin_reports.php" class="btn-action">📊 System Reports</a>
                <a href="admin_settings.php" class="btn-action">⚙️ System Settings</a>
            </div>
            
            <div style="margin-top: 40px;">
                <h3>Recent System Alerts</h3>
                <p style="color: #718096; font-size: 14px;">✓ Database backup completed successfully.</p>
                <p style="color: #e53e3e; font-size: 14px;">⚠ 5 New Officer accounts pending unit assignment.</p>
            </div>
        </div>

        <div class="card">
            <h2>Active Units</h2>
            <table class="unit-list">
                <?php
                $units = $conn->query("SELECT u.*, (SELECT COUNT(*) FROM users where unit_id = u.id AND role='Cadet') as cadets FROM units u");
                while($u = $units->fetch_assoc()):
                ?>
                <tr>
                    <td>
                        <strong><?php echo htmlspecialchars($u['name']); ?></strong><br>
                        <small style="color: #718096;"><?php echo $u['type']; ?> Unit | <?php echo $u['cadets']; ?> Cadets</small>
                    </td>
                    <td style="text-align: right;">
                        <a href="admin_units.php?id=<?php echo $u['id']; ?>" style="color: #2b6cb0; text-decoration: none; font-size: 13px;">Manage</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
            <a href="admin_units.php" style="display: block; margin-top: 20px; text-align: center; color: #2b6cb0; text-decoration: none; font-weight: bold;">+ Add New Unit</a>
        </div>
    </div>
</div>

</body>
</html>
