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

// Fetch Global Camps/Events
$items = $conn->query("SELECT * FROM camps_events ORDER BY date DESC");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Global Camp Records - Admin</title>
    <style>
        body { font-family: sans-serif; background: #f0f2f5; margin: 0; padding: 20px; }
        .container { max-width: 1200px; margin: auto; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        h1 { color: #1a202c; border-bottom: 2px solid #edf2f7; padding-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; border-bottom: 1px solid #edf2f7; text-align: left; }
        th { background: #f7fafc; color: #4a5568; font-size: 13px; }
        .back { display: block; margin-bottom: 20px; text-decoration: none; color: #2b6cb0; font-weight: bold; }
        .badge { padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: bold; }
        .Camp { background: #ebf8ff; color: #2b6cb0; }
        .Event { background: #faf5ff; color: #6b46c1; }
    </style>
</head>
<body>

<div class="container">
    <a href="admin_dashboard_v2.php" class="back">← Back to Dashboard</a>
    <h1>Global Camp & Event Records</h1>
    
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Type</th>
                <th>Date</th>
                <th>Location</th>
                <th>Total Enrolled</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($items->num_rows > 0): ?>
                <?php while($row = $items->fetch_assoc()): ?>
                    <?php 
                        $count = $conn->query("SELECT COUNT(*) as count FROM enrollments WHERE item_id = " . $row['id'])->fetch_assoc()['count'];
                    ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($row['name']); ?></strong></td>
                        <td><span class="badge <?php echo $row['type']; ?>"><?php echo $row['type']; ?></span></td>
                        <td><?php echo date('d M Y', strtotime($row['date'])); ?></td>
                        <td><?php echo htmlspecialchars($row['location']); ?></td>
                        <td><?php echo $count; ?> / <?php echo $row['max_cadets']; ?></td>
                        <td><?php echo $row['status']; ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="6" style="text-align:center;">No records found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
