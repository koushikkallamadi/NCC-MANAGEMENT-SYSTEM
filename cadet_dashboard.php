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

// Fetch Cadet Data
$stmt = $conn->prepare("SELECT u.name, u.regiment_no, u.role, un.name as unit_name, u.status 
                        FROM users u 
                        LEFT JOIN units un ON u.unit_id = un.id 
                        WHERE u.id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$cadet = $result->fetch_assoc();

if ($cadet['role'] !== 'Cadet') {
    header("Location: role_select.php");
    exit();
}

// Fetch Stats
// 1. Attendance %
$att_stmt = $conn->prepare("SELECT 
    COUNT(*) as total_days,
    SUM(CASE WHEN status = 'Present' THEN 1 ELSE 0 END) as attended_days
    FROM attendance WHERE user_id = ?");
$att_stmt->bind_param("i", $user_id);
$att_stmt->execute();
$att_stats = $att_stmt->get_result()->fetch_assoc();
$attendance_pct = ($att_stats['total_days'] > 0) ? round(($att_stats['attended_days'] / $att_stats['total_days']) * 100, 2) : 0;

// 2. Upcoming Camps (Enrolled)
$camp_stmt = $conn->prepare("SELECT COUNT(*) as count FROM enrollments e 
                             JOIN camps_events ce ON e.item_id = ce.id 
                             WHERE e.user_id = ? AND ce.type = 'Camp' AND ce.status = 'Upcoming'");
$camp_stmt->bind_param("i", $user_id);
$camp_stmt->execute();
$upcoming_camps = $camp_stmt->get_result()->fetch_assoc()['count'];

// 3. Certificates
$cert_stmt = $conn->prepare("SELECT COUNT(*) as count FROM certificates WHERE user_id = ?");
$cert_stmt->bind_param("i", $user_id);
$cert_stmt->execute();
$certificates_earned = $cert_stmt->get_result()->fetch_assoc()['count'];

// Fetch Announcements
$ann_stmt = $conn->query("SELECT * FROM announcements ORDER BY date_posted DESC LIMIT 5");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadet Dashboard - NCC</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            background-color: #f0f2f5;
            color: #333;
        }

        header {
            background-color: #0047AB;
            color: white;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .user-info h2 { margin: 0; font-size: 24px; }
        .user-info p { margin: 5px 0 0; opacity: 0.9; }

        .nav-links a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
            font-weight: bold;
            transition: 0.3s;
        }
        .nav-links a:hover { color: gold; }

        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            transition: transform 0.3s;
        }
        .stat-card:hover { transform: translateY(-5px); }

        .stat-card h3 {
            margin: 0;
            color: #666;
            font-size: 16px;
            text-transform: uppercase;
        }

        .stat-card .value {
            font-size: 36px;
            font-weight: bold;
            color: #0047AB;
            margin: 10px 0;
        }

        .stat-card .trend {
            font-size: 14px;
            font-weight: bold;
        }
        .text-success { color: #28a745; }
        .text-danger { color: #dc3545; }

        /* Content Sections */
        .main-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
        }

        .section-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }

        .section-card h2 {
            margin-top: 0;
            color: #0047AB;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        /* Announcements */
        .announcement {
            padding: 15px;
            border-left: 4px solid #0047AB;
            background: #f8f9fa;
            margin-bottom: 15px;
            border-radius: 0 8px 8px 0;
        }
        .announcement h4 { margin: 0 0 5px; color: #333; }
        .announcement p { margin: 0; font-size: 14px; color: #555; }
        .announcement span { font-size: 12px; color: #999; }

        /* Quick Links */
        .quick-links {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        .q-link {
            background: #0047AB;
            color: white;
            text-decoration: none;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            font-weight: bold;
            transition: 0.3s;
        }
        .q-link:hover { background: gold; color: black; }

        @media (max-width: 768px) {
            .main-grid { grid-template-columns: 1fr; }
            header { flex-direction: column; text-align: center; }
            .nav-links { margin-top: 15px; }
        }
    </style>
</head>
<body>

<header>
    <div class="user-info">
        <h2>Welcome, <?php echo htmlspecialchars($cadet['name']); ?></h2>
        <p>Regiment No: <?php echo htmlspecialchars($cadet['regiment_no']); ?> | Unit: <?php echo htmlspecialchars($cadet['unit_name'] ?? 'Not Assigned'); ?></p>
    </div>
    <div class="nav-links">
        <a href="cadet_dashboard.php">Dashboard</a>
        <a href="cadet_profile.php">Profile</a>
        <a href="logout.php text-danger">Logout</a>
    </div>
</header>

<div class="container">
    <div class="stats-grid">
        <div class="stat-card">
            <h3>Overall Attendance</h3>
            <div class="value"><?php echo $attendance_pct; ?>%</div>
            <div class="trend <?php echo ($attendance_pct >= 75) ? 'text-success' : 'text-danger'; ?>">
                <?php echo ($attendance_pct >= 75) ? '✓ Above Threshold' : '⚠ Below Threshold (75%)'; ?>
            </div>
        </div>
        <div class="stat-card">
            <h3>Upcoming Camps</h3>
            <div class="value"><?php echo $upcoming_camps; ?></div>
            <div class="trend text-success">Active Enrollments</div>
        </div>
        <div class="stat-card">
            <h3>Certificates</h3>
            <div class="value"><?php echo $certificates_earned; ?></div>
            <div class="trend text-success">Verified issued</div>
        </div>
    </div>

    <div class="main-grid">
        <div class="section-card">
            <h2>Recent Announcements</h2>
            <?php if ($ann_stmt && $ann_stmt->num_rows > 0): ?>
                <?php while($ann = $ann_stmt->fetch_assoc()): ?>
                    <div class="announcement">
                        <h4><?php echo htmlspecialchars($ann['title']); ?></h4>
                        <p><?php echo htmlspecialchars($ann['content']); ?></p>
                        <span>Posted on: <?php echo date('d M Y, h:i A', strtotime($ann['date_posted'])); ?></span>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No announcements at this time.</p>
            <?php endif; ?>
        </div>

        <div class="section-card">
            <h2>Quick Actions</h2>
            <div class="quick-links">
                <a href="cadet_attendance.php" class="q-link">My Attendance</a>
                <a href="cadet_camps.php" class="q-link">Camps & Events</a>
                <a href="cadet_certificates.php" class="q-link">Certificates</a>
                <a href="cadet_profile.php" class="q-link">My Profile</a>
            </div>
        </div>
    </div>
</div>

</body>
</html>
