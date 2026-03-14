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

// Get overall stats
$att_stmt = $conn->prepare("SELECT 
    COUNT(*) as total_days,
    SUM(CASE WHEN status = 'Present' THEN 1 ELSE 0 END) as attended_days
    FROM attendance WHERE user_id = ?");
$att_stmt->bind_param("i", $user_id);
$att_stmt->execute();
$att_stats = $att_stmt->get_result()->fetch_assoc();

$total_days = $att_stats['total_days'];
$attended_days = $att_stats['attended_days'];
$attendance_pct = ($total_days > 0) ? round(($attended_days / $total_days) * 100, 2) : 0;

// Get monthly records (simplified for now)
$records_stmt = $conn->prepare("SELECT date, status FROM attendance WHERE user_id = ? ORDER BY date DESC");
$records_stmt->bind_param("i", $user_id);
$records_stmt->execute();
$records = $records_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Attendance - NCC</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; }
        header { background-color: #0047AB; color: white; padding: 20px; text-align: center; }
        .container { max-width: 800px; margin: 30px auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .stats-banner { display: flex; justify-content: space-around; background: #eef2f7; padding: 20px; border-radius: 8px; margin-bottom: 30px; }
        .stat-item { text-align: center; }
        .stat-item h3 { margin: 0; color: #666; font-size: 14px; }
        .stat-item .value { font-size: 24px; font-weight: bold; color: #0047AB; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #0047AB; color: white; }
        .status-present { color: #28a745; font-weight: bold; }
        .status-absent { color: #dc3545; font-weight: bold; }
        
        .alert { padding: 15px; border-radius: 5px; margin-bottom: 20px; text-align: center; font-weight: bold; }
        .alert-danger { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        
        .back-btn { display: inline-block; margin-bottom: 20px; color: #0047AB; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>

<header>
    <h1>Attendance Record</h1>
</header>

<div class="container">
    <a href="cadet_dashboard.php" class="back-btn">← Back to Dashboard</a>

    <?php if ($total_days > 0 && $attendance_pct < 75): ?>
        <div class="alert alert-danger">
            ⚠️ Warning: Your attendance is <?php echo $attendance_pct; ?>%. It must be at least 75%.
        </div>
    <?php elseif ($total_days > 0): ?>
        <div class="alert alert-success">
            ✅ Good job! Your attendance is <?php echo $attendance_pct; ?>%.
        </div>
    <?php endif; ?>

    <div class="stats-banner">
        <div class="stat-item">
            <h3>Total Parades</h3>
            <div class="value"><?php echo $total_days; ?></div>
        </div>
        <div class="stat-item">
            <h3>Attended</h3>
            <div class="value"><?php echo $attended_days; ?></div>
        </div>
        <div class="stat-item">
            <h3>Percentage</h3>
            <div class="value"><?php echo $attendance_pct; ?>%</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($records->num_rows > 0): ?>
                <?php while($row = $records->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo date('d M Y', strtotime($row['date'])); ?></td>
                        <td class="<?php echo ($row['status'] == 'Present') ? 'status-present' : 'status-absent'; ?>">
                            <?php echo $row['status']; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="2" style="text-align: center;">No attendance records found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
